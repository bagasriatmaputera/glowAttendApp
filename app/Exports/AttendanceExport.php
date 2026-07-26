<?php

namespace App\Exports;

use App\Models\Attendance;
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

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    protected string $dateFrom;
    protected string $dateTo;
    protected ?int $employeeId;
    protected ?string $employeeName;

    public function __construct(string $dateFrom, string $dateTo, ?int $employeeId = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->employeeId = $employeeId;

        $this->employeeName = $employeeId
            ? Employee::find($employeeId)?->full_name
            : null;
    }

    public function title(): string
    {
        return 'Rekap Absensi';
    }

    public function collection()
    {
        $query = Attendance::with('employee')
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->orderBy('date');

        if ($this->employeeId) {
            $query->where('employee_id', $this->employeeId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Karyawan',
            'Divisi',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Keterangan',
        ];
    }

    public function map($attendance): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $attendance->employee->employee_code ?? '-',
            $attendance->employee->full_name ?? '-',
            $attendance->employee->position ?? '-',
            $attendance->date->format('d-m-Y'),
            $attendance->clock_in ? $attendance->clock_in->format('H:i:s') : '-',
            $attendance->clock_out ? $attendance->clock_out->format('H:i:s') : '-',
            match ($attendance->status) {
                'present' => 'Hadir',
                'late' => 'Terlambat',
                'absent' => 'Tidak Hadir',
                'on_leave' => 'Cuti',
                default => $attendance->status,
            },
            $attendance->notes ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 14,
            'C' => 25,
            'D' => 20,
            'E' => 14,
            'F' => 12,
            'G' => 12,
            'H' => 14,
            'I' => 30,
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
                'startColor' => ['rgb' => '4F46E5'],
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

        $sheet->getStyle('E2:H' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        foreach (range(2, $lastRow) as $row) {
            $status = $sheet->getCell('H' . $row)->getValue();
            $color = match ($status) {
                'Hadir' => 'DCFCE7',
                'Terlambat' => 'FED7AA',
                'Tidak Hadir' => 'FECACA',
                'Cuti' => 'BFDBFE',
                default => 'FFFFFF',
            };
            $sheet->getStyle('H' . $row)->applyFromArray([
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

