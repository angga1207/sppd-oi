# PhpWord Package Setup - SPPD OI

## Overview
PhpOffice/PhpWord package telah berhasil diinstall dan dikonfigurasi untuk sistem SPPD-OI. Package ini memungkinkan aplikasi untuk generate dokumen Microsoft Word (.docx) secara programatik.

## Struktur File

### 1. Package Installation
```
composer require phpoffice/phpword
```

### 2. Configuration Files
- `config/phpword.php` - Konfigurasi utama PhpWord
- `app/Providers/PhpWordServiceProvider.php` - Service Provider untuk Laravel integration
- `bootstrap/providers.php` - Provider registration

### 3. Service Class
- `app/Services/PhpWordService.php` - Helper service untuk operasi PhpWord

### 4. Controllers
- `app/Http/Controllers/DocumentController.php` - Controller untuk document generation

### 5. Testing Component
- `app/Livewire/Admin/Documents/TestGenerator.php` - Testing interface
- `resources/views/livewire/admin/documents/test-generator.blade.php` - UI untuk testing

### 6. Directory Structure
```
storage/
├── app/
    ├── documents/          # Output directory untuk generated documents
    └── templates/          # Directory untuk template files
        └── README.md       # Template documentation
```

## Configuration Details

### PhpWord Configuration (`config/phpword.php`)

#### Default Font Settings
```php
'default_font' => [
    'name' => 'Times New Roman',
    'size' => 12,
],
```

#### Paper Settings
```php
'default_paper' => [
    'size' => 'A4',
    'orientation' => 'portrait',
    'margins' => [
        'top' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
        'bottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
        'left' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
        'right' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
    ],
],
```

#### Template Paths
```php
'templates' => [
    'path' => storage_path('app/templates/'),
    'output_path' => storage_path('app/documents/'),
    'sppd' => storage_path('app/templates/template_sppd.docx'),
    'surat_perintah' => storage_path('app/templates/template_surat_perintah.docx'),
    'laporan_perjalanan' => storage_path('app/templates/template_laporan_perjalanan.docx'),
    'kuitansi' => storage_path('app/templates/template_kuitansi.docx'),
],
```

## Available Routes

### Document Generation API Routes
```php
POST /admin/documents/sppd                    # Generate SPPD document
POST /admin/documents/surat-perintah          # Generate Surat Perintah document  
POST /admin/documents/laporan-perjalanan      # Generate Laporan Perjalanan Dinas
POST /admin/documents/custom                  # Generate custom document from template
```

### Testing Route
```php
GET /admin/documents/test                     # PhpWord testing interface
```

## Usage Examples

### 1. Using PhpWordService
```php
use App\Services\PhpWordService;

class ExampleController extends Controller
{
    protected PhpWordService $phpWordService;

    public function __construct(PhpWordService $phpWordService)
    {
        $this->phpWordService = $phpWordService;
    }

    public function generateDocument()
    {
        // Create new document
        $phpWord = $this->phpWordService->createDocument();
        $section = $this->phpWordService->createSection($phpWord);

        // Add content
        $this->phpWordService->addHeading($section, 'Document Title', 1);
        $this->phpWordService->addParagraph($section, 'Document content...');

        // Save document
        $filename = 'example_document.docx';
        $filepath = $this->phpWordService->saveDocument($phpWord, $filename);

        // Return download response
        return $this->phpWordService->downloadDocument($filepath, $filename);
    }
}
```

### 2. Using Template Processor
```php
public function generateFromTemplate($data)
{
    // Load template
    $templatePath = $this->phpWordService->getTemplatePath('sppd');
    $template = $this->phpWordService->loadTemplate($templatePath);

    // Replace variables
    $variables = [
        'EMPLOYEE_NAME' => $data['employee_name'],
        'EMPLOYEE_NIP' => $data['employee_nip'],
        'POSITION' => $data['position'],
        // ... other variables
    ];
    
    $this->phpWordService->replaceTemplateVariables($template, $variables);

    // Save and download
    $filename = "SPPD_{$data['letter_number']}.docx";
    $filepath = $this->phpWordService->saveTemplate($template, $filename);
    
    return $this->phpWordService->downloadDocument($filepath, $filename);
}
```

### 3. Document Controller Usage
```php
// Generate SPPD
$response = DocumentController::generateSppd($request);

// Generate Surat Perintah  
$response = DocumentController::generateSuratPerintah($request);

// Generate Laporan Perjalanan
$response = DocumentController::generateLaporanPerjalananDinas($request);
```

## Helper Methods

### PhpWordService Available Methods

#### Document Creation
- `createDocument()` - Create new PhpWord instance
- `createSection($phpWord)` - Create new section with default settings

#### Template Operations  
- `loadTemplate($templatePath)` - Load template file
- `replaceTemplateVariables($template, $variables)` - Replace template variables
- `saveTemplate($template, $filename)` - Save template to file

#### Document Operations
- `saveDocument($phpWord, $filename)` - Save document to file
- `downloadDocument($filepath, $downloadName)` - Return download response

#### Content Creation
- `addHeading($section, $text, $level)` - Add heading
- `addParagraph($section, $text, $fontStyle, $paragraphStyle)` - Add paragraph
- `createTable($section, $data, $headers, $tableStyle)` - Create table

#### Formatting Helpers
- `formatCurrency($amount)` - Format currency (Rupiah)
- `formatDate($date, $short)` - Format date (Indonesian)

#### Configuration Helpers
- `getTemplatePath($type)` - Get template path for document type

## Template Variables

### SPPD Template Variables
```
${EMPLOYEE_NAME}      - Nama pegawai
${EMPLOYEE_NIP}       - NIP pegawai  
${POSITION}           - Jabatan
${DESTINATION}        - Tujuan perjalanan
${PURPOSE}            - Maksud perjalanan
${DEPARTURE_DATE}     - Tanggal berangkat
${RETURN_DATE}        - Tanggal kembali
${TRANSPORTATION}     - Transportasi
${BUDGET}             - Anggaran
${BUDGET_SOURCE}      - Sumber anggaran
${LETTER_NUMBER}      - Nomor surat
${GENERATED_DATE}     - Tanggal generate
```

### Surat Perintah Template Variables
```
${LETTER_NUMBER}      - Nomor surat
${LETTER_DATE}        - Tanggal surat
${TO_NAME}            - Nama yang dituju
${TO_POSITION}        - Jabatan yang dituju
${SUBJECT}            - Perihal
${CONTENT}            - Isi surat
${FROM_NAME}          - Nama pemberi perintah
${FROM_POSITION}      - Jabatan pemberi perintah
${FROM_NIP}           - NIP pemberi perintah
${GENERATED_DATE}     - Tanggal generate
```

## Testing

### Accessing Test Interface
1. Navigate to `/admin/documents/test`
2. Login as admin user
3. Run configuration tests
4. Test document generation

### Test Functions
- **Test Config**: Verifikasi konfigurasi PhpWord
- **Test Directories**: Check template dan output directories
- **Generate Document**: Create sample SPPD document

## File Permissions

Pastikan directories berikut memiliki permission yang tepat:
```bash
chmod 755 storage/app/documents
chmod 755 storage/app/templates
```

## Troubleshooting

### Common Issues

1. **Error: Template file not found**
   - Solution: Ensure template files exist in `storage/app/templates/`
   - Create template files using Microsoft Word with variables format `${VARIABLE_NAME}`

2. **Permission denied writing file**
   - Solution: Check file permissions on storage directories
   - Run: `php artisan storage:link`

3. **Memory limit exceeded for large documents**
   - Solution: Increase PHP memory limit in php.ini
   - Or use streaming for large document generation

4. **Character encoding issues**
   - Solution: Ensure UTF-8 encoding in templates and data
   - Check PhpWord character encoding settings

### Debug Mode
Enable debug logging in `config/phpword.php`:
```php
'debug' => env('APP_DEBUG', false),
```

## Next Steps

1. **Create Template Files**
   - Design Word templates in Microsoft Word
   - Save as .docx in `storage/app/templates/`
   - Use variable format `${VARIABLE_NAME}`

2. **Integrate with Existing Forms**
   - Add download buttons to SPPD and Surat Perintah forms
   - Connect forms to DocumentController methods

3. **Add Digital Signature**
   - Integrate with digital signature modal
   - Add signature validation before document generation

4. **Customize Styles**
   - Modify styles in `config/phpword.php`
   - Create organization-specific document templates

## Dependencies

- **phpoffice/phpword**: ^1.4.0
- **phpoffice/math**: ^0.3.0 (auto-installed)
- Laravel 11+
- PHP 8.1+

## License
Same as main application license.
