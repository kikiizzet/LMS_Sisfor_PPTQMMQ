<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $santri = App\Models\Santri::create([
        'nama_lengkap' => 'Santri Test',
        'no_induk' => 'TEST1234',
        'kelas_id' => 3,
        'jenis_kelamin' => 'L',
        'status' => 'Aktif'
    ]);
    echo "Successfully created Santri with ID: " . $santri->id . "\n";
    $santri->delete();
    echo "Successfully deleted test Santri\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

