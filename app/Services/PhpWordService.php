<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Converter;
use Illuminate\Support\Facades\Storage;

class PhpWordService
{
    protected $phpWord;
    protected $config;

    public function __construct()
    {
        $this->phpWord = app('phpword');
        $this->config = config('phpword');
    }

    /**
     * Create a new PhpWord instance.
     */
    public function createDocument(): PhpWord
    {
        return new PhpWord();
    }

    /**
     * Create a new section with default page settings.
     */
    public function createSection(PhpWord $phpWord = null): Section
    {
        $phpWord = $phpWord ?: $this->phpWord;

        $sectionStyle = [
            'pageSizeW' => Converter::cmToTwip(21), // A4 width
            'pageSizeH' => Converter::cmToTwip(29.7), // A4 height
            'orientation' => $this->config['default_paper']['orientation'] ?? 'portrait',
        ];

        // Add margins if configured
        if (isset($this->config['default_paper']['margins'])) {
            $margins = $this->config['default_paper']['margins'];
            $sectionStyle = array_merge($sectionStyle, [
                'marginTop' => $margins['top'],
                'marginBottom' => $margins['bottom'],
                'marginLeft' => $margins['left'],
                'marginRight' => $margins['right'],
            ]);
        }

        return $phpWord->addSection($sectionStyle);
    }

    /**
     * Load template from file.
     */
    public function loadTemplate(string $templatePath): TemplateProcessor
    {
        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found: {$templatePath}");
        }

        return new TemplateProcessor($templatePath);
    }

    /**
     * Save document to file.
     */
    public function saveDocument(PhpWord $phpWord, string $filename, string $format = 'Word2007'): string
    {
        $outputPath = $this->config['templates']['output_path'] ?? storage_path('app/documents/');

        // Ensure output directory exists
        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        $filepath = $outputPath . $filename;

        $writer = IOFactory::createWriter($phpWord, $format);
        $writer->save($filepath);

        return $filepath;
    }

    /**
     * Save template processor to file.
     */
    public function saveTemplate(TemplateProcessor $templateProcessor, string $filename): string
    {
        $outputPath = $this->config['templates']['output_path'] ?? storage_path('app/documents/');

        // Ensure output directory exists
        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        $filepath = $outputPath . $filename;
        $templateProcessor->saveAs($filepath);

        return $filepath;
    }

    /**
     * Download document as response.
     */
    public function downloadDocument(string $filepath, string $downloadName = null): \Illuminate\Http\Response
    {
        if (!file_exists($filepath)) {
            throw new \Exception("Document file not found: {$filepath}");
        }

        $downloadName = $downloadName ?: basename($filepath);

        return response()->download($filepath, $downloadName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    /**
     * Create table with default styling.
     */
    public function createTable(Section $section, array $data, array $headers = [], string $tableStyle = 'default')
    {
        $tableStyleConfig = $this->config['table_styles'][$tableStyle] ?? $this->config['table_styles']['default'];

        $table = $section->addTable([
            'borderSize' => $tableStyleConfig['borderSize'],
            'borderColor' => $tableStyleConfig['borderColor'] ?? '000000',
            'cellMargin' => $tableStyleConfig['cellMargin'] ?? 80,
            'alignment' => $tableStyleConfig['alignment'] ?? 'center',
            'width' => $tableStyleConfig['width'] ?? 100,
            'unit' => $tableStyleConfig['unit'] ?? 'pct',
        ]);

        // Add headers if provided
        if (!empty($headers)) {
            $table->addRow();
            foreach ($headers as $header) {
                $cell = $table->addCell();
                $cell->addText($header, $this->config['styles']['table_header'] ?? []);
            }
        }

        // Add data rows
        foreach ($data as $row) {
            $table->addRow();
            foreach ($row as $cellData) {
                $cell = $table->addCell();
                $cell->addText($cellData, $this->config['styles']['table_content'] ?? []);
            }
        }

        return $table;
    }

    /**
     * Add heading to section.
     */
    public function addHeading(Section $section, string $text, int $level = 1): void
    {
        $styleName = "heading{$level}";
        $style = $this->config['styles'][$styleName] ?? $this->config['styles']['heading1'];

        $section->addText($text, $style);
    }

    /**
     * Add paragraph to section.
     */
    public function addParagraph(Section $section, string $text, array $fontStyle = [], array $paragraphStyle = []): void
    {
        $defaultFontStyle = $this->config['styles']['normal'] ?? [];
        $fontStyle = array_merge($defaultFontStyle, $fontStyle);

        $section->addText($text, $fontStyle, $paragraphStyle);
    }

    /**
     * Format currency according to configuration.
     */
    public function formatCurrency(float $amount): string
    {
        $format = $this->config['number_format']['currency'] ?? [
            'symbol' => 'Rp ',
            'thousands_separator' => '.',
            'decimal_separator' => ',',
            'decimals' => 0,
        ];

        return $format['symbol'] . number_format(
            $amount,
            $format['decimals'],
            $format['decimal_separator'],
            $format['thousands_separator']
        );
    }

    /**
     * Format date according to configuration.
     */
    public function formatDate($date, bool $short = false): string
    {
        $format = $short ?
            ($this->config['number_format']['date_format_short'] ?? 'd/m/Y') :
            ($this->config['number_format']['date_format'] ?? 'd F Y');

        return \Carbon\Carbon::parse($date)->format($format);
    }

    /**
     * Get template path for specific document type.
     */
    public function getTemplatePath(string $type): string
    {
        $templates = $this->config['templates'] ?? [];

        if (!isset($templates[$type])) {
            throw new \Exception("Template type '{$type}' not configured");
        }

        return $templates[$type];
    }

    /**
     * Replace variables in template.
     */
    public function replaceTemplateVariables(TemplateProcessor $template, array $variables): void
    {
        foreach ($variables as $key => $value) {
            $template->setValue($key, $value);
        }
    }

    /**
     * Clean up temporary files.
     */
    public function cleanup(array $files): void
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
