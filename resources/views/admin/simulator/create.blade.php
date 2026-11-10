@extends('layouts.app')

@section('title', 'Buat Skenario Scam Simulator')

@section('content')
    @include('admin.partials.nav')

    @php
        $topicOptions = [
            1 => 'Topik 1: Intro to Digital Scam',
            2 => 'Topik 2: Social Engineering',
            3 => 'Topik 3: Phishing & Malicious Link',
            4 => 'Topik 4: Online Shopping & Investment',
            5 => 'Topik 5: Account & Data Protection',
            6 => 'Topik 6: Reporting & Prevention',
        ];

        $difficultyOptions = [
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced' => 'Tinggi',
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

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Judul Skenario</label>
                    <input type="text" id="scenarioTitle" name="title" value="{{ old('title', $selectedModule->title ?? '') }}" required placeholder="Contoh: Modus Undian Berhadiah Mengatasnamakan Bank"
                        style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-size: 1rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Topik Terkait</label>
                    <select name="topic_id" id="topicSelect" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                        @foreach($modules as $mod)
                        <option value="{{ $mod->id }}" {{ (old('topic_id', $selectedTopicId) == $mod->id) ? 'selected' : '' }} data-title="{{ $mod->title }}" data-desc="{{ $mod->description }}">
                            Topik {{ $mod->sort_order ?? $mod->id }}: {{ $mod->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Slug URL (Opsional)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Contoh: undian-palsu-bank (otomatis jika dikosongkan)"
                        style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Tingkat Kesulitan</label>
                    <select name="difficulty" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                        <option value="beginner" {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner (Pemula)</option>
                        <option value="intermediate" {{ old('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate (Menengah)</option>
                        <option value="advanced" {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced (Tinggi)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Deskripsi Situasi Awal</label>
                <textarea name="description" id="scenarioDesc" rows="2" required placeholder="Jelaskan konteks yang dihadapi pengguna sebelum interaksi dimulai..."
                    style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-size: 0.95rem;">{{ old('description', $selectedModule->description ?? '') }}</textarea>
            </div>

            <div style="margin-bottom: 24px; padding: 16px; background: var(--theme-card-soft); border-radius: 12px; border: 1px solid var(--theme-border);">
                <label style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--theme-heading); cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #20b4cb;">
                    Publikasikan langsung (Status Aktif)
                </label>
            </div>

            {{-- Story JSON Editor --}}
            <div style="margin-bottom: 28px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <div>
                        <h2 id="va-scenario-info-title">
                            Informasi skenario
                        </h2>

                        <p>
                            Tentukan judul, topik, dan konteks awal latihan.
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
                                value="{{ old('title') }}"
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
                                Topik terkait
                            </label>

                            <select
                                class="va-control va-editor-control"
                                id="va-scenario-topic"
                                name="topic_id"
                                @error('topic_id')
                                    aria-invalid="true"
                                    aria-describedby="va-scenario-topic-error"
                                @enderror
                            >
                                @foreach($topicOptions as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        {{ (string) old('topic_id', '1') === (string) $value ? 'selected' : '' }}
                                    >
                                        {{ $label }}
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
                                aria-describedby="va-scenario-slug-hint{{ $errors->has('slug') ? ' va-scenario-slug-error' : '' }}"
                                @error('slug')
                                    aria-invalid="true"
                                @enderror
                            >

                            <p
                                class="va-field-hint"
                                id="va-scenario-slug-hint"
                            >
                                Kosongkan untuk menggunakan slug otomatis.
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
                            </label>

                            <select
                                class="va-control va-editor-control"
                                id="va-scenario-difficulty"
                                name="difficulty"
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
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p
                                class="va-field-error"
                                id="va-scenario-description-error"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- STATUS --}}
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
                            @error('is_active')
                                aria-invalid="true"
                                aria-describedby="va-scenario-active-error"
                            @enderror
                        >

                        <span class="va-editor-publish-copy">
                            <span class="va-editor-publish-title">
                                Publikasikan setelah disimpan
                            </span>

                            <span class="va-editor-publish-description">
                                Centang untuk menyimpan skenario
                                dengan status Aktif.
                            </span>
                        </span>
                    </label>

                    @error('is_active')
                        <p
                            class="va-field-error"
                            id="va-scenario-active-error"
                        >
                            {{ $message }}
                        </p>
                    @enderror

                </div>
            </section>

            {{-- EDITOR JSON --}}
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
                                Alur percakapan
                            </h2>

                            <p>
                                Susun cerita menggunakan format
                                Story Graph JSON.
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

                        Rapikan JSON
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
                        aria-describedby="va-json-help jsonStatus{{ $errors->has('story_json') ? ' va-story-json-error' : '' }}"
                        required
                        @error('story_json')
                            aria-invalid="true"
                        @enderror
                    >{{ old('story_json', $defaultStoryJson) }}</textarea>
                </div>

                <p
                    id="jsonStatus"
                    class="va-json-status"
                    role="status"
                    aria-live="polite"
                >
                    Klik “Rapikan JSON” untuk memeriksa sintaks.
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
                    Pemeriksaan di browser hanya memeriksa sintaks JSON.
                    Struktur cerita dan aturan simulator tetap perlu
                    divalidasi oleh server saat disimpan.
                </p>
            </section>

            {{-- AKSI --}}
            <footer class="va-editor-actions">
                <p>
                    Periksa informasi dan alur cerita sebelum menyimpan.
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

                <textarea id="storyJsonInput" name="story_json" rows="18" required
                    style="width: 100%; font-family: Consolas, monospace; font-size: 0.88rem; line-height: 1.5; padding: 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: #0b1220; color: #38bdf8;">{{ old('story_json', $defaultStoryJson) }}</textarea>
                <div id="jsonStatus" style="margin-top: 6px; font-size: 0.85rem; color: var(--theme-muted);">Format JSON siap disimpan.</div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.simulator.index') }}" style="padding: 12px 20px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-weight: 600;">Batal</a>
                <button type="submit" style="padding: 12px 24px; border-radius: 10px; background: #20b4cb; color: #fff; border: none; font-weight: 700; cursor: pointer;">
                    Simpan Skenario
                </button>
            </div>
        </form>
    </section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('storyJsonInput');
        const btnFormat = document.getElementById('btnFormatJson');
        const status = document.getElementById('jsonStatus');
        const topicSelect = document.getElementById('topicSelect');
        const titleInput = document.getElementById('scenarioTitle');
        const descInput = document.getElementById('scenarioDesc');

        // Ubah judul dan deskripsi otomatis ketika pilihan topik dropdown diubah
        if (topicSelect) {
            topicSelect.addEventListener('change', function() {
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

        btnFormat.addEventListener('click', function() {
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
    });
</script>
@endsection