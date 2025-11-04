# Export Excel SPPD - Dokumentasi

## 📋 Overview

Fitur Export Excel untuk SPPD List menggunakan **maatwebsite/excel v3.1** dengan struktur data yang mengikuti **database migration** untuk memudahkan proses import di masa depan.

---

## 🎯 Fitur Utama

### 1. **Export dengan Filter**
Export akan mengikuti filter yang aktif:
- ✅ Search (nomor SPPD, nama pegawai, NIP, tujuan)
- ✅ Filter Instansi
- ✅ Filter Status (pending, approved, rejected, completed)
- ✅ Filter Tanggal Berangkat (dari)
- ✅ Filter Tanggal Pulang (sampai)

### 2. **Format Excel**
- Header dengan styling (background #0C2B4E, text putih, bold)
- Auto-width columns
- Format tanggal: `Y-m-d` (2025-11-03)
- Format datetime: `Y-m-d H:i:s` (2025-11-03 14:30:00)
- Border untuk semua cells
- Alignment center untuk header

### 3. **Nama File**
Format: `SPPD_Export_YYYY-MM-DD_HHmmss.xlsx`  
Contoh: `SPPD_Export_2025-11-03_143055.xlsx`

---

## 📊 Struktur Kolom Excel

Total **34 kolom** sesuai struktur database:

| No | Kolom | Tipe Data | Keterangan |
|----|-------|-----------|------------|
| 1 | ID | Integer | Primary key |
| 2 | Nomor SPPD | String | Unique identifier |
| 3 | Instance ID | Integer | Foreign key ke instances |
| 4 | Nama Instansi | String | Relasi: instance->name |
| 5 | Employee Giver ID | Integer | Foreign key ke employees |
| 6 | Pejabat Pemberi (Nama) | String | Relasi: employeeGiver->nama_lengkap |
| 7 | Pejabat Pemberi (NIP) | String | Relasi: employeeGiver->nip |
| 8 | Employee Executor ID | Integer | Foreign key ke employees |
| 9 | Pegawai Pelaksana (Nama) | String | Relasi: employeeExecutor->nama_lengkap |
| 10 | Pegawai Pelaksana (NIP) | String | Relasi: employeeExecutor->nip |
| 11 | Tingkat Biaya | String | - |
| 12 | Maksud Perjalanan | Text | - |
| 13 | Alat Angkutan | String | - |
| 14 | Tempat Berangkat | String | - |
| 15 | Tempat Tujuan | String | - |
| 16 | Lama Perjalanan (Hari) | Integer | - |
| 17 | Tanggal Berangkat | Date | Format: Y-m-d |
| 18 | Tanggal Pulang | Date | Format: Y-m-d |
| 19 | Instance Pembebanan ID | Integer | Foreign key ke instances |
| 20 | Instance Pembebanan (Nama) | String | Relasi: instancePembebanan->name |
| 21 | Kode Rekening | String | - |
| 22 | Uraian Rekening | String | - |
| 23 | Anggaran | Double | - |
| 24 | Keterangan Lain | Text | - |
| 25 | Tanggal Publikasi | Date | Format: Y-m-d |
| 26 | Tempat Publikasi | String | - |
| 27 | Publication Employee ID | Integer | Foreign key ke employees |
| 28 | Pegawai Publikasi (Nama) | String | Relasi: publicationEmployee->nama_lengkap |
| 29 | Status | Enum | draft/approved/rejected/completed |
| 30 | Created By | Integer | Foreign key ke users |
| 31 | Approved By | Integer | Foreign key ke users |
| 32 | Approved At | Timestamp | Format: Y-m-d H:i:s |
| 33 | Created At | Timestamp | Format: Y-m-d H:i:s |
| 34 | Updated At | Timestamp | Format: Y-m-d H:i:s |

---

## 🔧 Technical Implementation

### Files Created/Modified:

#### 1. **app/Exports/SppdExport.php**
```php
namespace App\Exports;

use App\Models\Sppd;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SppdExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithColumnWidths, 
    ShouldAutoSize
{
    // Implementation with filters support
}
```

**Features:**
- `FromQuery`: Efficient memory usage dengan query builder
- `WithHeadings`: Custom header columns
- `WithMapping`: Custom data mapping sesuai struktur DB
- `WithStyles`: Styling Excel (colors, borders, alignment)
- `WithColumnWidths`: Custom lebar kolom
- `ShouldAutoSize`: Auto-resize columns

#### 2. **app/Livewire/Admin/SppdList.php**
```php
use App\Exports\SppdExport;
use Maatwebsite\Excel\Facades\Excel;

public function exportExcel()
{
    $filters = [
        'search' => $this->search,
        'statusFilter' => $this->statusFilter,
        'instanceFilter' => $this->instanceFilter,
        'startDateFilter' => $this->startDateFilter,
        'endDateFilter' => $this->endDateFilter,
    ];

    $fileName = 'SPPD_Export_' . date('Y-m-d_His') . '.xlsx';

    return Excel::download(new SppdExport($filters), $fileName);
}
```

#### 3. **resources/views/livewire/admin/sppd-list.blade.php**
```blade
<button wire:click="exportExcel" class="btn-secondary">
    <svg wire:loading.remove wire:target="exportExcel">...</svg>
    <svg wire:loading wire:target="exportExcel" class="animate-spin">...</svg>
    <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
    <span wire:loading wire:target="exportExcel">Memproses...</span>
</button>
```

**Features:**
- Loading state dengan spinner animasi
- Disabled saat proses export
- Success notification via LivewireAlert

---

## 💡 Cara Penggunaan

### Export Semua Data:
1. Buka halaman SPPD List
2. Klik tombol "Export Excel"
3. File akan otomatis terdownload

### Export dengan Filter:
1. Set filter yang diinginkan (Instansi, Status, Tanggal, dll)
2. Klik tombol "Export Excel"
3. **Hanya data yang sesuai filter** yang akan di-export

---

## 🎨 UI/UX Features

### Button Styling:
- **Normal State**: Secondary button dengan icon download
- **Loading State**: 
  - Icon berubah jadi spinner animasi
  - Text "Export Excel" → "Memproses..."
  - Button disabled (tidak bisa diklik ulang)

### Notifications:
- **Success**: Toast notification hijau (top-end, 3 detik)
- **Error**: Toast notification merah (top-end, 5 detik)

---

## 📦 Dependencies

```json
{
    "maatwebsite/excel": "^3.1",
    "phpoffice/phpspreadsheet": "^1.30"
}
```

---

## 🔮 Future Enhancement Ideas

1. **Import Excel**: Reverse process untuk import data SPPD
2. **Export PDF**: Alternative format selain Excel
3. **Template Export**: Custom template dengan logo instansi
4. **Scheduled Export**: Auto export berkala via cron
5. **Export History**: Track siapa export apa dan kapan
6. **Batch Export**: Export per instansi/periode tertentu
7. **Email Export**: Kirim hasil export via email

---

## ⚠️ Important Notes

### Untuk Import (Future):
Karena format export mengikuti struktur database, saat membuat fitur import:

1. **ID Columns** (col 1, 3, 5, 8, 19, 27, 30, 31):
   - Bisa diabaikan saat import baru
   - Gunakan untuk update data existing

2. **Foreign Keys** validation:
   - Instance ID harus exist di table `instances`
   - Employee IDs harus exist di table `employees`
   - User IDs harus exist di table `users`

3. **Dates Format**:
   - Input harus format `Y-m-d` atau `Y-m-d H:i:s`
   - Validasi tanggal berangkat <= tanggal pulang

4. **Status Enum**:
   - Hanya terima: draft, approved, rejected, completed
   - Case-sensitive

5. **Required Fields** (sesuai migration):
   - nomor_sppd (unique)
   - Status memiliki default 'draft'

---

## 🎯 Best Practices

1. **Memory Efficient**: Menggunakan `FromQuery` bukan `FromCollection`
2. **Eager Loading**: Load semua relasi sekali jalan
3. **Null Handling**: Semua field nullable di-handle dengan `??`
4. **Date Formatting**: Consistent format untuk tanggal
5. **Filter Sync**: Export mengikuti exact filter yang ditampilkan

---

✅ **Export Excel SPPD siap digunakan!**
