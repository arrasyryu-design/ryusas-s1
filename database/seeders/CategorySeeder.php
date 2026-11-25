<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Elektronik' => 'Lampu, stop kontak, kipas angin, proyektor, komputer, dan perangkat elektronik lain',
            'Furnitur' => 'Meja, kursi, papan tulis, lemari, dan perabot kelas',
            'Bangunan' => 'Dinding, atap, plafon, pintu, jendela, dan lantai',
            'Sanitasi' => 'Toilet, keran air, wastafel, dan saluran pembuangan',
            'Infrastruktur' => 'Lapangan, selasar, pagar, jalan, dan fasilitas umum sekolah',
            'Lainnya' => 'Kerusakan fasilitas yang tidak termasuk kategori lain',
        ];

        foreach ($items as $name => $description) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => $description, 'is_active' => true]
            );
        }
    }
}
