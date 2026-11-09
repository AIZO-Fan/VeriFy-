window.verifyStoryMock = {
    data: {
        id: 1,
        topic_id: 1,
        slug: 'hadiah-biaya-administrasi',
        title: 'Hadiah yang Meminta Biaya',
        description: 'Nomor asing menawarkan hadiah dengan biaya awal.',
        difficulty: 'beginner',
        version: 1,

        story: {
            schema_version: 1,
            start: 'pesan_awal',

            contact: {
                name: 'Admin Hadiah',
                avatar_text: 'H',
                subtitle: 'Nomor tidak dikenal · Simulasi',
            },

            context: {
                title: 'Kamu menerima kabar menang hadiah',
                description:
                    'Kamu merasa tidak pernah mengikuti program tersebut. Baca pesannya dan tentukan langkahmu.',
            },

            nodes: {
                pesan_awal: {
                    type: 'conversation',
                    stage: 'situation',

                    messages: [
                        {
                            sender: 'contact',
                            text: 'Selamat! Nomor kamu terpilih mendapat hadiah Rp2.000.000.',
                        },
                        {
                            sender: 'contact',
                            text: 'Untuk pencairan, ada biaya administrasi Rp25.000. Bisa diproses sekarang?',
                        },
                    ],

                    prompt: 'Apa yang ingin kamu lakukan?',

                    choices: [
                        {
                            id: 'setuju',
                            label: 'Setuju membayar',
                            reply: 'Baik, saya setuju membayar.',
                            next: 'instruksi_bayar',
                        },
                        {
                            id: 'tanya',
                            label: 'Tanyakan asal program',
                            reply:
                                'Ini program apa? Saya tidak merasa mengikutinya.',
                            next: 'jawaban_pengirim',
                        },
                        {
                            id: 'periksa',
                            label: 'Periksa melalui saluran resmi',
                            reply: null,
                            next: 'hasil_aman',
                        },
                    ],
                },

                jawaban_pengirim: {
                    type: 'conversation',
                    stage: 'decision',

                    messages: [
                        {
                            sender: 'contact',
                            text:
                                'Ini program pelanggan terpilih. Harus segera diproses agar hadiahnya tidak hangus.',
                        },
                    ],

                    prompt: 'Setelah mendapat jawaban itu, apa langkahmu?',

                    choices: [
                        {
                            id: 'lanjut',
                            label: 'Lanjutkan ke pembayaran',
                            reply: 'Baik, bagaimana pembayarannya?',
                            next: 'instruksi_bayar',
                        },
                        {
                            id: 'berhenti',
                            label: 'Berhenti dan periksa dahulu',
                            reply: null,
                            next: 'hasil_aman',
                        },
                    ],
                },

                instruksi_bayar: {
                    type: 'conversation',
                    stage: 'decision',

                    messages: [
                        {
                            sender: 'contact',
                            text:
                                'Silakan bayar biaya administrasi Rp25.000 ke penerima berikut. Setelah itu hadiahnya kami proses.',
                        },
                    ],

                    prompt: 'Kamu masih bisa mempertimbangkan kembali.',

                    choices: [
                        {
                            id: 'lihat',
                            label: 'Lihat pembayaran simulasi',
                            reply: null,
                            next: 'konfirmasi_bayar',
                        },
                        {
                            id: 'batal',
                            label: 'Batalkan dan periksa',
                            reply: null,
                            next: 'hasil_aman',
                        },
                    ],
                },

                konfirmasi_bayar: {
                    type: 'payment',
                    stage: 'decision',
                    title: 'Konfirmasi pembayaran simulasi',
                    purpose: 'Biaya administrasi hadiah',
                    recipient: 'Penerima Fiktif',
                    amount: 25000,
                    currency: 'IDR',

                    confirm: {
                        id: 'bayar',
                        label: 'Lanjutkan pembayaran simulasi',
                        next: 'permintaan_tambahan',
                    },

                    cancel: {
                        id: 'batal_bayar',
                        label: 'Batalkan dan periksa dahulu',
                        next: 'hasil_aman',
                    },
                },

                permintaan_tambahan: {
                    type: 'conversation',
                    stage: 'consequence',

                    messages: [
                        {
                            sender: 'system',
                            text:
                                'Pembayaran fiktif Rp25.000 selesai. Tidak ada uang sungguhan yang dipindahkan.',
                        },
                        {
                            sender: 'contact',
                            text:
                                'Pembayaran sudah masuk. Ada biaya aktivasi tambahan Rp100.000 agar hadiah bisa dicairkan.',
                        },
                    ],

                    prompt: 'Apa dampak dari keputusan sebelumnya?',

                    choices: [
                        {
                            id: 'lihat_dampak',
                            label: 'Lihat dampak dan penjelasan',
                            reply: null,
                            next: 'hasil_berisiko',
                        },
                    ],
                },

                hasil_berisiko: {
                    type: 'outcome',
                    stage: 'consequence',
                    tone: 'warning',

                    scoring: {
                        score: 40,
                        max_score: 100,
                        scoring_version: 1,
                        breakdown: [
                            {
                                criterion: 'Keputusan pembayaran',
                                description:
                                    'Pembayaran fiktif diteruskan sebelum identitas dan program diperiksa.',
                                points: 40,
                                max_points: 100,
                            },
                        ],
                    },

                    title: 'Biaya dibayar, hadiah belum diterima',

                    description:
                        'Dalam cerita ini, pembayaran awal diikuti permintaan biaya tambahan. Hadiah yang dijanjikan tidak diterima.',

                    impact: {
                        label: 'Dampak dalam simulasi',
                        text: 'Rp25.000 dibayarkan secara fiktif',
                        simulated_loss_amount: 25000,
                    },

                    reflection: {
                        summary:
                            'Keputusan pertama membuka jalan bagi permintaan berikutnya dalam cerita ini.',

                        what_happened:
                            'Kamu melakukan pembayaran sebelum memastikan identitas pengirim dan kebenaran program.',

                        why_it_matters:
                            'Janji hadiah dan desakan waktu membuat proses pemeriksaan terlewat. Pembayaran tidak membuktikan bahwa hadiah benar-benar tersedia.',

                        safer_alternative:
                            'Hentikan pembayaran tambahan. Periksa informasi melalui kanal resmi yang kamu buka sendiri, bukan kontak yang diberikan pengirim.',

                        signals: [
                            'Hadiah disertai biaya awal.',
                            'Asal program tidak jelas.',
                            'Ada desakan agar segera bertindak.',
                            'Muncul permintaan biaya tambahan.',
                        ],
                    },
                },

                hasil_aman: {
                    type: 'outcome',
                    stage: 'consequence',
                    tone: 'positive',

                    scoring: {
                        score: 100,
                        max_score: 100,
                        scoring_version: 1,
                        breakdown: [
                            {
                                criterion: 'Menghentikan pembayaran',
                                description:
                                    'Kamu berhenti dan memilih memeriksa sebelum pembayaran diteruskan.',
                                points: 100,
                                max_points: 100,
                            },
                        ],
                    },

                    title: 'Kamu berhenti sebelum membayar',

                    description:
                        'Pada kanal resmi fiktif dalam cerita ini, program tersebut tidak ditemukan. Kamu tidak melanjutkan pembayaran.',

                    impact: {
                        label: 'Dampak dalam simulasi',
                        text: 'Tidak ada pembayaran',
                        simulated_loss_amount: 0,
                    },

                    reflection: {
                        summary:
                            'Kamu memilih memeriksa klaim melalui sumber terpisah.',

                        what_happened:
                            'Pembayaran tidak diteruskan dan kamu tidak hanya mengandalkan penjelasan pengirim.',

                        why_it_matters:
                            'Nama, foto, dan pengakuan pengirim belum cukup untuk membuktikan identitas atau kebenaran program.',

                        safer_alternative:
                            'Pertahankan kebiasaan memeriksa informasi melalui aplikasi atau kanal resmi yang sudah kamu kenal.',

                        signals: [
                            'Permintaan pembayaran untuk menerima hadiah.',
                            'Program belum terverifikasi.',
                        ],
                    },
                },
            },
        },
    },
};