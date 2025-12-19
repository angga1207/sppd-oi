# HTML List to Word Conversion

## Masalah
Data `dasar` dan `tujuan` dari rich text editor (Quill) tersimpan dalam format HTML seperti:

```html
<ol>
  <li data-list="ordered">
    <span class="ql-ui" contenteditable="false"></span>
    Surat Kepala Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Lahat Nomor 401/500.12.18.3/KOMINFO-SP/2005
  </li>
  <li data-list="ordered">
    <span class="ql-ui" contenteditable="false"></span>
    DPA Dinas Komunikasi dan Informatika Kabupaten Ogan ilir Sub Kegiatan 2.16.03.2.02.0017 Koordinasi Pengelolaan Data dan Informasi.
  </li>
</ol>
```

## Solusi
Menambahkan method `convertHtmlListToText()` yang mengkonversi HTML list menjadi format teks yang sesuai untuk dokumen Word.

## Implementasi

### 1. Method Konversi
```php
private function convertHtmlListToText($html)
{
    // Handles:
    // - Ordered lists (<ol> dan data-list="ordered")
    // - Unordered lists (<ul> dan data-list="bullet")
    // - Quill editor specific markup (span.ql-ui)
    // - HTML entity decoding
    // - Text cleaning dan normalization
}
```

### 2. Format Output

#### Input HTML (Ordered List):
```html
<ol>
  <li data-list="ordered">
    <span class="ql-ui" contenteditable="false"></span>
    Item pertama
  </li>
  <li data-list="ordered">
    <span class="ql-ui" contenteditable="false"></span>
    Item kedua
  </li>
</ol>
```

#### Output Text:
```
1. Item pertama
2. Item kedua
```

#### Input HTML (Unordered List):
```html
<ul>
  <li data-list="bullet">
    <span class="ql-ui" contenteditable="false"></span>
    Item pertama
  </li>
  <li data-list="bullet">
    <span class="ql-ui" contenteditable="false"></span>
    Item kedua
  </li>
</ul>
```

#### Output Text:
```
• Item pertama
• Item kedua
```

## Fitur yang Didukung

1. **Ordered Lists**: Menggunakan nomor (1., 2., 3., dst)
2. **Unordered Lists**: Menggunakan bullet point (•)
3. **Quill Editor Format**: Menghapus markup `<span class="ql-ui">` 
4. **HTML Cleaning**: Strip HTML tags dan decode entities
5. **Multiple Format Support**: 
   - Standard `<ol>/<ul>` tags
   - Quill format dengan `data-list` attributes
   - Standalone `<li>` elements

## Penggunaan

### Sebelum (Manual):
```php
$dasar = htmlspecialchars($previewData->dasar ?? '');
$dasar = nl2br($dasar);
$templateProcessor->setValue('dasar', $dasar);
```

### Sesudah (Otomatis):
```php
$dasar = $this->convertHtmlListToText($previewData->dasar ?? '');
$templateProcessor->setValue('dasar', $dasar);
```

## Testing

Tambahkan method test untuk debugging:
```php
public function testHtmlConversion()
{
    $sampleHtml = '<ol><li data-list="ordered">...</li></ol>';
    $converted = $this->convertHtmlListToText($sampleHtml);
    Log::info('Conversion result:', ['converted' => $converted]);
}
```

## Edge Cases yang Ditangani

1. **Empty content**: Return string kosong
2. **No lists**: Return cleaned plain text
3. **Mixed content**: List + plain text
4. **Malformed HTML**: Fallback ke text cleaning
5. **Special characters**: Proper HTML entity decoding

Konversi ini memastikan bahwa data dari rich text editor Quill dapat ditampilkan dengan benar dalam dokumen Word yang dihasilkan oleh PhpWord.
