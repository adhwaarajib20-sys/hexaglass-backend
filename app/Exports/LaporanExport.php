<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanExport implements
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
        $query = Laporan::with(['pelapor', 'verifikator', 'foto']);

        if ($this->dari_tanggal && $this->sampai_tanggal) {
            // Convert Carbon objects to date strings untuk whereBetween
            $dari = is_string($this->dari_tanggal) ? $this->dari_tanggal : $this->dari_tanggal->format('Y-m-d');
            $sampai = is_string($this->sampai_tanggal) ? $this->sampai_tanggal : $this->sampai_tanggal->format('Y-m-d');
            
            $query->whereBetween('tanggal_kejadian', [
                $dari,
                $sampai,
            ]);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pelapor',
            'Perusahaan',
            'Tanggal Kejadian',
            'Waktu Kejadian',
            'Lokasi',
            'Klasifikasi',
            'Deskripsi',
            'Rekomendasi',
            'Status',
            'Catatan Admin',
            'Diverifikasi Oleh',
            'Jumlah Foto',
            'Tanggal Lapor',
        ];
    }

    public function map($laporan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $laporan->nama_pelapor,
            $laporan->perusahaan ?? '-',
            $laporan->tanggal_kejadian
                ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d/m/Y')
                : '-',
            $laporan->waktu_kejadian ?? '-',
            $laporan->lokasi,
            ucfirst($laporan->klasifikasi),
            $laporan->deskripsi,
            $laporan->rekomendasi ?? '-',
            ucfirst($laporan->status),
            $laporan->catatan_admin ?? '-',
            $laporan->verifikator?->name ?? '-',
            $laporan->foto->count(),
            \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y H:i'),
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
        return 'Laporan Ketidaksesuaian';
    }
}