@extends('layouts.app')

@section('title', 'Edit Skenario: ' . $scenario->title)

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 960px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.simulator.index') }}" style="color: #20b4cb; font-size: 0.9rem; font-weight: 700; text-decoration: none;">&larr; Kembali ke Daftar Simulator</a>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 8px 0 4px;">Edit Skenario Simulasi</h1>
        <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Perbarui skenario #{{ $scenario->id }} &bull; Versi saat ini: v{{ $scenario->version }}</p>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px var(--theme-shadow);">
        <form action="{{ route('admin.simulator.update', $scenario) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Judul Skenario</label>
                    <input type="text" name="title" value="{{ old('title', $scenario->title) }}" required
                           style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-size: 1rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Topik Terkait</label>
                    <select name="topic_id" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('topic_id', $scenario->topic_id) == $i ? 'selected' : '' }}>Topik {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Slug URL</label>
                    <input type="text" name="slug" value="{{ old('slug', $scenario->slug) }}" required
                           style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Tingkat Kesulitan</label>
                    <select name="difficulty" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                        @foreach(['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'] as $key => $lbl)
                            <option value="{{ $key }}" {{ old('difficulty', $scenario->difficulty) == $key ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Deskripsi Situasi Awal</label>
                <textarea name="description" rows="2" required
                          style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-size: 0.95rem;">{{ old('description', $scenario->description) }}</textarea>
            </div>

            <div style="margin-bottom: 24px; padding: 16px; background: var(--theme-card-soft); border-radius: 12px; border: 1px solid var(--theme-border);">
                <label style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--theme-heading); cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $scenario->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #20b4cb;">
                    Status Aktif (Tampil dalam daftar simulator)
                </label>
            </div>

            {{-- Story JSON Editor --}}
            <div style="margin-bottom: 28px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: var(--theme-heading); margin: 0;">Alur Percakapan Skenario (Story Graph JSON)</label>
                        <span style="font-size: 0.85rem; color: var(--theme-muted);">Memperbarui skenario akan menaikkan versi menjadi v{{ $scenario->version + 1 }}.</span>
                    </div>
                    <button type="button" id="btnFormatJson" style="background: var(--theme-card-soft); border: 1px solid var(--theme-border); color: #20b4cb; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer;">
                        Format JSON Rapi
                    </button>
                </div>

                <textarea id="storyJsonInput" name="story_json" rows="18" required
                          style="width: 100%; font-family: Consolas, monospace; font-size: 0.88rem; line-height: 1.5; padding: 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: #0b1220; color: #38bdf8;">{{ old('story_json', $storyJson) }}</textarea>
                <div id="jsonStatus" style="margin-top: 6px; font-size: 0.85rem; color: var(--theme-muted);">JSON siap divalidasi.</div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.simulator.index') }}" style="padding: 12px 20px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-weight: 600;">Batal</a>
                <button type="submit" style="padding: 12px 24px; border-radius: 10px; background: #20b4cb; color: #fff; border: none; font-weight: 700; cursor: pointer;">
                    Perbarui Skenario
                </button>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('storyJsonInput');
    const btnFormat = document.getElementById('btnFormatJson');
    const status = document.getElementById('jsonStatus');

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
});
</script>
@endsection

