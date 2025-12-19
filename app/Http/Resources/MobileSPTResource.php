<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileSPTResource extends JsonResource
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
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'nomor_spt' => $this->nomor_surat,
            'dasar' => $this->dasar ? $this->convertHtmlListToText($this->dasar) : null,
            'tujuan' => $this->tujuan ? $this->convertHtmlListToText($this->tujuan) : null,
            'status' => $this->status,
            'file_pdf' => $this->file_pdf ? asset('storage/surat_perintah_tugas/' . $this->file_pdf) : null,
            'file_pdf_signed' => $this->file_pdf_signed ? asset('storage/surat_perintah_tugas_sign/' . $this->file_pdf_signed) : null,
            'tanggal_tte' => $this->tanggal_tte,

            'Instansi' => [
                'nama' => $this->instance ? $this->instance->name : null,
                'alias' => $this->instance ? $this->instance->alias : null,
                'alamat' => $this->instance ? $this->instance->address : null,
                'telepon' => $this->instance ? $this->instance->phone : null,
                'logo' => $this->instance ? asset($this->instance->logo) : null,
            ],

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

            'Penandatangan' => [
                'date' => $this->publication_date,
                'date_string' => $this->publication_date ? Carbon::parse($this->publication_date)->isoFormat('D MMMM Y') : null,
                'tempat' => $this->publication_place,
                'nama_lengkap' => $this->publicationEmployee ? $this->publicationEmployee->nama_lengkap : null,
                'nip' => $this->publicationEmployee ? $this->publicationEmployee->nip : null,
                'jabatan' => $this->publicationEmployee ? $this->publicationEmployee->jabatan : null,
            ],

            'jumlah_sppd' => $this->sppds ? $this->sppds->count() : 0,
            'Sppds' => MobileSPPDResource::collection($this->sppds),

            'created_at' => $this->created_at,
            'createt_at_string' => $this->created_at ? Carbon::parse($this->created_at)->isoFormat('D MMMM Y - HH:mm [WIB]') : null,
            'updated_at' => $this->updated_at,
            'updated_at_string' => $this->updated_at ? Carbon::parse($this->updated_at)->isoFormat('D MMMM Y - HH:mm [WIB]') : null,
        ];

        return $data;

        return parent::toArray($request);
    }
}
