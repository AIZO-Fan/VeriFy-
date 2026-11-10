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
                        'content' => strip_tags($section->content),
                    ],
                )
                ->values()
                ->toArray()
            : [['title' => '', 'content' => '']];

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

            {{-- MATERI PEMBELAJARAN --}}
            <section class="va-editor-card" aria-labelledby="va-module-lessons-title">
                <header class="va-editor-card-heading va-editor-lessons-heading">
                    <div class="va-editor-heading-group">
                        <span class="va-editor-step" aria-hidden="true">
                            02
                        </span>

                        <div>
                            <h2 id="va-module-lessons-title">
                                Materi pembelajaran
                            </h2>

                            <p>
                                Perbarui isi materi atau tambahkan
                                bagian pembelajaran baru.
                            </p>
                        </div>
                    </div>

                    <button class="va-button va-editor-add" id="btnAddLesson" type="button"
                        aria-controls="lessonsContainer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" aria-hidden="true">
                            <path d="M12 5V19M5 12H19" />
                        </svg>

                        Tambah materi
                    </button>
                </header>

                @error('lessons')
                    <p class="va-field-error">
                        {{ $message }}
                    </p>
                @enderror

                <div id="lessonsContainer" class="va-lessons-container">
                    @foreach ($currentLessons as $idx => $lesson)
                        <div class="lesson-row va-lesson-row">

                            <div class="va-lesson-heading">
                                <span class="lesson-number">
                                    Materi {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <button class="btn-remove-lesson va-action-button va-action-button--delete" type="button"
                                    aria-label="Hapus materi {{ $idx + 1 }}">
                                    Hapus
                                </button>
                            </div>

                            <div class="va-editor-field">
                                <label data-lesson-title-label for="va-lesson-title-{{ $idx }}">
                                    Judul materi
                                    <span class="va-required" aria-hidden="true">*</span>
                                </label>

                                <input class="va-control va-editor-control" data-lesson-title
                                    id="va-lesson-title-{{ $idx }}" type="text"
                                    name="lessons[{{ $idx }}][title]" value="{{ $lesson['title'] ?? '' }}"
                                    placeholder="Masukkan judul materi" required
                                    @if ($errors->has('lessons.' . $idx . '.title')) aria-invalid="true"
                                        aria-describedby="va-lesson-title-error-{{ $idx }}" @endif>

                                @if ($errors->has('lessons.' . $idx . '.title'))
                                    <p class="va-field-error" data-lesson-title-error
                                        id="va-lesson-title-error-{{ $idx }}">
                                        {{ $errors->first('lessons.' . $idx . '.title') }}
                                    </p>
                                @endif
                            </div>

                            <div class="va-editor-field">
                                <label data-lesson-content-label for="va-lesson-content-{{ $idx }}">
                                    Isi materi
                                    <span class="va-required" aria-hidden="true">*</span>
                                </label>

                                <textarea class="va-control va-editor-control" data-lesson-content id="va-lesson-content-{{ $idx }}"
                                    name="lessons[{{ $idx }}][content]" rows="5"
                                    placeholder="Tuliskan penjelasan materi pembelajaran..." required
                                    @if ($errors->has('lessons.' . $idx . '.content')) aria-invalid="true"
                                        aria-describedby="va-lesson-content-error-{{ $idx }}" @endif>{{ $lesson['content'] ?? '' }}</textarea>

                                @if ($errors->has('lessons.' . $idx . '.content'))
                                    <p class="va-field-error" data-lesson-content-error
                                        id="va-lesson-content-error-{{ $idx }}">
                                        {{ $errors->first('lessons.' . $idx . '.content') }}
                                    </p>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

                <p id="vaLessonStatus" class="va-editor-status" role="status" aria-live="polite"></p>

                <p class="va-editor-hint">
                    Modul harus memiliki minimal satu bagian materi.
                    Perubahan materi diterapkan setelah tombol
                    Perbarui modul ditekan.
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

    {{-- TEMPLATE UNTUK MATERI TAMBAHAN --}}
    <template id="vaLessonTemplate">
        <div class="lesson-row va-lesson-row">

            <div class="va-lesson-heading">
                <span class="lesson-number"></span>

                <button class="btn-remove-lesson va-action-button va-action-button--delete" type="button">
                    Hapus
                </button>
            </div>

            <div class="va-editor-field">
                <label data-lesson-title-label>
                    Judul materi
                    <span class="va-required" aria-hidden="true">*</span>
                </label>

                <input class="va-control va-editor-control" data-lesson-title type="text"
                    placeholder="Masukkan judul materi" required>
            </div>

            <div class="va-editor-field">
                <label data-lesson-content-label>
                    Isi materi
                    <span class="va-required" aria-hidden="true">*</span>
                </label>

                <textarea class="va-control va-editor-control" data-lesson-content rows="5"
                    placeholder="Tuliskan penjelasan materi pembelajaran..." required></textarea>
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
                    const content = row.querySelector('[data-lesson-content]');

                    const titleLabel = row.querySelector('[data-lesson-title-label]');
                    const contentLabel = row.querySelector('[data-lesson-content-label]');

                    const titleError = row.querySelector('[data-lesson-title-error]');
                    const contentError = row.querySelector('[data-lesson-content-error]');

                    row.querySelector('.lesson-number').textContent =
                        'Materi ' + String(number).padStart(2, '0');

                    row.querySelector('.btn-remove-lesson').setAttribute(
                        'aria-label',
                        'Hapus materi ' + number
                    );

                    title.name = `lessons[${index}][title]`;
                    title.id = `va-lesson-title-${index}`;
                    titleLabel.htmlFor = title.id;

                    content.name = `lessons[${index}][content]`;
                    content.id = `va-lesson-content-${index}`;
                    contentLabel.htmlFor = content.id;

                    if (titleError) {
                        titleError.id = `va-lesson-title-error-${index}`;
                        title.setAttribute('aria-describedby', titleError.id);
                    }

                    if (contentError) {
                        contentError.id = `va-lesson-content-error-${index}`;
                        content.setAttribute('aria-describedby', contentError.id);
                    }
                });
            }

            addButton.addEventListener('click', function() {
                const fragment = template.content.cloneNode(true);
                const newRow = fragment.querySelector('.lesson-row');

                container.appendChild(fragment);
                updateNumbers();

                newRow.querySelector('[data-lesson-title]').focus();

                status.textContent = 'Bagian materi baru ditambahkan.';
            });

            container.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.btn-remove-lesson');

                if (!removeButton || !container.contains(removeButton)) {
                    return;
                }

                const rows = Array.from(container.querySelectorAll('.lesson-row'));

                if (rows.length <= 1) {
                    status.textContent =
                        'Modul harus memiliki minimal satu bagian materi.';
                    return;
                }

                const row = removeButton.closest('.lesson-row');
                const index = rows.indexOf(row);

                const hasContent =
                    row.querySelector('[data-lesson-title]').value.trim() !== '' ||
                    row.querySelector('[data-lesson-content]').value.trim() !== '';

                if (hasContent && !window.confirm(
                        'Hapus bagian materi ini? Perubahan akan diterapkan saat modul diperbarui.'
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

                status.textContent = 'Bagian materi dihapus dari form.';
            });

            updateNumbers();
        });
    </script>
@endsection
