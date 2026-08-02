<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Carbon\Carbon;
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

class LeaveRequestExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithTitle
{
    protected string $dateFrom;
    protected string $dateTo;
    protected ?string $status;
    protected ?int $employeeId;

    public function __construct(string $dateFrom, string $dateTo, ?string $status = null, ?int $employeeId = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->status = $status;
        $this->employeeId = $employeeId;
    }

    public function title(): string
    {
        return 'Rekap Cuti';
    }

    public function collection()
    {
        $query = LeaveRequest::with('employee')
            ->whereBetween('start_date', [$this->dateFrom, $this->dateTo])
            ->orderBy('start_date');

        if ($this->status && in_array($this->status, ['pending', 'approved', 'rejected', 'cancelled'])) {
            $query->where('status', $this->status);
        }

        if ($this->employeeId) {
            $query->where('employee_id', $this->employeeId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Karyawan',
            'NIK',
            'Jenis Cuti',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Durasi (Hari)',
            'Alasan',
            'Status',
        ];
    }

    public function map($request): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $duration = $end->gte($start) ? $start->diffInDays($end) + 1 : 0;

        return [
            $rowNumber,
            $request->employee->full_name ?? '-',
            $request->employee->employee_code ?? '-',
            match ($request->leave_type) {
                'sick' => 'Cuti Sakit',
                'annual' => 'Cuti Tahunan',
                'emergency' => 'Cuti Darurat',
                default => $request->leave_type,
            },
            $request->start_date->format('d-m-Y'),
            $request->end_date->format('d-m-Y'),
            $duration,
            $request->reason ?? '-',
            match ($request->status) {
                'pending' => 'Pending',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'cancelled' => 'Dibatalkan',
                default => $request->status,
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 14,
            'D' => 15,
            'E' => 14,
            'F' => 14,
            'G' => 13,
            'H' => 45,
            'I' => 14,
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

        $sheet->getStyle('E2:G' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        foreach (range(2, $lastRow) as $row) {
            $status = $sheet->getCell('I' . $row)->getValue();
            $color = match ($status) {
                'Disetujui' => 'DCFCE7',
                'Ditolak' => 'FECACA',
                'Pending' => 'FEF9C3',
                'Dibatalkan' => 'F3F4F6',
                default => 'FFFFFF',
            };
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
