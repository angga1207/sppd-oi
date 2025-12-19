<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Sppd;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SppdExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithColumnFormatting, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query data dengan filter
     */
    public function query()
    {
        $query = SPPD::query()->with(['employeeGiver', 'employeeExecutor', 'instance', 'instancePembebanan']);

        // Apply filters
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sppd', 'like', '%' . $search . '%')
                    ->orWhere('tempat_tujuan', 'like', '%' . $search . '%')
                    ->orWhere('maksud_perjalanan', 'like', '%' . $search . '%')
                    ->orWhereHas('employeeExecutor', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', '%' . $search . '%')
                            ->orWhere('nip', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('employeeGiver', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', '%' . $search . '%')
                            ->orWhere('nip', 'like', '%' . $search . '%');
                    });
            });
        }

        if (!empty($this->filters['statusFilter'])) {
            $query->where('status', $this->filters['statusFilter']);
        }

        if (!empty($this->filters['instanceFilter'])) {
            $query->where('instance_id', $this->filters['instanceFilter']);
        }

        if (!empty($this->filters['startDateFilter'])) {
            $query->whereDate('tanggal_berangkat', '>=', $this->filters['startDateFilter']);
        }

        if (!empty($this->filters['endDateFilter'])) {
            $query->whereDate('tanggal_pulang', '<=', $this->filters['endDateFilter']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Header columns sesuai struktur database
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nomor SPPD',
            'Nama Instansi',
            'Pejabat Pemberi (Nama)',
            'Pejabat Pemberi (NIP)',
            'Pegawai Pelaksana (Nama)',
            'Pegawai Pelaksana (NIP)',
            'Tingkat Biaya',
            'Maksud Perjalanan',
            'Alat Angkutan',
            'Tempat Berangkat',
            'Tempat Tujuan',
            'Lama Perjalanan (Hari)',
            'Tanggal Berangkat',
            'Tanggal Pulang',
            'Instansi Pembebanan Anggaran',
            'Kode Rekening',
            'Uraian Rekening',
            'Anggaran',
            'Keterangan Lain',
            'Status',
        ];
    }

    /**
     * Map data sesuai struktur database
     */
    public function map($sppd): array
    {
        $tingkatBiayaOptions = Employee::getTingkatBiayaOptions();
        $tingkatBiaya = $tingkatBiayaOptions[array_search($sppd->tingkat_biaya, array_column($tingkatBiayaOptions, 'value'))]['label'] ?? $sppd->tingkat_biaya;

        return [
            $sppd->id,
            $sppd->nomor_sppd,
            $sppd->instance?->name ?? '',
            $sppd->employeeGiver?->nama_lengkap ?? '',
            "'" . ($sppd->employeeGiver?->nip ?? ''), // Prefix dengan apostrophe untuk format text
            $sppd->employeeExecutor?->nama_lengkap ?? '',
            "'" . ($sppd->employeeExecutor?->nip ?? ''), // Prefix dengan apostrophe untuk format text
            // $sppd->tingkat_biaya,
            $tingkatBiaya ?? $sppd->tingkat_biaya,
            $sppd->maksud_perjalanan,
            $sppd->alat_angkutan,
            $sppd->tempat_berangkat,
            $sppd->tempat_tujuan,
            $sppd->lama_perjalanan,
            $sppd->tanggal_berangkat ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($sppd->tanggal_berangkat) : '',
            $sppd->tanggal_pulang ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($sppd->tanggal_pulang) : '',
            $sppd->instancePembebanan?->name ?? '',
            $sppd->kode_rekening,
            $sppd->uraian_rekening,
            $sppd->anggaran,
            $sppd->keterangan_lain,
            ucfirst($sppd->status),
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0C2B4E'], // Primary color
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,  // ID
            'B' => 20, // Nomor SPPD
            'C' => 30, // Nama Instansi
            'D' => 30, // Pejabat Pemberi (Nama)
            'E' => 20, // Pejabat Pemberi (NIP)
            'F' => 30, // Pegawai Pelaksana (Nama)
            'G' => 20, // Pegawai Pelaksana (NIP)
            'H' => 15, // Tingkat Biaya
            'I' => 40, // Maksud Perjalanan
            'J' => 15, // Alat Angkutan
            'K' => 25, // Tempat Berangkat
            'L' => 25, // Tempat Tujuan
            'M' => 12, // Lama Perjalanan
            'N' => 15, // Tanggal Berangkat
            'O' => 15, // Tanggal Pulang
            'P' => 30, // Instance Pembebanan (Nama)
            'Q' => 20, // Kode Rekening
            'R' => 30, // Uraian Rekening
            'S' => 15, // Anggaran
            'T' => 30, // Keterangan Lain
            'U' => 12, // Status
        ];
    }

    /**
     * Column formatting untuk tanggal dan NIP
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // NIP Pejabat Pemberi
            'G' => NumberFormat::FORMAT_TEXT, // NIP Pegawai Pelaksana
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Tanggal Berangkat
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Tanggal Pulang
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Anggaran dengan separator
        ];
    }
}
