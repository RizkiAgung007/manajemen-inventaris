    <?php

    namespace Database\Seeders;

    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash; // Jangan lupa import Hash

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            // Hapus atau komentari User::factory(10)->create(); jika ada

            // Buat 1 pengguna spesifik Anda
            User::create([
                'name' => 'Rizki',
                'email' => 'rizki@example.com',
                'password' => Hash::make('qwertyuiop[]'),
                'role' => 'superadmin' // atau 'admin', sesuaikan dengan sistem Anda
            ]);

            // (Opsional) Buat beberapa pengguna palsu lainnya
            // User::factory(5)->create();

            // (Opsional) Panggil seeder Anda yang lain jika ada
            // $this->call([
            //     CategorySeeder::class,
            //     ProductSeeder::class,
            // ]);
        }
    }
    ```

3.  Setelah Anda menyimpan file itu, kembali ke **terminal Ubuntu (WSL)** Anda.
4.  Jalankan perintah ini. Ini akan menghapus database, membuat ulang tabel, DAN menjalankan Seeder Anda:

    ```bash
    ./vendor/bin/sail artisan migrate:fresh --seed

