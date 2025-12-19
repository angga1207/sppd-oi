# QR Code Package Setup - SPPD OI

## Overview
Package Simple QRCode telah berhasil diinstall dan dikonfigurasi untuk sistem SPPD-OI. Package ini memungkinkan aplikasi untuk generate QR Code yang berisi link untuk scanning surat perintah dan SPPD.

## Package Installation
```bash
composer require simplesoftwareio/simple-qrcode
```

**Dependencies yang terinstall:**
- `simplesoftwareio/simple-qrcode` v4.2.0
- `bacon/bacon-qr-code` v2.0.8 
- `dasprid/enum` v1.0.7

## Configuration Files

### 1. QR Code Configuration (`config/qrcode.php`)
```php
<?php

return [
    // Default format: png, svg, eps
    'format' => env('QRCODE_FORMAT', 'svg'),
    
    // Default size in pixels
    'size' => env('QRCODE_SIZE', 300),
    
    // Default margin
    'margin' => env('QRCODE_MARGIN', 10),
    
    // Error correction levels: L, M, Q, H
    'errorCorrectionLevel' => env('QRCODE_ERROR_CORRECTION', 'M'),
    
    // Character encoding
    'encoding' => env('QRCODE_ENCODING', 'UTF-8'),
    
    // Colors
    'colors' => [
        'foreground' => env('QRCODE_FOREGROUND_COLOR', '#000000'),
        'background' => env('QRCODE_BACKGROUND_COLOR', '#FFFFFF'),
    ],
    
    // Storage settings
    'storage' => [
        'disk' => env('QRCODE_STORAGE_DISK', 'public'),
        'path' => env('QRCODE_STORAGE_PATH', 'qrcodes'),
    ],
    
    // Application specific settings
    'sppd' => [
        'enabled' => env('QRCODE_SPPD_ENABLED', true),
        'size' => env('QRCODE_SPPD_SIZE', 150),
        'include_logo' => env('QRCODE_SPPD_INCLUDE_LOGO', false),
        'logo_path' => env('QRCODE_SPPD_LOGO_PATH', 'images/logo.png'),
    ],
    
    'surat_perintah' => [
        'enabled' => env('QRCODE_SPT_ENABLED', true),
        'size' => env('QRCODE_SPT_SIZE', 150),
        'include_logo' => env('QRCODE_SPT_INCLUDE_LOGO', false),
        'logo_path' => env('QRCODE_SPT_LOGO_PATH', 'images/logo.png'),
    ],
];
```

## Service Class

### QrCodeService (`app/Services/QrCodeService.php`)

#### Available Methods:

**1. Generate Surat Perintah QR Code:**
```php
public function generateSuratPerintahQrCode($suratPerintahId, $options = [])
```

**2. Generate SPPD QR Code:**
```php
public function generateSppdQrCode($sppdId, $options = [])
```

**3. Generate Custom QR Code:**
```php
public function generateCustomQrCode($url, $options = [])
```

**4. Save QR Code to Storage:**
```php
public function saveQrCode($data, $filename, $options = [])
```

**5. Generate Inline QR Code:**
```php
public function generateInlineQrCode($data, $options = [])
```

**6. Generate with Verification:**
```php
public function generateVerificationQrCode($type, $id, $additionalData = [])
```

## Implementation in Preview Component

### Added Features:
1. **QR Code Generation** - Generate QR code untuk surat perintah
2. **Toggle Display** - Show/hide QR code
3. **Download QR** - Download QR code sebagai PNG
4. **Copy URL** - Copy scan URL ke clipboard
5. **Inline Display** - Tampilkan QR code di halaman

### New Component Properties:
```php
public $qrCodeSvg = null;        // Store generated QR code SVG
public $showQrCode = false;      // Control QR code visibility
```

### New Component Methods:
```php
public function generateQrCode()     // Generate QR code
public function downloadQrCode()     // Download QR as PNG
public function getScanUrl()         // Get scan URL
public function copyUrl()            // Copy URL to clipboard
public function toggleQrCode()       // Toggle QR visibility
```

## UI Components Added

### 1. Action Buttons:
- **Generate QR Code** - Generate dan tampilkan QR code
- **Download QR** - Download QR code sebagai file PNG
- **Copy URL** - Copy scan URL ke clipboard
- **Hide QR** - Sembunyikan QR code

### 2. QR Code Display Section:
- **QR Code Viewer** - Display QR code dengan styling
- **URL Information** - Tampilkan scan URL dengan copy button
- **Usage Instructions** - Petunjuk cara scan QR code
- **Statistics** - Info ID surat dan tanggal generate

### 3. Interactive Features:
- **Alpine.js Integration** - Copy to clipboard functionality
- **Responsive Design** - Mobile-friendly layout
- **Real-time Updates** - Livewire reactive components

## Scan URLs Generated

### Surat Perintah:
```
https://domain.com/spt/{id}
```

### SPPD:
```
https://domain.com/sppd/{id}
```

## QR Code Features

### 1. Format Support:
- **SVG** - Vector format, scalable, default untuk display
- **PNG** - Raster format, untuk download
- **EPS** - Vector format, untuk print

### 2. Customization Options:
- **Size** - Ukuran QR code dalam pixels
- **Colors** - Foreground dan background colors
- **Margin** - Margin around QR code
- **Error Correction** - Level koreksi error (L, M, Q, H)
- **Logo Integration** - Embed logo di tengah QR code

### 3. Verification Data:
```php
[
    'generated_at' => '2025-11-26T10:00:00Z',
    'type' => 'spt',
    'id' => 123,
    'additional_data' => [...]
]
```

## Usage Examples

### Basic QR Generation:
```php
// In Livewire component
$qrCodeService = app(QrCodeService::class);
$qrCode = $qrCodeService->generateSuratPerintahQrCode($id);
```

### Custom Options:
```php
$qrCode = $qrCodeService->generateSuratPerintahQrCode($id, [
    'size' => 300,
    'format' => 'png',
    'margin' => 20,
    'foregroundColor' => '#000000',
    'backgroundColor' => '#FFFFFF'
]);
```

### With Logo:
```php
$qrCode = $qrCodeService->generateSuratPerintahQrCode($id, [
    'logo' => true,
    'logoPath' => public_path('images/logo.png')
]);
```

## Environment Variables

Tambahkan ke `.env` file:
```env
# QR Code Settings
QRCODE_FORMAT=svg
QRCODE_SIZE=300
QRCODE_MARGIN=10
QRCODE_ERROR_CORRECTION=M
QRCODE_ENCODING=UTF-8

# Colors
QRCODE_FOREGROUND_COLOR=#000000
QRCODE_BACKGROUND_COLOR=#FFFFFF

# Storage
QRCODE_STORAGE_DISK=public
QRCODE_STORAGE_PATH=qrcodes

# SPPD Settings
QRCODE_SPPD_ENABLED=true
QRCODE_SPPD_SIZE=150
QRCODE_SPPD_INCLUDE_LOGO=false

# Surat Perintah Settings
QRCODE_SPT_ENABLED=true
QRCODE_SPT_SIZE=150
QRCODE_SPT_INCLUDE_LOGO=false
```

## Security Features

### 1. URL Validation:
- Validasi format URL
- Check domain ownership
- Prevent external URLs

### 2. Verification Data:
- Timestamp generation
- Type identification
- Additional metadata

### 3. Access Control:
- Routes protected by middleware
- User authentication required
- Public scan routes available

## Mobile Compatibility

### QR Code Scanning:
1. **Native Camera Apps** - iOS Camera, Google Lens
2. **QR Scanner Apps** - Dedicated scanner applications
3. **Web Browsers** - Modern browsers with camera access

### Responsive Design:
- Mobile-first approach
- Touch-friendly buttons
- Adaptive QR code sizing
- Optimized for small screens

## Testing

### Access Preview Page:
1. Navigate to Surat Perintah preview
2. Click "Generate QR Code" button
3. QR code akan muncul dengan scan URL
4. Test scan dengan smartphone
5. Verify redirect ke scan page

### QR Code Validation:
- Check URL format
- Test scan functionality
- Verify responsive design
- Test download feature

## Troubleshooting

### Common Issues:

1. **QR Code tidak muncul:**
   - Check package installation
   - Verify service injection
   - Clear configuration cache

2. **Download error:**
   - Check file permissions
   - Verify storage configuration
   - Test with different formats

3. **Scan URL tidak work:**
   - Verify route configuration
   - Check APP_URL setting
   - Test route accessibility

### Debug Commands:
```bash
php artisan config:clear
php artisan route:list
php artisan storage:link
```

QR Code package sudah fully configured dan ready untuk digunakan! 🚀
