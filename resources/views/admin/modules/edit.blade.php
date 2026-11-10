@extends('layouts.app')

@section('title', 'Edit Modul: ' . $module->title)

@section('content')
    @include('admin.partials.nav')

    @php
        $colorOptions = [
            'blue' => 'Biru',
            'purple' => 'Ungu',
            'green' => 'Hijau',
            'orange' => 'Jingga',
            'cyan' => 'Toska',
            'pink' => 'Merah muda',
        ];

        $iconOptions = [
            'shield' => 'Perisai',
            'brain' => 'Psikologi',
            'link' => 'Tautan',
            'cart' => 'Belanja',
            'lock' => 'Privasi',
            'report' => 'Laporan',
        ];

        $fallbackLessons = $module->sections->isNotEmpty()
            ? $module->sections
                ->map(
                    fn($section) => [
                        'title' => $section->title,
                        'type' => $section->type ?? 'text',
                        'content' => strip_tags($section->content),
                    ],
                )
                ->values()
                ->toArray()
            : [['title' => '', 'type' => 'text', 'content' => '']];

        $currentLessons = array_values(old('lessons', $fallbackLessons));

        if (count($currentLessons) === 0) {
            $currentLessons = [['title' => '', 'content' => '']];
        }
    @endphp

    <section class="va-shell va-module-editor-page" aria-labelledby="va-module-editor-title">
        {{-- KEMBALI --}}
        <a class="va-editor-back" href="{{ route('admin.modules.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <path d="M19 12H5M11 6L5 12L11 18" />
            </svg>

            Daftar modul
        </a>

        {{-- HEADER --}}
        <header class="va-management-heading">
            <div class="va-management-heading-copy">
                <span class="va-eyebrow">
                    MODUL PEMBELAJARAN
                </span>

                <h1 id="va-module-editor-title">
                    Edit modul pembelajaran
                </h1>

                <p>
                    Perbarui informasi dan isi materi
                    untuk modul #{{ $module->id }}.
                </p>
            </div>
        </header>

        <form id="vaModuleEditForm" class="va-module-editor" action="{{ route('admin.modules.update', $module) }}"
            method="POST">
            @csrf
            @method('PUT')

            {{-- INFORMASI MODUL --}}
            <section class="va-editor-card" aria-labelledby="va-module-info-title">
                <header class="va-editor-card-heading">
                    <span class="va-editor-step" aria-hidden="true">
                        01
                    </span>

                    <div>
                        <h2 id="va-module-info-title">
                            Informasi modul
                        </h2>

                        <p>
                            Perbarui judul, deskripsi,
                            dan pengaturan tampilan modul.
                        </p>
                    </div>
                </header>

                <div class="va-editor-fields">

                    {{-- JUDUL --}}
                    <div class="va-editor-field">
                        <label for="va-module-title">
                            Judul modul
                            <span class="va-required" aria-hidden="true">*</span>
                        </label>

                        <input class="va-control va-editor-control" id="va-module-title" type="text" name="title"
                            value="{{ old('title', $module->title) }}" placeholder="Masukkan judul modul" required
                            @error('title')
                                aria-invalid="true"
                                aria-describedby="va-module-title-error"
                            @enderror>

                        @error('title')
                            <p class="va-field-error" id="va-module-title-error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="va-editor-field">
                        <label for="va-module-description">
                            Deskripsi singkat
                            <span class="va-required" aria-hidden="true">*</span>
                        </label>

                        <textarea class="va-control va-editor-control" id="va-module-description" name="description" rows="4"
                            placeholder="Jelaskan secara ringkas materi yang akan dipelajari." required
                            @error('description')
                                aria-invalid="true"
                                aria-describedby="va-module-description-error"
                            @enderror>{{ old('description', $module->description) }}</textarea>

                        @error('description')
                            <p class="va-field-error" id="va-module-description-error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- WARNA, IKON, DAN URUTAN --}}
                    <div class="va-editor-grid">
                        <div class="va-editor-field">
                            <label for="va-module-color">
                                Tema warna
                            </label>

                            <select class="va-control va-editor-control" id="va-module-color" name="color"
                                @error('color')
                                    aria-invalid="true"
                                    aria-describedby="va-module-color-error"
                                @enderror>
                                @foreach ($colorOptions as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('color', $module->color ?? 'blue') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            @error('color')
                                <p class="va-field-error" id="va-module-color-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="va-editor-field">
                            <label for="va-module-icon">
                                Ikon modul
                            </label>

                            <select class="va-control va-editor-control" id="va-module-icon" name="icon"
                                @error('icon')
                                    aria-invalid="true"
                                    aria-describedby="va-module-icon-error"
                                @enderror>
                                @foreach ($iconOptions as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('icon', $module->icon ?? 'shield') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            @error('icon')
                                <p class="va-field-error" id="va-module-icon-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="va-editor-field">
                            <label for="va-module-order">
                                Nomor urutan
                            </label>

                            <input class="va-control va-editor-control" id="va-module-order" type="number" name="order"
                                value="{{ old('order', $module->sort_order) }}" min="1"
                                @error('order')
                                    aria-invalid="true"
                                    aria-describedby="va-module-order-error"
                                @enderror>

                            @error('order')
                                <p class="va-field-error" id="va-module-order-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <label class="va-editor-publish" for="va-module-active">
                        <input id="va-module-active" type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $module->is_active) ? 'checked' : '' }}
                            @error('is_active')
                                aria-invalid="true"
                                aria-describedby="va-module-active-error"
                            @enderror>

                        <span class="va-editor-publish-copy">
                            <span class="va-editor-publish-title">
                                Aktifkan modul
                            </span>

                            <span class="va-editor-publish-description">
                                Tentukan apakah modul ditampilkan
                                kepada pengguna setelah perubahan disimpan.
                            </span>
                        </span>
                    </label>

                    @error('is_active')
                        <p class="va-field-error" id="va-module-active-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>
            </section>

            {{-- MATERI / SLIDE --}}
            <section class="va-editor-card" aria-labelledby="va-module-lessons-title">
                <header class="va-editor-card-heading va-editor-lessons-heading">
                    <div class="va-editor-heading-group">
                        <span class="va-editor-step" aria-hidden="true">
                            02
                        </span>

                        <div>
                            <h2 id="va-module-lessons-title">
                                Slide Materi Pembelajaran
                            </h2>

                            <p>
                                Susun materi slide demi slide. Setiap kartu di bawah akan menjadi <strong>1 halaman geser (slide)</strong> yang dibaca pengguna secara bertahap.
                            </p>
                        </div>
                    </div>

                    <button class="va-button va-editor-add" id="btnAddLesson" type="button"
                        aria-controls="lessonsContainer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" aria-hidden="true">
                            <path d="M12 5V19M5 12H19" />
                        </svg>

                        Tambah Slide Materi
                    </button>
                </header>

                @error('lessons')
                    <p class="va-field-error">
                        {{ $message }}
                    </p>
                @enderror

                <div id="lessonsContainer" class="va-lessons-container">
                    @foreach ($currentLessons as $idx => $lesson)
                        <div class="lesson-row va-lesson-row" style="background: var(--theme-card-soft); border: 1px solid var(--theme-border); border-radius: 14px; padding: 20px; margin-bottom: 18px;">
                            <div class="va-lesson-heading" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--theme-border);">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span class="lesson-number" style="font-weight: 800; font-size: 1.05rem; color: var(--theme-heading);">
                                        Slide {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="lesson-badge" style="font-size: 0.75rem; background: rgba(32, 180, 203, 0.12); color: #20b4cb; padding: 2px 8px; border-radius: 6px; font-weight: 600;">
                                        Halaman Geser #{{ $idx + 1 }}
                                    </span>
                                </div>

                                <button class="btn-remove-lesson va-action-button va-action-button--delete" type="button"
                                    aria-label="Hapus slide {{ $idx + 1 }}">
                                    Hapus Slide
                                </button>
                            </div>

                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 14px;">
                                <div class="va-editor-field">
                                    <label data-lesson-title-label for="va-lesson-title-{{ $idx }}">
                                        Judul slide
                                        <span class="va-required" aria-hidden="true">*</span>
                                    </label>

                                    <input class="va-control va-editor-control" data-lesson-title
                                        id="va-lesson-title-{{ $idx }}" type="text"
                                        name="lessons[{{ $idx }}][title]" value="{{ $lesson['title'] ?? '' }}"
                                        placeholder="Masukkan judul materi slide" required>
                                </div>

                                <div class="va-editor-field">
                                    <label for="va-lesson-type-{{ $idx }}">
                                        Tipe slide
                                    </label>

                                    <select
                                        class="va-control va-editor-control"
                                        data-lesson-type
                                        id="va-lesson-type-{{ $idx }}"
                                        name="lessons[{{ $idx }}][type]"
                                    >
                                        <option value="text" {{ ($lesson['type'] ?? 'text') === 'text' ? 'selected' : '' }}>Teks Pembahasan</option>
                                        <option value="example" {{ ($lesson['type'] ?? '') === 'example' ? 'selected' : '' }}>💡 Contoh Kasus / Modus</option>
                                        <option value="summary" {{ ($lesson['type'] ?? '') === 'summary' ? 'selected' : '' }}>📌 Ringkasan &amp; Poin Kunci</option>
                                    </select>
                                </div>
                            </div>

                            <div class="va-editor-field">
                                <label data-lesson-content-label for="va-lesson-content-{{ $idx }}">
                                    Isi materi slide
                                    <span class="va-required" aria-hidden="true">*</span>
                                </label>

                                <textarea class="va-control va-editor-control" data-lesson-content id="va-lesson-content-{{ $idx }}"
                                    name="lessons[{{ $idx }}][content]" rows="4"
                                    placeholder="Tuliskan penjelasan materi pembelajaran pada slide ini..." required>{{ $lesson['content'] ?? '' }}</textarea>
                            </div>

                        </div>
                    @endforeach
                </div>

                <p id="vaLessonStatus" class="va-editor-status" role="status" aria-live="polite"></p>

                <p class="va-editor-hint">
                    Setiap kartu slide di atas akan menjadi 1 halaman pada modul pembaca. Pengguna dapat berpindah antar-slide menggunakan tombol Selanjutnya &amp; Sebelumnya.
                </p>
            </section>

            {{-- AKSI --}}
            <footer class="va-editor-actions">
                <p>
                    Periksa kembali perubahan sebelum memperbarui modul.
                </p>

                <div class="va-editor-action-buttons">
                    <a class="va-button" href="{{ route('admin.modules.index') }}">
                        Batal
                    </a>

                    <button class="va-button va-button--primary" type="submit">
                        Perbarui modul
                    </button>
                </div>
            </footer>
        </form>
    </section>

    {{-- TEMPLATE UNTUK SLIDE MATERI BARU --}}
    <template id="vaLessonTemplate">
        <div class="lesson-row va-lesson-row" style="background: var(--theme-card-soft); border: 1px solid var(--theme-border); border-radius: 14px; padding: 20px; margin-bottom: 18px;">
            <div class="va-lesson-heading" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid var(--theme-border);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="lesson-number" style="font-weight: 800; font-size: 1.05rem; color: var(--theme-heading);"></span>
                    <span class="lesson-badge" style="font-size: 0.75rem; background: rgba(32, 180, 203, 0.12); color: #20b4cb; padding: 2px 8px; border-radius: 6px; font-weight: 600;">
                        Halaman Geser
                    </span>
                </div>

                <button class="btn-remove-lesson va-action-button va-action-button--delete" type="button">
                    Hapus Slide
                </button>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 14px;">
                <div class="va-editor-field">
                    <label data-lesson-title-label>
                        Judul slide
                        <span class="va-required" aria-hidden="true">*</span>
                    </label>

                    <input class="va-control va-editor-control" data-lesson-title type="text"
                        placeholder="Masukkan judul materi slide" required>
                </div>

                <div class="va-editor-field">
                    <label data-lesson-type-label>
                        Tipe slide
                    </label>

                    <select
                        class="va-control va-editor-control"
                        data-lesson-type
                    >
                        <option value="text">Teks Pembahasan</option>
                        <option value="example">💡 Contoh Kasus / Modus</option>
                        <option value="summary">📌 Ringkasan &amp; Poin Kunci</option>
                    </select>
                </div>
            </div>

            <div class="va-editor-field">
                <label data-lesson-content-label>
                    Isi materi slide
                    <span class="va-required" aria-hidden="true">*</span>
                </label>

                <textarea class="va-control va-editor-control" data-lesson-content rows="4"
                    placeholder="Tuliskan penjelasan materi pembelajaran pada slide ini..." required></textarea>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('lessonsContainer');
            const addButton = document.getElementById('btnAddLesson');
            const template = document.getElementById('vaLessonTemplate');
            const status = document.getElementById('vaLessonStatus');

            if (!container || !addButton || !template || !status) {
                return;
            }

            function updateNumbers() {
                container.querySelectorAll('.lesson-row').forEach((row, index) => {
                    const number = index + 1;

                    const title = row.querySelector('[data-lesson-title]');
                    const type = row.querySelector('[data-lesson-type]');
                    const content = row.querySelector('[data-lesson-content]');

                    const titleLabel = row.querySelector('[data-lesson-title-label]');
                    const typeLabel = row.querySelector('[data-lesson-type-label]');
                    const contentLabel = row.querySelector('[data-lesson-content-label]');
                    const lessonBadge = row.querySelector('.lesson-badge');

                    row.querySelector('.lesson-number').textContent =
                        'Slide ' + String(number).padStart(2, '0');

                    if (lessonBadge) {
                        lessonBadge.textContent = 'Halaman Geser #' + number;
                    }

                    row.querySelector('.btn-remove-lesson').setAttribute(
                        'aria-label',
                        'Hapus slide ' + number
                    );

                    title.name = `lessons[${index}][title]`;
                    title.id = `va-lesson-title-${index}`;
                    if (titleLabel) titleLabel.htmlFor = title.id;

                    if (type) {
                        type.name = `lessons[${index}][type]`;
                        type.id = `va-lesson-type-${index}`;
                        if (typeLabel) typeLabel.htmlFor = type.id;
                    }

                    content.name = `lessons[${index}][content]`;
                    content.id = `va-lesson-content-${index}`;
                    if (contentLabel) contentLabel.htmlFor = content.id;
                });
            }

            addButton.addEventListener('click', function() {
                const fragment = template.content.cloneNode(true);
                const newRow = fragment.querySelector('.lesson-row');

                container.appendChild(fragment);
                updateNumbers();

                newRow.querySelector('[data-lesson-title]').focus();

                status.textContent = 'Slide materi baru ditambahkan.';
            });

            container.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.btn-remove-lesson');

                if (!removeButton || !container.contains(removeButton)) {
                    return;
                }

                const rows = Array.from(container.querySelectorAll('.lesson-row'));

                if (rows.length <= 1) {
                    alert('Modul harus memiliki minimal satu slide materi.');
                    return;
                }

                const row = removeButton.closest('.lesson-row');
                const index = rows.indexOf(row);

                const hasContent =
                    row.querySelector('[data-lesson-title]').value.trim() !== '' ||
                    row.querySelector('[data-lesson-content]').value.trim() !== '';

                if (hasContent && !window.confirm(
                        'Hapus slide materi ini? Perubahan akan diterapkan saat modul diperbarui.'
                    )) {
                    return;
                }

                row.remove();
                updateNumbers();

                const remainingRows = container.querySelectorAll('.lesson-row');
                const nextRow = remainingRows[
                    Math.min(index, remainingRows.length - 1)
                ];

                nextRow.querySelector('[data-lesson-title]').focus();

                status.textContent = 'Slide materi telah dihapus.';
            });

            updateNumbers();
        });
    </script>
@endsection
