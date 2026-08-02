<?php

namespace Tests\Feature;

use App\Livewire\HomeAttendPage;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AttendanceDistanceTest extends TestCase
{
    use RefreshDatabase;

    private function createEmployeeUser(): User
    {
        $user = User::create([
            'username' => 'employee',
            'email' => 'employee@example.com',
            'password' => 'password',
        ]);

        $user->assignRole(Role::create(['name' => 'Employee']));

        Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'SLN-001',
            'full_name' => 'Employee Test',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'position' => 'Stylist',
            'join_date' => now()->toDateString(),
        ]);

        return $user;
    }

    private function createOfficeLocation(): OfficeLocation
    {
        return OfficeLocation::create([
            'name' => 'Kantor Glow',
            'address' => 'Jl. Sudirman',
            'latitude' => -6.2087634,
            'longitude' => 106.8455990,
            'radius' => 100,
            'clock_in_time' => '00:00',
            'clock_out_time' => '23:59',
            'is_active' => true,
        ]);
    }

    public function test_clock_in_succeeds_when_inside_radius(): void
    {
        $user = $this->createEmployeeUser();
        $location = $this->createOfficeLocation();

        Livewire::actingAs($user)
            ->test(HomeAttendPage::class)
            ->call('clockIn', (float) $location->latitude, (float) $location->longitude)
            ->assertSet('errorMessage', '')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $user->employee->id,
            'office_location_id' => $location->id,
        ]);
    }

    public function test_clock_in_is_rejected_when_outside_radius(): void
    {
        $user = $this->createEmployeeUser();
        $location = $this->createOfficeLocation();

        Livewire::actingAs($user)
            ->test(HomeAttendPage::class)
            ->call('clockIn', $location->latitude + 0.01, $location->longitude)
            ->assertSet('errorMessage', 'Anda berada di luar area kantor. Clock In ditolak.');

        $this->assertDatabaseMissing('attendances', [
            'employee_id' => $user->employee->id,
        ]);
    }

    public function test_clock_in_is_rejected_when_no_active_location(): void
    {
        $user = $this->createEmployeeUser();

        Livewire::actingAs($user)
            ->test(HomeAttendPage::class)
            ->call('clockIn', -6.2087634, 106.8455990)
            ->assertSet('errorMessage', 'Lokasi kantor belum diatur. Silakan hubungi Administrator.');
    }

    public function test_clock_out_is_rejected_when_outside_radius(): void
    {
        $user = $this->createEmployeeUser();
        $location = $this->createOfficeLocation();

        Attendance::create([
            'employee_id' => $user->employee->id,
            'office_location_id' => $location->id,
            'date' => now()->toDateString(),
            'clock_in' => now(),
            'latitude_in' => $location->latitude,
            'longitude_in' => $location->longitude,
            'status' => 'present',
        ]);

        Livewire::actingAs($user)
            ->test(HomeAttendPage::class)
            ->call('clockOut', $location->latitude + 0.01, $location->longitude)
            ->assertSet('errorMessage', 'Anda berada di luar area kantor. Clock Out ditolak.');

        $this->assertNull(Attendance::first()->clock_out);
    }

    public function test_clock_out_succeeds_when_inside_radius(): void
    {
        $user = $this->createEmployeeUser();
        $location = $this->createOfficeLocation();

        Attendance::create([
            'employee_id' => $user->employee->id,
            'office_location_id' => $location->id,
            'date' => now()->toDateString(),
            'clock_in' => now(),
            'latitude_in' => $location->latitude,
            'longitude_in' => $location->longitude,
            'status' => 'present',
        ]);

        Livewire::actingAs($user)
            ->test(HomeAttendPage::class)
            ->call('clockOut', (float) $location->latitude, (float) $location->longitude)
            ->assertSet('errorMessage', '');

        $this->assertNotNull(Attendance::first()->clock_out);
    }
}
