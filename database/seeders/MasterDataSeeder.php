<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Species;
use App\Models\Grade;
use App\Models\SystemSetting;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 38 Spesies Kayu Sebenar
        $species = [
            'AJAL', 'BALAU', 'BITIS', 'CHENGAL', 'DAMAR HITAM',
            'DARK RED MERANTI(NEMESU)PHND', 'DARK RED MERANTI(SERAYA)PHND',
            'DRM(DUC)', 'GERUTU', 'KAPUR', 'KASAI', 'KEKATONG', 'KELADAN',
            'KELAT', 'KEMBANG SEMANGKOK', 'KEMPAS', 'KERUING', 'KULIM',
            'MALAYA', 'MELANTAI', 'MELUNAK', 'MEMBATU', 'MENGKULANG',
            'MERANTI', 'MERANTI SERAYA', 'MERAWAN', 'MERBAU', 'MERPAUH',
            'MERSAWA', 'MINYAK BERUK', 'MIX HARDWOOD', 'MIXED MEDIUM HARDWOOD',
            'NEMESU', 'NYATOH', 'PAUH KIJANG', 'SERAYA', 'SERAYA KITAM', 'SIMPOH'
        ];

        foreach ($species as $item) {
            Species::firstOrCreate(['name' => $item]);
        }

        // 23 Gred Kayu Sebenar
        $grades = [
            'BLUE STAIN',
            'HATI',
            'LOCAL GRADE 1',
            'LOCAL GRADE 2',
            'MERCHANTABLE FULLSAWN',
            'MERCHANTABLE FULLSAWN(SHORT)',
            'S',
            'S1',
            'S2',
            'S3',
            'SAPFREE',
            'SAPFREE(SHORT)',
            'SELECT & BETTER FULLSAWN',
            'SELECT & BETTER FULLSAWN(PHND)',
            'SELECT&BETTER FULLSAWN(SHORT PHND)',
            'SOUND & BETTER FULLSAWN',
            'SOUND & BETTER FULLSAWN(SHORT)',
            'STANDARD & BETTER FULLSAWN',
            'STANDARD & BETTER FULLSAWN(SHORT)',
            'STANDARD & BETTER(PHND)',
            'STANDARD & BETTER(PHND)SHORT',
            'STANDARD&BETTER(SAPFREE)SHORT',
            'STANDARD(SAPFREE)'
        ];

        foreach ($grades as $item) {
            Grade::firstOrCreate(['name' => $item]);
        }

        // Tetapan Default Kos Pengangkutan
        SystemSetting::firstOrCreate(
            ['key' => 'fixed_transport_cost'],
            ['value' => 68.00, 'year' => 2026]
        );
    }
}