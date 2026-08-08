<?php

namespace Tests\Feature;

use App\Livewire\Approval\Index as ApprovalIndex;
use App\Livewire\Employee\Index as EmployeeIndex;
use App\Models\Employee;
use App\Models\PendingChangeRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionApprovalTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $username, string $roleName): User
    {
        $role = Role::firstOrCreate(['name' => $roleName]);

        $user = User::create([
            'username' => $username,
            'email' => $username.'@example.com',
            'password' => 'password',
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function createEmployee(User $user, string $code = 'SLN-TEST'): Employee
    {
        return Employee::create([
            'user_id' => $user->id,
            'employee_code' => $code,
            'full_name' => $user->username,
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'position' => 'Stylist',
            'join_date' => now()->toDateString(),
        ]);
    }

    public function test_approvals_page_is_owner_only(): void
    {
        $owner = $this->createUser('owner', 'Management');
        $this->actingAs($owner)->get('/approvals')->assertOk();

        $admin = $this->createUser('admin1', 'Admin');
        $this->actingAs($admin)->get('/approvals')->assertForbidden();

        $employee = $this->createUser('pegawai1', 'Employee');
        $this->actingAs($employee)->get('/approvals')->assertForbidden();
    }

    public function test_owner_delete_employee_deletes_directly(): void
    {
        $owner = $this->createUser('owner', 'Management');
        $employee = $this->createEmployee($this->createUser('emp1', 'Employee'));

        Livewire::actingAs($owner)
            ->test(EmployeeIndex::class)
            ->set('employee_id', $employee->id)
            ->call('delete');

        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
        $this->assertDatabaseCount('pending_change_requests', 0);
    }

    public function test_admin_delete_employee_creates_pending_request(): void
    {
        $admin = $this->createUser('admin', 'Admin');
        $employee = $this->createEmployee($this->createUser('emp', 'Employee'));

        Livewire::actingAs($admin)
            ->test(EmployeeIndex::class)
            ->set('employee_id', $employee->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertNotSoftDeleted('employees', ['id' => $employee->id]);
        $this->assertDatabaseHas('pending_change_requests', [
            'type' => PendingChangeRequest::TYPE_DELETE_EMPLOYEE,
            'status' => PendingChangeRequest::STATUS_PENDING,
            'requested_by' => $admin->id,
        ]);
    }

    public function test_admin_cannot_delete_or_edit_admin_account(): void
    {
        $admin = $this->createUser('admin', 'Admin');
        $targetAdmin = $this->createUser('admin2', 'Admin');
        $targetEmployee = $this->createEmployee($targetAdmin);

        Livewire::actingAs($admin)
            ->test(EmployeeIndex::class)
            ->set('employee_id', $targetEmployee->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertNotSoftDeleted('employees', ['id' => $targetEmployee->id]);
        $this->assertDatabaseCount('pending_change_requests', 0);

        Livewire::actingAs($admin)
            ->test(EmployeeIndex::class)
            ->call('edit', $targetEmployee->id)
            ->assertSet('showEmployeeModal', false);
    }

    public function test_admin_cannot_promote_account_to_owner(): void
    {
        $admin = $this->createUser('admin', 'Admin');
        $admin2 = $this->createUser('admin2', 'Admin');
        $employee = $this->createEmployee($admin2);

        Livewire::actingAs($admin)
            ->test(EmployeeIndex::class)
            ->set('employee_id', $employee->id)
            ->set('role', 'Owner')
            ->call('store');

        $this->assertTrue($admin2->fresh()->hasRole('Admin'));
        $this->assertFalse($admin2->fresh()->hasRole('Management'));
    }

    public function test_owner_cannot_delete_last_owner(): void
    {
        $owner = $this->createUser('owner', 'Management');
        $ownerEmployee = $this->createEmployee($owner, 'SLN-OWN');

        Livewire::actingAs($owner)
            ->test(EmployeeIndex::class)
            ->set('employee_id', $ownerEmployee->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertNotSoftDeleted('employees', ['id' => $ownerEmployee->id]);
    }

    public function test_owner_cannot_delete_own_account(): void
    {
        $owner = $this->createUser('owner', 'Management');
        $ownerEmployee = $this->createEmployee($owner, 'SLN-OWN');

        Livewire::actingAs($owner)
            ->test(EmployeeIndex::class)
            ->set('employee_id', $ownerEmployee->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertNotSoftDeleted('employees', ['id' => $ownerEmployee->id]);
    }

    public function test_owner_approves_pending_delete_request(): void
    {
        $owner = $this->createUser('owner', 'Management');
        $admin = $this->createUser('admin', 'Admin');
        $employee = $this->createEmployee($this->createUser('emp', 'Employee'));

        $request = PendingChangeRequest::create([
            'type' => PendingChangeRequest::TYPE_DELETE_EMPLOYEE,
            'payload' => ['employee_id' => $employee->id],
            'requested_by' => $admin->id,
            'status' => PendingChangeRequest::STATUS_PENDING,
        ]);

        Livewire::actingAs($owner)
            ->test(ApprovalIndex::class)
            ->call('approve', $request->id);

        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
        $this->assertDatabaseHas('pending_change_requests', [
            'id' => $request->id,
            'status' => PendingChangeRequest::STATUS_APPROVED,
            'approved_by' => $owner->id,
        ]);
    }

    public function test_owner_rejects_pending_delete_request(): void
    {
        $owner = $this->createUser('owner', 'Management');
        $admin = $this->createUser('admin', 'Admin');
        $employee = $this->createEmployee($this->createUser('emp', 'Employee'));

        $request = PendingChangeRequest::create([
            'type' => PendingChangeRequest::TYPE_DELETE_EMPLOYEE,
            'payload' => ['employee_id' => $employee->id],
            'requested_by' => $admin->id,
            'status' => PendingChangeRequest::STATUS_PENDING,
        ]);

        Livewire::actingAs($owner)
            ->test(ApprovalIndex::class)
            ->call('reject', $request->id);

        $this->assertNotSoftDeleted('employees', ['id' => $employee->id]);
        $this->assertDatabaseHas('pending_change_requests', [
            'id' => $request->id,
            'status' => PendingChangeRequest::STATUS_REJECTED,
            'approved_by' => $owner->id,
        ]);
    }
}
