@extends('layouts.app')

@section('title', 'Buat Skenario Scam Simulator')

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 960px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.simulator.index') }}" style="color: #20b4cb; font-size: 0.9rem; font-weight: 700; text-decoration: none;">&larr; Kembali ke Daftar Simulator</a>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 8px 0 4px;">Buat Skenario Simulasi Baru</h1>
        <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Rancang percakapan interaktif untuk melatih kepekaan pengguna terhadap pola penipuan.</p>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px var(--theme-shadow);">
        <form action="{{ route('admin.simulator.store') }}" method="POST">
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
                        <label style="display: block; font-weight: 700; color: var(--theme-heading); margin: 0;">Alur Percakapan Skenario (Story Graph JSON)</label>
                        <span style="font-size: 0.85rem; color: var(--theme-muted);">Format divalidasi otomatis sesuai standar engine VeriFy Simulator.</span>
                    </div>
                    <button type="button" id="btnFormatJson" style="background: var(--theme-card-soft); border: 1px solid var(--theme-border); color: #20b4cb; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer;">
                        Format JSON Rapi
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
    </div>
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