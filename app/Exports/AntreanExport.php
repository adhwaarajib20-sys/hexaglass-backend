<?php

namespace App\Exports;

use App\Models\Antrean;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AntreanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected $dari_tanggal;
    protected $sampai_tanggal;

    public function __construct($dari_tanggal = null, $sampai_tanggal = null)
    {
        $this->dari_tanggal   = $dari_tanggal;
        $this->sampai_tanggal = $sampai_tanggal;
    }

    public function collection()
    {
        $query = Antrean::with([
            'kendaraan',
            'operator',
            'laporanPengisian'
        ]);

        if ($this->dari_tanggal && $this->sampai_tanggal) {
            $query->whereBetween('tanggal', [
                $this->dari_tanggal,
                $this->sampai_tanggal,
            ]);
        }

        return $query->latest('waktu_daftar')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Antrean',
            'Tanggal',
            'Nama Supir',
            'No HP Supir',
            'No Polisi',
            'Jenis Kendaraan',
            'Kapasitas Tangki',
            'Perusahaan',
            'Status',
            'Status Validasi Satpam',
            'Prioritas',
            'Alasan Prioritas',
            'Estimasi (Menit)',
            'Jumlah Gas (Liter)',
            'Durasi Pengisian (Menit)',
            'Operator',
            'Waktu Daftar',
            'Waktu Dipanggil',
            'Waktu Selesai',
            'Catatan',
        ];
    }

    public function map($antrean): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $antrean->nomor_antrean,
            \Carbon\Carbon::parse($antrean->tanggal)->format('d/m/Y'),
            $antrean->kendaraan?->nama_supir ?? '-',
            $antrean->kendaraan?->no_hp_supir ?? '-',
            $antrean->kendaraan?->nomor_polisi ?? '-',
            $antrean->kendaraan?->jenis_kendaraan ?? '-',
            $antrean->kendaraan?->kapasitas_tangki ?? '-',
            $antrean->kendaraan?->perusahaan ?? '-',
            ucfirst($antrean->status),
            ucfirst(str_replace('_', ' ', $antrean->status_validasi_satpam ?? '-')),
            $antrean->is_prioritas ? 'Ya' : 'Tidak',
            $antrean->alasan_prioritas ?? '-',
            $antrean->estimasi_menit ?? '-',
            $antrean->jumlah_gas_liter ?? '-',
            $antrean->laporanPengisian?->durasi_menit ?? '-',
            $antrean->operator?->name ?? '-',
            $antrean->waktu_daftar
                ? \Carbon\Carbon::parse($antrean->waktu_daftar)->format('d/m/Y H:i')
                : '-',
            $antrean->waktu_dipanggil
                ? \Carbon\Carbon::parse($antrean->waktu_dipanggil)->format('d/m/Y H:i')
                : '-',
            $antrean->waktu_selesai
                ? \Carbon\Carbon::parse($antrean->waktu_selesai)->format('d/m/Y H:i')
                : '-',
            $antrean->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1a7a2e'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Riwayat Antrean';
    }
}