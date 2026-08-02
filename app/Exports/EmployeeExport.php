<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    protected ?string $role;
    protected ?int $status;

    public function __construct(?string $role = null, ?int $status = null)
    {
        $this->role = $role;
        $this->status = $status;
    }

    public function title(): string
    {
        return 'Data Karyawan';
    }

    public function collection()
    {
        $query = Employee::with('user.roles')->orderBy('full_name');

        if ($this->role && in_array($this->role, ['Management', 'Admin', 'Employee'])) {
            $query->whereHas('user.roles', function ($q) {
                $q->where('name', $this->role);
            });
        }

        if (! is_null($this->status)) {
            $query->where('is_active', (bool) $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Lengkap',
            'Email',
            'Username',
            'Telepon',
            'Jabatan',
            'Tanggal Bergabung',
            'Status',
            'Role',
        ];
    }

    public function map($employee): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $employee->employee_code ?? '-',
            $employee->full_name ?? '-',
            $employee->user?->email ?? '-',
            $employee->user?->username ?? '-',
            $employee->phone ?? '-',
            $employee->position ?? '-',
            $employee->join_date ? $employee->join_date->format('d-m-Y') : '-',
            $employee->is_active ? 'Aktif' : 'Tidak Aktif',
            $employee->user?->roles->first()?->name ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 14,
            'C' => 25,
            'D' => 28,
            'E' => 20,
            'F' => 18,
            'G' => 20,
            'H' => 18,
            'I' => 14,
            'J' => 14,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D9488'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A1:' . $lastCol . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        $sheet->getStyle('A2:' . $lastCol . $lastRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:A' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('I2:J' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        foreach (range(2, $lastRow) as $row) {
            $status = $sheet->getCell('I' . $row)->getValue();
            $color = $status === 'Aktif' ? 'DCFCE7' : 'FECACA';
            $sheet->getStyle('I' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color],
                ],
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        $sheet->getRowDimension(1)->setRowHeight(20);
    }
}
