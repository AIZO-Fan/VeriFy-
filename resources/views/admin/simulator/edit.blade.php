@extends('layouts.app')

@section('title', 'Edit Skenario: ' . $scenario->title)

@section('content')
    @include('admin.partials.nav')

    @php
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
                    Edit skenario simulasi
                </h1>

                <p>
                    Perbarui skenario #{{ $scenario->id }}.
                    Versi saat ini: v{{ $scenario->version }}.
                </p>
            </div>
        </header>

        <form
            id="vaScenarioEditForm"
            class="va-module-editor"
            action="{{ route('admin.simulator.update', $scenario) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

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
                            Perbarui judul, topik, dan konteks awal latihan.
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
                                value="{{ old('title', $scenario->title) }}"
                                placeholder="Masukkan judul skenario"
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
                                @for($i = 1; $i <= 6; $i++)
                                    <option
                                        value="{{ $i }}"
                                        {{ old('topic_id', $scenario->topic_id) == $i ? 'selected' : '' }}
                                    >
                                        Topik {{ $i }}
                                    </option>
                                @endfor
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
                                <span class="va-required" aria-hidden="true">*</span>
                            </label>

                            <input
                                class="va-control va-editor-control"
                                id="va-scenario-slug"
                                type="text"
                                name="slug"
                                value="{{ old('slug', $scenario->slug) }}"
                                placeholder="Contoh: undian-palsu-bank"
                                required
                                @error('slug')
                                    aria-invalid="true"
                                    aria-describedby="va-scenario-slug-error"
                                @enderror
                            >

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
                                        {{ old('difficulty', $scenario->difficulty) === $value ? 'selected' : '' }}
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
                            placeholder="Jelaskan konteks yang dihadapi pengguna..."
                            required
                            @error('description')
                                aria-invalid="true"
                                aria-describedby="va-scenario-description-error"
                            @enderror
                        >{{ old('description', $scenario->description) }}</textarea>

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
                            {{ old('is_active', $scenario->is_active) ? 'checked' : '' }}
                            @error('is_active')
                                aria-invalid="true"
                                aria-describedby="va-scenario-active-error"
                            @enderror
                        >

                        <span class="va-editor-publish-copy">
                            <span class="va-editor-publish-title">
                                Aktifkan skenario
                            </span>

                            <span class="va-editor-publish-description">
                                Tentukan status aktif skenario
                                setelah perubahan disimpan.
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
                                Perbarui percakapan dan pilihan
                                menggunakan format Story Graph JSON.
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

                <p class="va-editor-hint">
                    Versi saat ini: v{{ $scenario->version }}.
                    Sesuai keterangan form sebelumnya, pembaruan
                    menaikkan versi menjadi v{{ $scenario->version + 1 }}.
                </p>

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
                    >{{ old('story_json', $storyJson) }}</textarea>
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
                    Periksa perubahan informasi dan alur cerita
                    sebelum memperbarui skenario.
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
                        Perbarui skenario
                    </button>
                </div>
            </footer>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('storyJsonInput');
            const formatButton = document.getElementById('btnFormatJson');
            const status = document.getElementById('jsonStatus');

            if (!textarea || !formatButton || !status) {
                return;
            }

            formatButton.addEventListener('click', function () {
                status.classList.remove('is-success', 'is-error');

                try {
                    const parsed = JSON.parse(textarea.value);

                    textarea.value = JSON.stringify(parsed, null, 2);

                    status.textContent =
                        'Sintaks JSON valid dan berhasil dirapikan. ' +
                        'Struktur cerita akan diperiksa saat disimpan.';

                    status.classList.add('is-success');
                } catch (error) {
                    status.textContent =
                        'Sintaks JSON belum valid: ' + error.message;

                    status.classList.add('is-error');
                    textarea.focus();
                }
            });

            textarea.addEventListener('input', function () {
                status.classList.remove('is-success', 'is-error');

                status.textContent =
                    'Isi JSON berubah. Klik “Rapikan JSON” untuk memeriksa ulang.';
            });
        });
    </script>
@endsection