# SPPD Design System

## 🎨 Color Palette

Palet warna baru yang modern dan profesional:

| Color Name | Hex Code | Usage | Tailwind Class |
|-----------|----------|-------|----------------|
| **Primary** | `#0C2B4E` | Deep Navy - Warna utama aplikasi | `bg-primary`, `text-primary`, `border-primary` |
| **Secondary** | `#1A3D64` | Navy Blue - Warna sekunder | `bg-secondary`, `text-secondary` |
| **Accent** | `#1D546C` | Teal Blue - Aksen dan highlight | `bg-accent`, `text-accent` |
| **Light** | `#F4F4F4` | Light Gray - Background | `bg-light` |
| **Muted** | `#5D688A` | Slate - Text secondary | `text-muted` |

### Backward Compatibility
- `navy` → alias untuk `primary` (#0C2B4E)
- `blue-light` → alias untuk `secondary` (#1A3D64)

---

## 📝 Form Components

### Input Fields

#### Base Input
```html
<input type="text" class="form-input" placeholder="Masukkan teks...">
```

#### Input with Error
```html
<input type="text" class="form-input-error" placeholder="Masukkan teks...">
<p class="form-error-message">Pesan error di sini</p>
```

#### Input with Success
```html
<input type="text" class="form-input-success" placeholder="Teks valid">
```

### Textarea
```html
<textarea class="form-textarea" placeholder="Masukkan deskripsi..."></textarea>
```

### Select (Native)
```html
<select class="form-select">
    <option value="">Pilih opsi</option>
    <option value="1">Opsi 1</option>
</select>
```

### Label
```html
<!-- Basic Label -->
<label class="form-label">Nama Field</label>

<!-- Label with Required Indicator -->
<label class="form-label form-label-required">Nama Field</label>
<!-- Output: "Nama Field *" -->
```

### Helper & Error Text
```html
<!-- Helper Text -->
<p class="form-helper-text">Informasi tambahan untuk input</p>

<!-- Error Message -->
<p class="form-error-message">
    <svg class="h-4 w-4">...</svg>
    Pesan error di sini
</p>
```

---

## 🔘 Buttons

### Primary Button
```html
<button class="btn-primary">
    <svg class="h-5 w-5 mr-2">...</svg>
    Simpan Data
</button>
```

### Secondary Button
```html
<button class="btn-secondary">
    Batal
</button>
```

### Accent Button
```html
<button class="btn-accent">
    <svg class="h-5 w-5 mr-2">...</svg>
    Ambil Data
</button>
```

---

## 📦 Cards

### Basic Card
```html
<div class="card">
    <div class="card-header">
        <h3>Judul Card</h3>
        <p>Deskripsi singkat</p>
    </div>
    <div class="card-body">
        <!-- Konten card -->
    </div>
    <div class="card-footer">
        <!-- Action buttons -->
    </div>
</div>
```

---

## 🗂️ Tabs

### Tab Navigation
```html
<nav class="tab-nav">
    <button type="button" class="tab-button-active">
        <svg>...</svg>
        Tab Aktif
    </button>
    <button type="button" class="tab-button">
        <svg>...</svg>
        Tab Inactive
    </button>
</nav>
```

---

## 🏷️ Badges

```html
<!-- Primary Badge -->
<span class="badge-primary">Active</span>

<!-- Success Badge -->
<span class="badge-success">Approved</span>

<!-- Warning Badge -->
<span class="badge-warning">Pending</span>

<!-- Danger Badge -->
<span class="badge-danger">Rejected</span>
```

---

## 🔔 Alerts

```html
<!-- Success Alert -->
<div class="alert-success">
    <strong>Success!</strong> Data berhasil disimpan.
</div>

<!-- Info Alert -->
<div class="alert-info">
    <strong>Info:</strong> Informasi penting.
</div>

<!-- Warning Alert -->
<div class="alert-warning">
    <strong>Warning:</strong> Peringatan!
</div>

<!-- Danger Alert -->
<div class="alert-danger">
    <strong>Error!</strong> Terjadi kesalahan.
</div>
```

---

## 🎛️ Select2 Custom Styling

Select2 sudah dikustomisasi secara otomatis dengan palet warna baru:
- Border color: Gray-300
- Focus color: Primary (#0C2B4E)
- Selected item: Secondary (#1A3D64)
- Hover item: Primary (#0C2B4E)

```html
<select class="select2" wire:model="field" data-placeholder="Pilih opsi">
    <option value="">-- Pilih --</option>
    <option value="1">Opsi 1</option>
</select>
```

---

## 📐 Layout Utilities

### Responsive Container
```html
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Content -->
</div>
```

### Card Grid
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Cards -->
</div>
```

### Hide Scrollbar
```html
<div class="overflow-x-auto scrollbar-hide">
    <!-- Content dengan horizontal scroll tanpa scrollbar -->
</div>
```

---

## 🌈 Gradient Backgrounds

```html
<!-- Primary to Secondary Gradient -->
<div class="bg-gradient-to-r from-primary to-secondary">
    <!-- Content -->
</div>

<!-- Text with Gradient -->
<h1 class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
    Gradient Text
</h1>
```

---

## 📱 Responsive Design

Semua komponen sudah responsive dengan breakpoints:
- `sm:` - 640px and up
- `md:` - 768px and up
- `lg:` - 1024px and up
- `xl:` - 1280px and up

### Contoh Responsive Class
```html
<!-- Stack di mobile, row di desktop -->
<div class="flex flex-col sm:flex-row gap-4">
    <!-- Content -->
</div>

<!-- Full width di mobile, auto di desktop -->
<button class="w-full sm:w-auto btn-primary">
    Submit
</button>

<!-- Hide di mobile, show di desktop -->
<span class="hidden sm:inline">Detail Text</span>
```

---

## 🎨 Usage Examples

### Complete Form Example
```html
<div class="space-y-6">
    <div>
        <label class="form-label form-label-required">Email</label>
        <input type="email" class="form-input" placeholder="email@example.com">
        <p class="form-helper-text">Gunakan email aktif</p>
    </div>

    <div>
        <label class="form-label form-label-required">Pesan</label>
        <textarea class="form-textarea" placeholder="Tulis pesan..."></textarea>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <button type="submit" class="btn-primary">
            <svg class="h-5 w-5 mr-2">...</svg>
            Kirim
        </button>
        <button type="button" class="btn-secondary">
            Batal
        </button>
    </div>
</div>
```

---

## 🚀 Migration Guide

### Old vs New Classes

| Old Class | New Class | Notes |
|-----------|-----------|-------|
| `bg-navy` | `bg-primary` | Warna berubah dari #4A70A9 ke #0C2B4E |
| `bg-blue-light` | `bg-secondary` | Warna berubah dari #8FABD4 ke #1A3D64 |
| `text-gray-700` | `text-muted` | Untuk secondary text |
| Manual button classes | `btn-primary` | Lebih konsisten |
| Manual input classes | `form-input` | Auto handle focus, error states |

### Quick Replace
Gunakan find & replace di editor:
1. `bg-navy` → `bg-primary`
2. `text-navy` → `text-primary`
3. `border-navy` → `border-primary`

---

## ✨ Best Practices

1. **Consistency**: Gunakan class global daripada inline styling
2. **Responsive**: Selalu pertimbangkan tampilan mobile-first
3. **Accessibility**: Gunakan label yang jelas dan error messages
4. **Loading States**: Tambahkan `disabled:` states untuk buttons
5. **Transitions**: Semua komponen sudah include smooth transitions

---

## 📚 Resources

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev)
- [Select2 Documentation](https://select2.org)

---

**Version:** 1.0.0
**Last Updated:** November 3, 2025
**Maintained by:** SPPD Development Team
