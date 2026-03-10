<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@pesantren.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin Pesantren',
                'email' => 'admin@pesantren.com',
                'password' => Hash::make('admin123'), // GANTI PASSWORD INI untuk production!
            ]);

            $this->command->info('✅ Admin user berhasil dibuat!');
            $this->command->info('📧 Email: admin@pesantren.com');
            $this->command->info('🔑 Password: admin123');
            $this->command->warn('⚠️  PENTING: Ganti password default setelah login pertama kali!');
        } else {
            $this->command->info('ℹ️  Admin user sudah ada, skip seeding.');
        }
    }
}
