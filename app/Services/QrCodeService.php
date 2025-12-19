<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('qrcode');
    }

    /**
     * Generate QR Code for Surat Perintah
     */
    public function generateSuratPerintahQrCode($suratPerintahId, $options = [])
    {
        $url = route('scan.spt', ['id' => $suratPerintahId]);

        $size = $options['size'] ?? $this->config['surat_perintah']['size'] ?? $this->config['size'];
        $format = $options['format'] ?? $this->config['format'];

        return $this->generateQrCode($url, $size, $format, $options);
    }

    /**
     * Generate QR Code for SPPD
     */
    public function generateSppdQrCode($sppdId, $options = [])
    {
        $url = route('scan.sppd', ['id' => $sppdId]);

        $size = $options['size'] ?? $this->config['sppd']['size'] ?? $this->config['size'];
        $format = $options['format'] ?? $this->config['format'];

        return $this->generateQrCode($url, $size, $format, $options);
    }

    /**
     * Generate QR Code with custom URL
     */
    public function generateCustomQrCode($url, $options = [])
    {
        $size = $options['size'] ?? $this->config['size'];
        $format = $options['format'] ?? $this->config['format'];

        return $this->generateQrCode($url, $size, $format, $options);
    }

    /**
     * Generate QR Code
     */
    private function generateQrCode($data, $size = 300, $format = 'svg', $options = [])
    {
        $qrCode = QrCode::format($format)
            ->size($size)
            ->margin($options['margin'] ?? $this->config['margin'])
            ->errorCorrection($options['errorCorrection'] ?? $this->config['errorCorrectionLevel'])
            ->encoding($options['encoding'] ?? $this->config['encoding']);

        // Set colors if provided
        if (isset($options['foregroundColor']) || isset($options['backgroundColor'])) {
            $qrCode->color(
                $options['foregroundColor'] ?? $this->config['colors']['foreground'],
                $options['backgroundColor'] ?? $this->config['colors']['background']
            );
        }

        // Add logo if specified
        if (isset($options['logo']) && $options['logo']) {
            $logoPath = $options['logoPath'] ?? public_path($this->config['sppd']['logo_path']);
            if (file_exists($logoPath)) {
                $qrCode->merge($logoPath, 0.3, true);
            }
        }

        return $qrCode->generate($data);
    }

    /**
     * Save QR Code to storage
     */
    public function saveQrCode($data, $filename, $options = [])
    {
        $qrCodeData = $this->generateQrCode($data, $options['size'] ?? 300, 'png', $options);

        $disk = $options['disk'] ?? $this->config['storage']['disk'];
        $path = $options['path'] ?? $this->config['storage']['path'];

        $fullPath = $path . '/' . $filename;

        Storage::disk($disk)->put($fullPath, $qrCodeData);

        return Storage::disk($disk)->url($fullPath);
    }

    /**
     * Generate QR Code for display (inline)
     */
    public function generateInlineQrCode($data, $options = [])
    {
        $format = $options['format'] ?? 'svg';
        $qrCodeData = $this->generateQrCode($data, $options['size'] ?? 200, $format, $options);

        if ($format === 'svg') {
            return $qrCodeData;
        }

        // For PNG format, return base64 encoded data URI
        return 'data:image/png;base64,' . base64_encode($qrCodeData);
    }

    /**
     * Generate QR Code with verification info
     */
    public function generateVerificationQrCode($type, $id, $additionalData = [])
    {
        $baseUrl = $type === 'spt' ? route('scan.spt', ['id' => $id]) : route('scan.sppd', ['id' => $id]);

        // Add timestamp and verification data
        $verificationData = array_merge([
            'generated_at' => now()->toISOString(),
            'type' => $type,
            'id' => $id,
        ], $additionalData);

        $url = $baseUrl . '?' . http_build_query($verificationData);

        return $this->generateQrCode($url, 200, 'svg');
    }

    /**
     * Get QR Code configuration
     */
    public function getConfig($key = null)
    {
        if ($key) {
            return data_get($this->config, $key);
        }

        return $this->config;
    }

    /**
     * Validate QR Code URL
     */
    public function validateQrUrl($url)
    {
        // Basic URL validation
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check if URL belongs to our domain
        $appDomain = parse_url(config('app.url'), PHP_URL_HOST);
        $urlDomain = parse_url($url, PHP_URL_HOST);

        return $appDomain === $urlDomain;
    }
}
