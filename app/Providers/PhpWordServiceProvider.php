<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Font;
use Illuminate\Support\Facades\Log;

class PhpWordServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('phpword', function ($app) {
            // Create PhpWord instance
            $phpWord = new PhpWord();

            // Get configuration
            $config = config('phpword');

            // Set default font
            if (isset($config['default_font'])) {
                $phpWord->setDefaultFontName($config['default_font']['name']);
                $phpWord->setDefaultFontSize($config['default_font']['size']);
            }

            // Set document properties
            if (isset($config['document_properties'])) {
                $properties = $phpWord->getDocInfo();
                $docProperties = $config['document_properties'];

                if ($docProperties['creator']) {
                    $properties->setCreator($docProperties['creator']);
                }
                if ($docProperties['company']) {
                    $properties->setCompany($docProperties['company']);
                }
                if ($docProperties['title']) {
                    $properties->setTitle($docProperties['title']);
                }
                if ($docProperties['description']) {
                    $properties->setDescription($docProperties['description']);
                }
                if ($docProperties['category']) {
                    $properties->setCategory($docProperties['category']);
                }
                if ($docProperties['last_modified_by']) {
                    $properties->setLastModifiedBy($docProperties['last_modified_by']);
                }
                if ($docProperties['subject']) {
                    $properties->setSubject($docProperties['subject']);
                }
                if ($docProperties['keywords']) {
                    $properties->setKeywords($docProperties['keywords']);
                }

                // Set timestamps
                $now = time();
                $properties->setCreated($docProperties['created'] ?? $now);
                $properties->setModified($docProperties['modified'] ?? $now);
            }

            return $phpWord;
        });

        // Register PhpWord facade
        $this->app->alias('phpword', PhpWord::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set PhpWord settings
        $config = config('phpword');

        // Set default font
        if (isset($config['default_font'])) {
            Settings::setDefaultFontName($config['default_font']['name']);
            Settings::setDefaultFontSize($config['default_font']['size']);
        }

        // Create necessary directories
        $this->createDirectories();

        // Define default styles
        $this->defineDefaultStyles();
    }

    /**
     * Create necessary directories for templates and output.
     */
    protected function createDirectories(): void
    {
        $config = config('phpword');

        // Create templates directory
        if (isset($config['templates'])) {
            $templateDir = dirname($config['templates']['surat_perintah']);
            if (!file_exists($templateDir)) {
                mkdir($templateDir, 0755, true);
            }

            // Create output directory
            $outputDir = $config['templates']['output_path'];
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
        }
    }

    /**
     * Define default styles for PhpWord.
     */
    protected function defineDefaultStyles(): void
    {
        $config = config('phpword.styles', []);

        foreach ($config as $styleName => $styleConfig) {
            try {
                // Define paragraph styles
                if (in_array($styleName, ['heading1', 'heading2', 'normal'])) {
                    $paragraphStyle = [];
                    $fontStyle = [];

                    // Extract font styles
                    if (isset($styleConfig['name'])) {
                        $fontStyle['name'] = $styleConfig['name'];
                    }
                    if (isset($styleConfig['size'])) {
                        $fontStyle['size'] = $styleConfig['size'];
                    }
                    if (isset($styleConfig['bold'])) {
                        $fontStyle['bold'] = $styleConfig['bold'];
                    }
                    if (isset($styleConfig['color'])) {
                        $fontStyle['color'] = $styleConfig['color'];
                    }

                    // Extract paragraph styles
                    if (isset($styleConfig['spaceAfter'])) {
                        $paragraphStyle['spaceAfter'] = $styleConfig['spaceAfter'];
                    }
                    if (isset($styleConfig['alignment'])) {
                        $paragraphStyle['alignment'] = $styleConfig['alignment'];
                    }

                    // For now, we'll just store the styles in config
                    // Actual style definition will happen when creating documents
                }
            } catch (\Exception $e) {
                // Log error but continue
                Log::warning("Failed to define PhpWord style '{$styleName}': " . $e->getMessage());
            }
        }
    }
}
