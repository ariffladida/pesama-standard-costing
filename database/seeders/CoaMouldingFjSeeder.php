<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoaItem;

class CoaMouldingFjSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Padam data dummy lama bagi kategori Moulding_FJ
        CoaItem::where('product_type', 'Moulding_FJ')->delete();

        // 2. Senarai penuh 81 kod akaun mengikut fail Excel Pesama Timber
        $items = [
            ['coa_code' => '7000/000', 'name' => 'MANUFACTURING A/C', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '7100/000', 'name' => 'OPERATION COST', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '71B1/000', 'name' => 'Belts & chains', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71B2/000', 'name' => 'Bundling, strapping & crating', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71C1/000', 'name' => 'Chainsaw spares', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71C2/000', 'name' => 'Chemicals', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71D1/000', 'name' => 'Diesel & petrol', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71E1/000', 'name' => 'Electrical maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71F1/000', 'name' => 'Forklifts diesel & lubricants', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 20.00, 'is_reducible' => true],
            ['coa_code' => '71F2/000', 'name' => 'Forklifts repairs & maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71F3/000', 'name' => 'Forklift tyre & tube', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71G2/000', 'name' => 'Gloves, helmets & tapes', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 5.00, 'is_reducible' => true],
            ['coa_code' => '71G3/000', 'name' => 'Grinding materials', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71G4/000', 'name' => 'GLUE/ADHESIVE', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71L1/000', 'name' => 'Loader diesel & lubricants', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71L2/000', 'name' => 'Loader repairs & maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71L3/000', 'name' => 'Loader tyre & tubes', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71L4/000', 'name' => 'Lubricants & oil', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71L5/000', 'name' => 'Levy & Cess', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71L6/000', 'name' => 'Logs consumed (Cherul)', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71M1/000', 'name' => 'MV diesel & lubricants', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71M2/000', 'name' => 'MV repairs & spareparts', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71M3/000', 'name' => 'Mechanical maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71M4/000', 'name' => 'MV tyre & tubes', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71M5/000', 'name' => 'Management charge', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71O1/000', 'name' => 'Other civil maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71O2/000', 'name' => 'Other store supply', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71O3/000', 'name' => 'Other Expenses', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71P1/000', 'name' => 'P&M belts & chains', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71P2/000', 'name' => 'P&M electrical', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71P3/000', 'name' => 'P&M lubricants & oil', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71P4/000', 'name' => 'P&M mechanical', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71P5/000', 'name' => 'P&M other maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71P6/000', 'name' => 'Ppaints', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71P8/000', 'name' => 'Printing & stationery', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 5.00, 'is_reducible' => true],
            ['coa_code' => '71P9/000', 'name' => 'Professional fee', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71R1/000', 'name' => 'Raw material consumed ( logs )', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71R2/000', 'name' => 'Repair & maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 13.33, 'is_reducible' => true],
            ['coa_code' => '71R3/000', 'name' => 'Retirement benefit', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 9.63, 'is_reducible' => true],
            ['coa_code' => '71R4/000', 'name' => 'R&D', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71R5/000', 'name' => 'Rental Equipment (Acetylene, Oxygen, Pure)', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71S1/000', 'name' => 'Shipping', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71S2/000', 'name' => 'Sawbalade & knives', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 10.00, 'is_reducible' => true],
            ['coa_code' => '71S3/000', 'name' => 'Sawing fees', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71S4/000', 'name' => 'Sawn timber consumed', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71S5/000', 'name' => 'Shipping & forwarding', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71S6/000', 'name' => 'Spare parts', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71S7/000', 'name' => 'Safety Equipment/ Uniform/ Others', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71T1/000', 'name' => 'Transport', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71W1/000', 'name' => 'Welding accessories maintenance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '71ZZ/ZZZ', 'name' => 'TOTAL OPERATION COST', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '7200/000', 'name' => 'DIRECT LABOUR', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '72C1/000', 'name' => 'Contract Labour', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '72D1/000', 'name' => 'Depreciations', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 26.92, 'is_reducible' => true],
            ['coa_code' => '72E1/000', 'name' => 'Electricity', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 46.67, 'is_reducible' => true],
            ['coa_code' => '72I1/000', 'name' => 'Insurance & Road tax', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '72L1/000', 'name' => 'License', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '72L2/000', 'name' => 'Loader rental', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '72O1/000', 'name' => 'Other expense', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.67, 'is_reducible' => true],
            ['coa_code' => '72W1/000', 'name' => 'Water', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 1.33, 'is_reducible' => true],
            ['coa_code' => '72W2/000', 'name' => 'Write Off', 'cost_type' => 'Balance', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '72ZZ/ZZZ', 'name' => 'TOTAL DIRECT LABOUR', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '7300/000', 'name' => 'OVERHEAD', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '73A1/000', 'name' => 'Allowance', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73A2/000', 'name' => 'Accomodation', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 5.09, 'is_reducible' => true],
            ['coa_code' => '73B1/000', 'name' => 'Bonus', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73C1/000', 'name' => 'Course & seminar', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73E1/000', 'name' => 'Entertainment', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73E2/000', 'name' => 'EPF', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 22.43, 'is_reducible' => true],
            ['coa_code' => '73E3/000', 'name' => 'Export promotion', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73E5/000', 'name' => 'EX-GRATIA OPERATION', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73G1/000', 'name' => 'Grader allowance', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73M1/000', 'name' => 'Medical', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 6.67, 'is_reducible' => true],
            ['coa_code' => '73O1/000', 'name' => 'Overtime', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73S1/000', 'name' => 'Salary', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 170.18, 'is_reducible' => true],
            ['coa_code' => '73S2/000', 'name' => 'Socso', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 3.45, 'is_reducible' => true],
            ['coa_code' => '73S3/000', 'name' => 'Staff welfare', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73T1/000', 'name' => 'Travelling', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73T2/000', 'name' => 'Telephone', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
            ['coa_code' => '73Z9/000', 'name' => 'TOTAL OVERHEAD', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'standard_rate_per_ton' => 0.00, 'is_reducible' => false],
            ['coa_code' => '7700/000', 'name' => 'AD timber purchase', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'standard_rate_per_ton' => 0.00, 'is_reducible' => true],
        ];

        foreach ($items as $item) {
            CoaItem::create([
                'coa_code' => $item['coa_code'],
                'name' => $item['name'],
                'cost_type' => $item['cost_type'],
                'basis_type' => $item['basis_type'],
                'standard_rate_per_ton' => $item['standard_rate_per_ton'],
                'is_reducible' => $item['is_reducible'],
                'product_type' => 'Moulding_FJ',
            ]);
        }
    }
}