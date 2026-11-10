@include('activity-history.partials.empty-state', [
    'label' => 'INTEGRASI DATA',
    'title' => 'Progres modul belum terhubung',
    'description' => 'Status membaca dan posisi terakhir akan ditampilkan setelah penyimpanan progres disambungkan.',
    'url' => route('modules'),
    'button' => 'Jelajahi modul',
])