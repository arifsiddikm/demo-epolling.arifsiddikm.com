<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── ADMIN ────────────────────────────────────────────────────
        $admin = User::create([
            'name'              => 'Administrator',
            'email'             => 'admin@epolling.com',
            'password'          => Hash::make('admin123'),
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // ─── DEMO USERS ───────────────────────────────────────────────
        $users = [
            ['name' => 'Budi Santoso',    'email' => 'budi@epolling.com',    'phone' => '081234567890'],
            ['name' => 'Siti Rahayu',     'email' => 'siti@epolling.com',    'phone' => '082345678901'],
            ['name' => 'Ahmad Fauzi',     'email' => 'ahmad@epolling.com',   'phone' => '083456789012'],
            ['name' => 'Dewi Kusuma',     'email' => 'dewi@epolling.com',    'phone' => '084567890123'],
            ['name' => 'Rizki Pratama',   'email' => 'rizki@epolling.com',   'phone' => '085678901234'],
            ['name' => 'Nur Aini',        'email' => 'nuraini@epolling.com', 'phone' => '086789012345'],
            ['name' => 'Demo User',       'email' => 'user@epolling.com',    'phone' => null],
        ];

        $createdUsers = [];
        foreach ($users as $u) {
            $createdUsers[] = User::create([
                'name'              => $u['name'],
                'email'             => $u['email'],
                'password'          => Hash::make('user123'),
                'phone'             => $u['phone'],
                'role'              => 'user',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
        }

        // One inactive user
        User::create([
            'name'              => 'User Nonaktif',
            'email'             => 'nonaktif@epolling.com',
            'password'          => Hash::make('user123'),
            'role'              => 'user',
            'is_active'         => false,
            'email_verified_at' => now(),
        ]);

        // ─── POLLS & VOTES ────────────────────────────────────────────
        $pollsData = [
            // Poll 1 – Pemilihan Ketua OSIS
            [
                'owner'   => $createdUsers[0],
                'poll'    => [
                    'title'                   => 'Pemilihan Ketua OSIS 2025',
                    'description'             => 'Pilih ketua OSIS terbaik untuk periode 2025/2026. Setiap siswa hanya boleh memilih satu kali.',
                    'primary_key_label'       => 'NIS (Nomor Induk Siswa)',
                    'primary_key_placeholder' => 'Masukkan NIS Anda',
                    'is_active'               => true,
                    'start_date'              => now()->subDays(3),
                    'end_date'                => now()->addDays(4),
                ],
                'options' => [
                    ['name' => 'Andi Wirawan',   'description' => 'Visi: Sekolah Maju, Siswa Berprestasi'],
                    ['name' => 'Bayu Setiawan',  'description' => 'Visi: Bersatu untuk Kemajuan Bersama'],
                    ['name' => 'Citra Lestari',  'description' => 'Visi: Inovasi dan Kreativitas Tanpa Batas'],
                ],
                'votes' => [
                    ['NIS001', 'Andi Wirawan'],
                    ['NIS002', 'Bayu Setiawan'],
                    ['NIS003', 'Andi Wirawan'],
                    ['NIS004', 'Citra Lestari'],
                    ['NIS005', 'Andi Wirawan'],
                    ['NIS006', 'Bayu Setiawan'],
                    ['NIS007', 'Citra Lestari'],
                    ['NIS008', 'Andi Wirawan'],
                    ['NIS009', 'Bayu Setiawan'],
                    ['NIS010', 'Andi Wirawan'],
                    ['NIS011', 'Citra Lestari'],
                    ['NIS012', 'Andi Wirawan'],
                ],
            ],

            // Poll 2 – Warna Kaos Komunitas
            [
                'owner'   => $createdUsers[1],
                'poll'    => [
                    'title'                   => 'Voting Warna Kaos Komunitas Laravel Indonesia',
                    'description'             => 'Bantu kami memilih warna kaos komunitas untuk gathering tahun ini!',
                    'primary_key_label'       => 'Username Discord',
                    'primary_key_placeholder' => 'Masukkan username Discord kamu',
                    'is_active'               => true,
                    'start_date'              => now()->subDays(1),
                    'end_date'                => now()->addDays(6),
                ],
                'options' => [
                    ['name' => 'Merah Maroon',   'description' => 'Elegan dan profesional'],
                    ['name' => 'Navy Blue',       'description' => 'Klasik dan timeless'],
                    ['name' => 'Hijau Sage',      'description' => 'Fresh dan modern'],
                    ['name' => 'Abu-abu Gelap',   'description' => 'Netral dan serbaguna'],
                ],
                'votes' => [
                    ['discord_001', 'Navy Blue'],
                    ['discord_002', 'Merah Maroon'],
                    ['discord_003', 'Navy Blue'],
                    ['discord_004', 'Hijau Sage'],
                    ['discord_005', 'Navy Blue'],
                    ['discord_006', 'Abu-abu Gelap'],
                    ['discord_007', 'Navy Blue'],
                    ['discord_008', 'Hijau Sage'],
                    ['discord_009', 'Merah Maroon'],
                    ['discord_010', 'Navy Blue'],
                ],
            ],

            // Poll 3 – Destinasi Wisata (SELESAI / tidak aktif)
            [
                'owner'   => $createdUsers[2],
                'poll'    => [
                    'title'                   => 'Pilih Destinasi Wisata Kantor 2025',
                    'description'             => 'Voting untuk menentukan destinasi team outing tahun 2025.',
                    'primary_key_label'       => 'NIK Karyawan',
                    'primary_key_placeholder' => 'Masukkan NIK Anda',
                    'is_active'               => false,
                    'start_date'              => now()->subDays(14),
                    'end_date'                => now()->subDays(2),
                ],
                'options' => [
                    ['name' => 'Bali',          'description' => 'Pulau Dewata yang eksotis'],
                    ['name' => 'Yogyakarta',     'description' => 'Kota budaya penuh sejarah'],
                    ['name' => 'Raja Ampat',     'description' => 'Surga bawah laut Indonesia'],
                    ['name' => 'Labuan Bajo',    'description' => 'Petualangan komodo menanti'],
                ],
                'votes' => [
                    ['NIK-001', 'Bali'],
                    ['NIK-002', 'Bali'],
                    ['NIK-003', 'Yogyakarta'],
                    ['NIK-004', 'Bali'],
                    ['NIK-005', 'Raja Ampat'],
                    ['NIK-006', 'Bali'],
                    ['NIK-007', 'Yogyakarta'],
                    ['NIK-008', 'Labuan Bajo'],
                    ['NIK-009', 'Bali'],
                    ['NIK-010', 'Raja Ampat'],
                    ['NIK-011', 'Bali'],
                    ['NIK-012', 'Yogyakarta'],
                    ['NIK-013', 'Bali'],
                    ['NIK-014', 'Labuan Bajo'],
                    ['NIK-015', 'Bali'],
                    ['NIK-016', 'Raja Ampat'],
                    ['NIK-017', 'Bali'],
                    ['NIK-018', 'Yogyakarta'],
                ],
            ],

            // Poll 4 – Makanan Favorit
            [
                'owner'   => $createdUsers[3],
                'poll'    => [
                    'title'                   => 'Makanan Favorit Mahasiswa 2025',
                    'description'             => 'Survey sederhana untuk menentukan kantin mana yang paling disukai.',
                    'primary_key_label'       => 'NIM Mahasiswa',
                    'primary_key_placeholder' => 'Contoh: 2021310001',
                    'is_active'               => true,
                    'start_date'              => now()->subDays(2),
                    'end_date'                => now()->addDays(5),
                ],
                'options' => [
                    ['name' => 'Nasi Padang',    'description' => 'Lezat dan mengenyangkan'],
                    ['name' => 'Mie Ayam',        'description' => 'Murah meriah idola mahasiswa'],
                    ['name' => 'Soto Ayam',       'description' => 'Hangat dan menyehatkan'],
                    ['name' => 'Gado-gado',       'description' => 'Sehat dan bergizi'],
                    ['name' => 'Bakso',           'description' => 'Favoriit semua kalangan'],
                ],
                'votes' => [
                    ['2021310001', 'Bakso'],
                    ['2021310002', 'Mie Ayam'],
                    ['2021310003', 'Nasi Padang'],
                    ['2021310004', 'Bakso'],
                    ['2021310005', 'Soto Ayam'],
                    ['2021310006', 'Mie Ayam'],
                    ['2021310007', 'Bakso'],
                    ['2021310008', 'Gado-gado'],
                    ['2021310009', 'Bakso'],
                    ['2021310010', 'Nasi Padang'],
                    ['2021310011', 'Mie Ayam'],
                    ['2021310012', 'Bakso'],
                    ['2021310013', 'Soto Ayam'],
                    ['2021310014', 'Nasi Padang'],
                ],
            ],

            // Poll 5 – Tema Seminar (milik demo user)
            [
                'owner'   => $createdUsers[6], // Demo User
                'poll'    => [
                    'title'                   => 'Tema Seminar Teknologi 2025',
                    'description'             => 'Pilih tema yang paling relevan untuk seminar teknologi akhir tahun.',
                    'primary_key_label'       => 'Email Peserta',
                    'primary_key_placeholder' => 'Masukkan email Anda',
                    'is_active'               => true,
                    'start_date'              => now(),
                    'end_date'                => now()->addDays(10),
                ],
                'options' => [
                    ['name' => 'Artificial Intelligence & Machine Learning', 'description' => 'Tren AI yang mengubah dunia'],
                    ['name' => 'Cybersecurity & Data Privacy',               'description' => 'Keamanan digital di era modern'],
                    ['name' => 'Cloud Computing & DevOps',                   'description' => 'Infrastruktur masa depan'],
                    ['name' => 'Blockchain & Web3',                          'description' => 'Desentralisasi dan masa depan internet'],
                ],
                'votes' => [
                    ['user@a.com',  'Artificial Intelligence & Machine Learning'],
                    ['user@b.com',  'Cybersecurity & Data Privacy'],
                    ['user@c.com',  'Artificial Intelligence & Machine Learning'],
                    ['user@d.com',  'Cloud Computing & DevOps'],
                    ['user@e.com',  'Artificial Intelligence & Machine Learning'],
                    ['user@f.com',  'Blockchain & Web3'],
                    ['user@g.com',  'Cybersecurity & Data Privacy'],
                    ['user@h.com',  'Artificial Intelligence & Machine Learning'],
                ],
            ],

            // Poll 6 – Pilihan Olah Raga
            [
                'owner'   => $createdUsers[4],
                'poll'    => [
                    'title'                   => 'Olahraga Rutin Mingguan RT 05',
                    'description'             => 'Pilih jenis olahraga yang akan dijadikan kegiatan rutin setiap minggu.',
                    'primary_key_label'       => 'Nomor KK',
                    'primary_key_placeholder' => 'Masukkan nomor KK',
                    'is_active'               => true,
                    'start_date'              => now()->subDays(1),
                    'end_date'                => now()->addDays(3),
                ],
                'options' => [
                    ['name' => 'Senam Pagi',   'description' => 'Setiap Minggu jam 06.00'],
                    ['name' => 'Badminton',    'description' => 'Setiap Sabtu sore'],
                    ['name' => 'Futsal',       'description' => 'Setiap Sabtu pagi'],
                    ['name' => 'Voli',         'description' => 'Setiap Minggu sore'],
                ],
                'votes' => [
                    ['KK-001', 'Badminton'],
                    ['KK-002', 'Senam Pagi'],
                    ['KK-003', 'Badminton'],
                    ['KK-004', 'Voli'],
                    ['KK-005', 'Badminton'],
                    ['KK-006', 'Futsal'],
                    ['KK-007', 'Badminton'],
                    ['KK-008', 'Senam Pagi'],
                    ['KK-009', 'Voli'],
                    ['KK-010', 'Badminton'],
                ],
            ],
        ];

        foreach ($pollsData as $data) {
            $poll = Poll::create(array_merge(
                ['user_id' => $data['owner']->id],
                $data['poll']
            ));

            // Create options
            $optionModels = [];
            foreach ($data['options'] as $i => $opt) {
                $optionModels[$opt['name']] = PollOption::create([
                    'poll_id'     => $poll->id,
                    'name'        => $opt['name'],
                    'description' => $opt['description'] ?? null,
                    'order'       => $i,
                ]);
            }

            // Create votes
            foreach ($data['votes'] as [$voterKey, $optionName]) {
                if (!isset($optionModels[$optionName])) continue;
                PollVote::create([
                    'poll_id'        => $poll->id,
                    'poll_option_id' => $optionModels[$optionName]->id,
                    'voter_key'      => $voterKey,
                    'voter_name'     => null,
                    'ip_address'     => fake()->ipv4(),
                    'created_at'     => now()->subMinutes(rand(1, 4320)),
                    'updated_at'     => now()->subMinutes(rand(1, 4320)),
                ]);
            }
        }
    }
}
