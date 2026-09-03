<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoaItem;

class CoaMouldingFjSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['coa_code' => 'SEC-5101', 'name' => 'DIRECT LABOUR (MOULDING & FJ PLANT)', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'standard_rate_per_ton' => 45.00],
            ['coa_code' => 'SEC-5102', 'name' => 'ELECTRICITY & POWER (SECONDARY COMPRESSORS)', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 38.50],
            ['coa_code' => 'SEC-5103', 'name' => 'GLUE & ADHESIVE (EPI / PVAc FOR FJ)', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'standard_rate_per_ton' => 28.00],
            ['coa_code' => 'SEC-5104', 'name' => 'CUTTER KNIVES, PROFILING HEADS & DUST DUCTING', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 16.50],
            ['coa_code' => 'SEC-6101', 'name' => 'FACTORY OVERHEAD - SECONDARY PLANT DEPRECIATION', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 42.00],
            ['coa_code' => 'SEC-6102', 'name' => 'MACHINE PREVENTIVE MAINTENANCE (MOULDER & FINGER JOINT)', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 24.00],
            ['coa_code' => 'SEC-6103', 'name' => 'SUPERVISORY & FORKLIFT DRIVER ALLOWANCE', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'standard_rate_per_ton' => 26.00],
        ];

        foreach ($items as $item) {
            CoaItem::updateOrCreate(
                ['coa_code' => $item['coa_code']],
                array_merge($item, [
                    'product_type' => 'Moulding_FJ',
                    'is_reducible' => true,
                ])
            );
        }
    }
}