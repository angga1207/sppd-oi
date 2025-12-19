<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileSPPDResource extends JsonResource
{
    /**
     * Convert HTML ordered/unordered lists to Word-compatible format
     */
    private function convertHtmlListToText($html)
    {
        if (empty($html)) {
            return '';
        }

        // Remove HTML tags except list items - handle Quill editor format
        $html = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $html);
        $html = preg_replace('/<span[^>]*contenteditable=["\']false["\'][^>]*><\/span>/i', '', $html);

        // Handle ordered lists (ol with li elements containing data-list="ordered")
        if (preg_match('/<ol[^>]*>(.*?)<\/ol>/is', $html, $olMatch)) {
            $listContent = $olMatch[1];
            if (preg_match_all('/<li[^>]*(?:data-list=["\']ordered["\'])?[^>]*>(.*?)<\/li>/is', $listContent, $matches)) {
                $result = '';
                foreach ($matches[1] as $index => $item) {
                    // Clean up the item content
                    $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                    $cleanItem = strip_tags($cleanItem);
                    $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                    $cleanItem = trim($cleanItem);

                    if (!empty($cleanItem)) {
                        $result .= ($index + 1) . '. ' . $cleanItem . "\n";
                    }
                }
                return trim($result);
            }
        }

        // Handle unordered lists (ul with li elements containing data-list="bullet")
        if (preg_match('/<ul[^>]*>(.*?)<\/ul>/is', $html, $ulMatch)) {
            $listContent = $ulMatch[1];
            if (preg_match_all('/<li[^>]*(?:data-list=["\']bullet["\'])?[^>]*>(.*?)<\/li>/is', $listContent, $matches)) {
                $result = '';
                foreach ($matches[1] as $item) {
                    // Clean up the item content
                    $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                    $cleanItem = strip_tags($cleanItem);
                    $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                    $cleanItem = trim($cleanItem);

                    if (!empty($cleanItem)) {
                        $result .= '• ' . $cleanItem . "\n";
                    }
                }
                return trim($result);
            }
        }

        // Handle standalone li elements with data-list="ordered" (Quill format without ol wrapper)
        if (preg_match_all('/<li[^>]*data-list=["\']ordered["\'][^>]*>(.*?)<\/li>/is', $html, $matches)) {
            $result = '';
            foreach ($matches[1] as $index => $item) {
                // Clean up the item content
                $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                $cleanItem = strip_tags($cleanItem);
                $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                $cleanItem = trim($cleanItem);

                if (!empty($cleanItem)) {
                    $result .= ($index + 1) . '. ' . $cleanItem . "\n";
                }
            }
            return trim($result);
        }

        // Handle standalone li elements with data-list="bullet" (Quill format without ul wrapper)
        if (preg_match_all('/<li[^>]*data-list=["\']bullet["\'][^>]*>(.*?)<\/li>/is', $html, $matches)) {
            $result = '';
            foreach ($matches[1] as $item) {
                // Clean up the item content
                $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                $cleanItem = strip_tags($cleanItem);
                $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                $cleanItem = trim($cleanItem);

                if (!empty($cleanItem)) {
                    $result .= '• ' . $cleanItem . "\n";
                }
            }
            return trim($result);
        }

        // If no list found, just clean the HTML
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text); // Normalize whitespace
        return trim($text);
    }

    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        $return = [
            'id' => $this->id,
            'surat_perintah_id' => $this->surat_perintah_id,
            'uuid' => $this->uuid,
            'nomor_sppd' => $this->nomor_sppd,
            'nomor_spt' => $this->suratPerintah ? $this->suratPerintah->nomor_surat : null,

            'tingkat_biaya' => $this->tingkat_biaya,
            'maksud_perjalanan' => $this->maksud_perjalanan ? $this->convertHtmlListToText($this->maksud_perjalanan) : null,
            'alat_angkutan' => $this->alat_angkutan,
            'tempat_berangkat' => $this->tempat_berangkat,
            'tempat_tujuan' => $this->tempat_tujuan . ', ' . $this->regency?->name . ', ' . $this->province?->name,
            'lama_perjalanan' => $this->lama_perjalanan,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_berangkat_string' => $this->tanggal_berangkat ? Carbon::parse($this->tanggal_berangkat)->isoFormat('D MMMM Y - HH:mm [WIB]') : null,
            'tanggal_pulang' => $this->tanggal_pulang,
            'tanggal_pulang_string' => $this->tanggal_pulang ? Carbon::parse($this->tanggal_pulang)->isoFormat('D MMMM Y - HH:mm [WIB]') : null,

            'instance_pembebanan' => $this->instancePembebanan ? $this->instancePembebanan->name : null,
            'mata_anggaran' => ($this->kode_rekening && $this->uraian_rekening) ? $this->kode_rekening . ' - ' . $this->uraian_rekening : null,

            'keterangan_lain' => $this->keterangan_lain,
            'status' => $this->status,

            'PejabatPemberiPerintah' => [
                'semesta_id' => $this->employeeGiver->semesta_id,
                'nama_lengkap' => $this->employeeGiver->nama_lengkap,
                'nip' => $this->employeeGiver->nip,
                'jenis_pegawai' => $this->employeeGiver->jenis_pegawai,
                'jabatan' => $this->employeeGiver->jabatan,
                'instansi' => $this->employeeGiver->instance ? $this->employeeGiver->instance->name : null,
                'foto_pegawai' => $this->employeeGiver->foto_pegawai ? asset($this->employeeGiver->foto_pegawai) : null,
                'email' => $this->employeeGiver->email,
                'no_hp' => $this->employeeGiver->no_hp,
                'eselon' => $this->employeeGiver->eselon,
                'golongan' => $this->employeeGiver->golongan,
                'pangkat' => $this->employeeGiver->pangkat,
            ],

            'PejabatPelaksana' => [
                'semesta_id' => $this->employeeExecutor->semesta_id,
                'nama_lengkap' => $this->employeeExecutor->nama_lengkap,
                'nip' => $this->employeeExecutor->nip,
                'jenis_pegawai' => $this->employeeExecutor->jenis_pegawai,
                'jabatan' => $this->employeeExecutor->jabatan,
                'instansi' => $this->employeeExecutor->instance ? $this->employeeExecutor->instance->name : null,
                'foto_pegawai' => $this->employeeExecutor->foto_pegawai ? asset($this->employeeExecutor->foto_pegawai) : null,
                'email' => $this->employeeExecutor->email,
                'no_hp' => $this->employeeExecutor->no_hp,
                'eselon' => $this->employeeExecutor->eselon,
                'golongan' => $this->employeeExecutor->golongan,
                'pangkat' => $this->employeeExecutor->pangkat,
            ],
        ];

        return $return;
    }
}
