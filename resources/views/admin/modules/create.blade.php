@extends('layouts.app')

@section('title', 'Tambah Modul Pembelajaran')

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 900px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.modules.index') }}" style="color: #20b4cb; font-size: 0.9rem; font-weight: 700; text-decoration: none;">&larr; Kembali ke Daftar Modul</a>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 8px 0 4px;">Buat Modul Pembelajaran Baru</h1>
        <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Modul baru akan otomatis tampil pada ruang belajar pengguna.</p>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px var(--theme-shadow);">
        <form action="{{ route('admin.modules.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Judul Modul</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Mengenali Penipuan Pinjaman Online Ilegal"
                       style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-size: 1rem;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Deskripsi Singkat</label>
                <textarea name="description" rows="3" required placeholder="Jelaskan secara ringkas materi apa yang akan dipelajari di modul ini..."
                          style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-size: 0.95rem;">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Tema Warna</label>
                    <select name="color" style="width: 100%; padding: 11px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                        <option value="blue" {{ old('color') == 'blue' ? 'selected' : '' }}>Blue (Biru)</option>
                        <option value="purple" {{ old('color') == 'purple' ? 'selected' : '' }}>Purple (Ungu)</option>
                        <option value="green" {{ old('color') == 'green' ? 'selected' : '' }}>Green (Hijau)</option>
                        <option value="orange" {{ old('color') == 'orange' ? 'selected' : '' }}>Orange (Jingga)</option>
                        <option value="cyan" {{ old('color') == 'cyan' ? 'selected' : '' }}>Cyan (Toska)</option>
                        <option value="pink" {{ old('color') == 'pink' ? 'selected' : '' }}>Pink (Merah Muda)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Ikon Modul</label>
                    <select name="icon" style="width: 100%; padding: 11px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                        <option value="shield" {{ old('icon') == 'shield' ? 'selected' : '' }}>shield (Perisai)</option>
                        <option value="brain" {{ old('icon') == 'brain' ? 'selected' : '' }}>brain (Otak/Psikologi)</option>
                        <option value="link" {{ old('icon') == 'link' ? 'selected' : '' }}>link (Tautan/URL)</option>
                        <option value="cart" {{ old('icon') == 'cart' ? 'selected' : '' }}>cart (Belanja/Toko)</option>
                        <option value="lock" {{ old('icon') == 'lock' ? 'selected' : '' }}>lock (Gembok/Privasi)</option>
                        <option value="report" {{ old('icon') == 'report' ? 'selected' : '' }}>report (Laporan)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; color: var(--theme-heading); margin-bottom: 8px;">Nomor Urutan</label>
                    <input type="number" name="order" value="{{ old('order', 7) }}" min="1"
                           style="width: 100%; padding: 11px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">
                </div>
            </div>

            <div style="margin-bottom: 24px; padding: 16px; background: var(--theme-card-soft); border-radius: 12px; border: 1px solid var(--theme-border);">
                <label style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--theme-heading); cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #20b4cb;">
                    Langsung aktifkan dan tampilkan modul ke pengguna
                </label>
            </div>

            {{-- Lessons list builder --}}
            <div style="margin-bottom: 28px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--theme-heading);">Poin Materi Pembelajaran (Lessons)</h3>
                        <p style="margin: 4px 0 0; color: var(--theme-muted); font-size: 0.85rem;">Setiap poin mewakili satu slide materi yang akan dibaca pengguna.</p>
                    </div>
                    <button type="button" id="btnAddLesson" style="background: rgba(32, 180, 203, 0.15); color: #20b4cb; border: none; padding: 8px 14px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                        + Tambah Poin Materi
                    </button>
                </div>

                <div id="lessonsContainer" style="display: flex; flex-direction: column; gap: 12px;">
                    @php $oldLessons = old('lessons', ['', '']); @endphp
                    @foreach($oldLessons as $idx => $lessonText)
                        <div class="lesson-row" style="display: flex; gap: 10px; align-items: flex-start;">
                            <span class="lesson-number" style="padding-top: 10px; font-weight: 700; color: var(--theme-muted); min-width: 24px;">{{ $idx + 1 }}.</span>
                            <textarea name="lessons[]" rows="2" required placeholder="Tuliskan isi materi pembelajaran poin ini..."
                                      style="flex: 1; padding: 10px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);">{{ $lessonText }}</textarea>
                            <button type="button" class="btn-remove-lesson" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: none; padding: 10px 14px; border-radius: 8px; cursor: pointer; font-weight: 700;">&times;</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.modules.index') }}" style="padding: 12px 20px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-weight: 600;">Batal</a>
                <button type="submit" style="padding: 12px 24px; border-radius: 10px; background: #20b4cb; color: #fff; border: none; font-weight: 700; cursor: pointer;">
                    Simpan Modul
                </button>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('lessonsContainer');
    const btnAdd = document.getElementById('btnAddLesson');

    function updateNumbers() {
        container.querySelectorAll('.lesson-row').forEach((row, i) => {
            row.querySelector('.lesson-number').textContent = (i + 1) + '.';
        });
    }

    btnAdd.addEventListener('click', function () {
        const div = document.createElement('div');
        div.className = 'lesson-row';
        div.style.cssText = 'display: flex; gap: 10px; align-items: flex-start;';
        div.innerHTML = `
            <span class="lesson-number" style="padding-top: 10px; font-weight: 700; color: var(--theme-muted); min-width: 24px;">.</span>
            <textarea name="lessons[]" rows="2" required placeholder="Tuliskan isi materi pembelajaran poin ini..."
                      style="flex: 1; padding: 10px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text);"></textarea>
            <button type="button" class="btn-remove-lesson" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: none; padding: 10px 14px; border-radius: 8px; cursor: pointer; font-weight: 700;">&times;</button>
        `;
        container.appendChild(div);
        updateNumbers();
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-lesson')) {
            if (container.querySelectorAll('.lesson-row').length > 1) {
                e.target.closest('.lesson-row').remove();
                updateNumbers();
            } else {
                alert('Modul harus memiliki minimal 1 poin materi.');
            }
        }
    });
});
</script>
@endsection

