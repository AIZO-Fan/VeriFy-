@extends('layouts.app')

@section('title', 'Buat Skenario Scam Simulator')

@section('content')
    @include('admin.partials.nav')

    @php
        $difficultyOptions = [
            'beginner' => 'Pemula (Beginner)',
            'intermediate' => 'Menengah (Intermediate)',
            'advanced' => 'Tinggi (Advanced)',
        ];
    @endphp

    <section
        class="va-shell va-module-editor-page va-scenario-editor-page"
        aria-labelledby="va-scenario-editor-title"
    >
        {{-- KEMBALI --}}
        <a
            class="va-editor-back"
            href="{{ route('admin.simulator.index') }}"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M19 12H5M11 6L5 12L11 18" />
            </svg>

            Daftar simulator
        </a>

        {{-- HEADER --}}
        <header class="va-management-heading">
            <div class="va-management-heading-copy">
                <span class="va-eyebrow">
                    SCAM SIMULATOR
                </span>

                <h1 id="va-scenario-editor-title">
                    Buat skenario baru
                </h1>

                <p>
                    Rancang percakapan interaktif untuk membantu pengguna
                    mengenali pola penipuan dan memahami dampak pilihannya.
                </p>
            </div>
        </header>

        <form
            id="vaScenarioCreateForm"
            class="va-module-editor"
            action="{{ route('admin.simulator.store') }}"
            method="POST"
        >
            @csrf

            {{-- INFORMASI SKENARIO --}}
            <section
                class="va-editor-card"
                aria-labelledby="va-scenario-info-title"
            >
                <header class="va-editor-card-heading">
                    <span class="va-editor-step" aria-hidden="true">
                        01
                    </span>

                    <div>
                        <h2 id="va-scenario-info-title">
                            Informasi skenario
                        </h2>

                        <p>
                            Tentukan judul, topik terkait, dan konteks awal latihan simulasi.
                        </p>
                    </div>
                </header>

                <div class="va-editor-fields">

                    <div class="va-scenario-grid va-scenario-grid--title">
                        {{-- JUDUL --}}
                        <div class="va-editor-field">
                            <label for="va-scenario-title">
                                Judul skenario
                                <span class="va-required" aria-hidden="true">*</span>
                            </label>

                            <input
                                class="va-control va-editor-control"
                                id="va-scenario-title"
                                type="text"
                                name="title"
                                value="{{ old('title', $selectedModule->title ?? '') }}"
                                placeholder="Contoh: Modus Undian Berhadiah Mengatasnamakan Bank"
                                required
                                @error('title')
                                    aria-invalid="true"
                                    aria-describedby="va-scenario-title-error"
                                @enderror
                            >

                            @error('title')
                                <p
                                    class="va-field-error"
                                    id="va-scenario-title-error"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- TOPIK --}}
                        <div class="va-editor-field">
                            <label for="va-scenario-topic">
                                Topik terkait (Modul)
                                <span class="va-required" aria-hidden="true">*</span>
                            </label>

                            <select
                                class="va-control va-editor-control"
                                id="va-scenario-topic"
                                name="topic_id"
                                required
                                @error('topic_id')
                                    aria-invalid="true"
                                    aria-describedby="va-scenario-topic-error"
                                @enderror
                            >
                                @foreach($modules as $mod)
                                    <option
                                        value="{{ $mod->id }}"
                                        data-title="{{ $mod->title }}"
                                        data-desc="{{ $mod->description }}"
                                        {{ (string) old('topic_id', $selectedTopicId ?? '1') === (string) $mod->id ? 'selected' : '' }}
                                    >
                                        Topik {{ $mod->sort_order ?? $mod->id }}: {{ $mod->title }}
                                    </option>
                                @endforeach
                            </select>

                            @error('topic_id')
                                <p
                                    class="va-field-error"
                                    id="va-scenario-topic-error"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="va-scenario-grid">
                        {{-- SLUG --}}
                        <div class="va-editor-field">
                            <label for="va-scenario-slug">
                                Slug URL
                                <span class="va-field-optional">
                                    Opsional
                                </span>
                            </label>

                            <input
                                class="va-control va-editor-control"
                                id="va-scenario-slug"
                                type="text"
                                name="slug"
                                value="{{ old('slug') }}"
                                placeholder="Contoh: undian-palsu-bank"
                                aria-describedby="va-scenario-slug-hint"
                                @error('slug')
                                    aria-invalid="true"
                                @enderror
                            >

                            <p
                                class="va-field-hint"
                                id="va-scenario-slug-hint"
                            >
                                Kosongkan untuk menggunakan slug otomatis berdasarkan judul.
                            </p>

                            @error('slug')
                                <p
                                    class="va-field-error"
                                    id="va-scenario-slug-error"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- KESULITAN --}}
                        <div class="va-editor-field">
                            <label for="va-scenario-difficulty">
                                Tingkat kesulitan
                                <span class="va-required" aria-hidden="true">*</span>
                            </label>

                            <select
                                class="va-control va-editor-control"
                                id="va-scenario-difficulty"
                                name="difficulty"
                                required
                                @error('difficulty')
                                    aria-invalid="true"
                                    aria-describedby="va-scenario-difficulty-error"
                                @enderror
                            >
                                @foreach($difficultyOptions as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        {{ old('difficulty', 'beginner') === $value ? 'selected' : '' }}
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            @error('difficulty')
                                <p
                                    class="va-field-error"
                                    id="va-scenario-difficulty-error"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="va-editor-field">
                        <label for="va-scenario-description">
                            Deskripsi situasi awal
                            <span class="va-required" aria-hidden="true">*</span>
                        </label>

                        <textarea
                            class="va-control va-editor-control"
                            id="va-scenario-description"
                            name="description"
                            rows="4"
                            placeholder="Jelaskan konteks yang dihadapi pengguna sebelum interaksi dimulai..."
                            required
                            @error('description')
                                aria-invalid="true"
                                aria-describedby="va-scenario-description-error"
                            @enderror
                        >{{ old('description', $selectedModule->description ?? '') }}</textarea>

                        @error('description')
                            <p
                                class="va-field-error"
                                id="va-scenario-description-error"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- STATUS PUBLIKASI --}}
                    <label
                        class="va-editor-publish"
                        for="va-scenario-active"
                    >
                        <input
                            id="va-scenario-active"
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', '1') ? 'checked' : '' }}
                        >

                        <span class="va-editor-publish-copy">
                            <span class="va-editor-publish-title">
                                Publikasikan langsung (Status Aktif)
                            </span>

                            <span class="va-editor-publish-description">
                                Centang untuk mengaktifkan skenario di Scam Simulator.
                            </span>
                        </span>
                    </label>

                </div>
            </section>

            {{-- EDITOR JSON ALUR CERITA --}}
            <section
                class="va-editor-card"
                aria-labelledby="va-scenario-story-title"
            >
                <header class="va-editor-card-heading va-editor-lessons-heading">
                    <div class="va-editor-heading-group">
                        <span class="va-editor-step" aria-hidden="true">
                            02
                        </span>

                        <div>
                            <h2 id="va-scenario-story-title">
                                Alur percakapan (Story Graph JSON)
                            </h2>

                            <p>
                                Susun cerita interaktif menggunakan format Story Graph JSON.
                            </p>
                        </div>
                    </div>

                    <button
                        class="va-button va-editor-add"
                        id="btnFormatJson"
                        type="button"
                        aria-controls="storyJsonInput"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M8 4H6A2 2 0 0 0 4 6V9L2 12L4 15V18A2 2 0 0 0 6 20H8" />
                            <path d="M16 4H18A2 2 0 0 1 20 6V9L22 12L20 15V18A2 2 0 0 1 18 20H16" />
                        </svg>

                        Rapikan &amp; Validasi JSON
                    </button>
                </header>

                <div class="va-json-editor-shell">
                    <div class="va-json-editor-bar">
                        <label for="storyJsonInput">
                            story.json
                        </label>

                        <span>Story Graph</span>
                    </div>

                    <textarea
                        class="va-control va-editor-control va-json-input"
                        id="storyJsonInput"
                        name="story_json"
                        rows="18"
                        spellcheck="false"
                        autocapitalize="off"
                        autocomplete="off"
                        required
                        style="width: 100%; font-family: Consolas, 'Fira Code', monospace; font-size: 0.9rem; line-height: 1.5; padding: 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: #0b1220; color: #38bdf8;"
                    >{{ old('story_json', $defaultStoryJson) }}</textarea>
                </div>

                <p
                    id="jsonStatus"
                    class="va-json-status"
                    role="status"
                    aria-live="polite"
                    style="margin-top: 8px; font-size: 0.88rem; font-weight: 600;"
                >
                    Klik “Rapikan &amp; Validasi JSON” untuk memeriksa sintaks JSON.
                </p>

                @error('story_json')
                    <p
                        class="va-field-error"
                        id="va-story-json-error"
                    >
                        {{ $message }}
                    </p>
                @enderror

                <p class="va-editor-hint" id="va-json-help">
                    Pemeriksaan di browser memastikan sintaks JSON valid.
                    Struktur node percakapan dan opsi keputusan akan divalidasi oleh sistem saat disimpan.
                </p>
            </section>

            {{-- AKSI FORM --}}
            <footer class="va-editor-actions">
                <p>
                    Periksa informasi dan alur cerita sebelum menyimpan skenario.
                </p>

                <div class="va-editor-action-buttons">
                    <a
                        class="va-button"
                        href="{{ route('admin.simulator.index') }}"
                    >
                        Batal
                    </a>

                    <button
                        class="va-button va-button--primary"
                        type="submit"
                    >
                        Simpan skenario
                    </button>
                </div>
            </footer>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('storyJsonInput');
            const btnFormat = document.getElementById('btnFormatJson');
            const status = document.getElementById('jsonStatus');
            const topicSelect = document.getElementById('va-scenario-topic');
            const titleInput = document.getElementById('va-scenario-title');
            const descInput = document.getElementById('va-scenario-description');

            // Otomatis sinkronkan judul dan deskripsi saat topik dropdown diubah (jika belum diisi manual)
            if (topicSelect) {
                topicSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const modTitle = selectedOption.getAttribute('data-title');
                    const modDesc = selectedOption.getAttribute('data-desc');

                    if (modTitle && !titleInput.value) {
                        titleInput.value = modTitle;
                    }
                    if (modDesc && !descInput.value) {
                        descInput.value = modDesc;
                    }
                });
            }

            // Tombol Format & Validasi JSON
            if (btnFormat && textarea && status) {
                btnFormat.addEventListener('click', function () {
                    try {
                        const parsed = JSON.parse(textarea.value);
                        textarea.value = JSON.stringify(parsed, null, 2);
                        status.textContent = '✅ Format JSON valid dan berhasil dirapikan.';
                        status.style.color = '#16a34a';
                    } catch (e) {
                        status.textContent = '❌ Kesalahan sintaks JSON: ' + e.message;
                        status.style.color = '#dc2626';
                    }
                });
            }
        });
    </script>
@endsection
