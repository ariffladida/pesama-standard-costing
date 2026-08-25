<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoaItem;

class CoaItemsSeeder extends Seeder
{
    public function run(): void
    {
        $coas = [

            // =========================================================================
            // SEKSYEN 1: STOK, BELIAN & BAKI PAPAN (6000 SERIES)
            // =========================================================================
            ['coa_code' => '6000/000', 'name' => 'MANUFACTURING COSTS - SAWNTIMBER', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '6001/000', 'name' => 'OPENING BALANCE - SAWNTIMBER', 'cost_type' => 'Balance', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '6001/100', 'name' => 'OPENING BALANCE - SAWNTIMBER (IN TRANSIT)', 'cost_type' => 'Balance', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '6010/000', 'name' => 'BELIAN PAPAN', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '6020/000', 'name' => 'CLOSING BALANCE - SAWNTIMBER', 'cost_type' => 'Balance', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '6020/100', 'name' => 'CLOSING BALANCE - SAWNTIMBER (IN TRANSIT)', 'cost_type' => 'Balance', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '610-0000', 'name' => 'PURCHASES SAWNTIMBER', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '612-0000', 'name' => 'PURCHASES RETURN - SAWNTIMBER', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],

            // =========================================================================
            // SEKSYEN 2: KOS PENGELUARAN, BAHAN MENTAH & LOGISTIK BALAK (7000/100 SERIES)
            // =========================================================================
            ['coa_code' => '7000/100', 'name' => 'KOS PENGELUARAN', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7000/101', 'name' => 'STOK AWAL BALAK', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => false],
            ['coa_code' => '7000/107', 'name' => 'BELIAN BALAK PERMINT PLYWOOD', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/108', 'name' => 'CAMPUR-BELIAN BALAK PESAMA', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/109', 'name' => 'CAMPUR-BELIAN BALAK KPKK', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/110', 'name' => 'CAMPUR-BELIAN BALAK LUAR', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/111', 'name' => 'PENGANGKUTAN LORI BALAK', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/112', 'name' => 'CUKAI BALAK', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/113', 'name' => 'KUPAS KULIT-KPKK', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/114', 'name' => 'PENGANGKUTAN LOADER', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => true],
            ['coa_code' => '7000/115', 'name' => 'PENGANGKUTAN LORI BALAK-BALAK LUAR', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => true],
            ['coa_code' => '7000/130', 'name' => 'JUMLAH BAHAN MENTAH', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7000/150', 'name' => 'STOK AKHIR BALAK', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => false],
            ['coa_code' => '7000/155', 'name' => 'KOS BAHAN MENTAH DIGUNAKAN', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7000/160', 'name' => 'STOK AWAL MINYAK', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/161', 'name' => 'STOK AWAL ALATGANTI', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/165', 'name' => 'INVENTORIES MINYAK (BELIAN)', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/166', 'name' => 'INVENTORIES ALATGANTI (BELIAN)', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/170', 'name' => 'STOK AKHIR MINYAK', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/171', 'name' => 'STOK AKHIR ALATGANTI', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/199', 'name' => 'JUMLAH INVENTORI & KOS PENGELUARAN', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],

            // =========================================================================
            // SEKSYEN 3: KOS OPERASI & UPAH KONTRAK PEMBUATAN (7000/200 SERIES)
            // =========================================================================
            ['coa_code' => '7000/200', 'name' => 'KOS OPERASI', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7000/260', 'name' => 'UPAH KONTRAK PEMOTONG/MEMBELAH', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/261', 'name' => 'UPAH KONTRAK MENYUSUN', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/262', 'name' => 'UPAH KONTRAK SAW DOCTOR', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/263', 'name' => 'UPAH KONTRAK MINISAW', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/264', 'name' => 'UPAH KONTRAK KUPAS KULIT BALAK', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/265', 'name' => 'UPAH KONTRAK ALIH BALAK DALAM KILANG', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/266', 'name' => 'UPAH KONTRAK WOODCHIP (TUKAR ACC)', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/267', 'name' => 'UPAH KONTRAK SAYONG/BERSIH/QC', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/268', 'name' => 'UPAH KONGSIKONG', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/269', 'name' => 'SAGUHATI PEKERJA KONTRAK', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/2ZZ', 'name' => 'JUMLAH KOS OPERASI', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],

            // =========================================================================
            // SEKSYEN 4: KOS OVERHEAD, LOJI, MINYAK & PENYELENGGARAAN (7000/300 & 700M/K)
            // =========================================================================
            ['coa_code' => '7000/300', 'name' => 'KOS OVERHEAD', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7000/319', 'name' => 'KLIN DRIED - KD', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => true],
            ['coa_code' => '7000/320', 'name' => 'PERBELANJAAN KILANG', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/321', 'name' => '** BAIKI MESIN KILANG **', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700M/005', 'name' => 'BAIKI MESIN KILANG #', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700M/010', 'name' => 'MESIN TASAK & PONY', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700M/020', 'name' => 'BANDSAW', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700M/030', 'name' => 'MESIN CROSS CUT', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700M/040', 'name' => 'AUTOMATION LINE', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700M/050', 'name' => 'TREATMENT PLANT', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700M/055', 'name' => 'RUMAH HABUK', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/322', 'name' => 'MINYAK % PELINCIR', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/324', 'name' => 'ELEKTRIK KILANG-ACC220627239607', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/325', 'name' => 'KIMIA', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/326', 'name' => '** ALATGANTI KENDERAAN KILANG **', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700K/005', 'name' => 'ALATGANTI KENDERAAN KILANG #', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700K/010', 'name' => 'LOADER', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700K/020', 'name' => 'FORKLIFT', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700K/030', 'name' => 'LORI', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '700K/040', 'name' => 'ALATGANTI KENDERAAN-TAYAR & TIUB', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/327', 'name' => 'MATA GERGAJI', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/328', 'name' => 'AIR (BIL AIR)', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/330', 'name' => 'SEWAAN PERALATAN', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/331', 'name' => 'WORKSHOP-PELBAGAI', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/332', 'name' => 'ALATGANTI SAW DOCTOR', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/3ZZ', 'name' => 'JUMLAH KOS OVERHEAD', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],

            // =========================================================================
            // SEKSYEN 5: KOS PENTADBIRAN KILANG, GAJI & STATUTORI (7000/A-Z SERIES)
            // =========================================================================
            ['coa_code' => '7000/A00', 'name' => 'KOS PENTADBIRAN-KILANG', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7000/A01', 'name' => 'ALATULIS DAN PERCETAKAN', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/A02', 'name' => 'AKHBAR DAN MAJALAH', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/B01', 'name' => 'BONUS', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/B02', 'name' => 'BONUS - EXGRATIA', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/B03', 'name' => 'BELANJA PENGURUSAN TENDER', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/C01', 'name' => 'CUKAI JALAN/TEST BODY', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/E01', 'name' => 'ELAUN LEBIH MASA', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/E02', 'name' => 'ELAUN PERUMAHAN', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/E03', 'name' => 'ELAUN KHAS', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/E04', 'name' => 'ELAUN TAMBAHAN', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/E05', 'name' => 'ELAUN KENDERAAN DAN PERJALANAN', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/E06', 'name' => 'ELAUN PELATIH-PRATIKAL', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/E07', 'name' => 'ELAUN HARIAN/PENGINAPAN/TAMBANG', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/E08', 'name' => 'ELAUN MINYAK&KENDERAAN', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/E09', 'name' => 'ELAUN TELEFON', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/F01', 'name' => 'FAEDAH PERSARAAN', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/G01', 'name' => 'GAJI KAKITANGAN', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/I01', 'name' => 'INSURAN PREMIUM', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/I02', 'name' => 'INSENTIF', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/K01', 'name' => 'KWSP KILANG', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/K02', 'name' => 'KERAIAN', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/K03', 'name' => 'KURSUS DAN SEMINAR', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/L01', 'name' => 'LESEN KILANG & YURAN', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/L02', 'name' => 'LEVI PEKERJA ASING', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/M01', 'name' => 'MEMBAIKI DAN MENJAGA PEJABAT KILANG', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/M02', 'name' => 'MINYAK/TOL/PARKING', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/M03', 'name' => 'PERALATAN DAN MANJAGA KAWASAN', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/P02', 'name' => 'PERUBATAN KILANG', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/P03', 'name' => 'PELBAGAI', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/P04', 'name' => 'PEMERIKSAAN KILANG', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/P05', 'name' => 'PAKAIAN/BARANGAN SAFETY & SAFETY & COVID19', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/P06', 'name' => 'PERUNTUKAN STOK LAMA', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/P08', 'name' => 'PENGINAPAN & HOTEL', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/S01', 'name' => 'SOCSO KILANG', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/S02', 'name' => 'STEM,POS&DUTI STEM', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/S03', 'name' => 'SUSUTNILAI KILANG', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => false],
            ['coa_code' => '7000/S04', 'name' => 'SKIM INSURAN PEKERJAAN (S.I.P)', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/S05', 'name' => 'SEWA PERALATAN', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/S06', 'name' => 'SEWA - WOODCHIP', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/S07', 'name' => 'SELENGGARA MESIN - WOODCHIP', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/T01', 'name' => 'TAMBANG', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/T02', 'name' => 'TELEFON/H.PHONE/UNIFI', 'cost_type' => 'Fixed', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7000/T03', 'name' => 'TAMBANG - WOODCHIP', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/T04', 'name' => 'TRIBUTE - WOODCHIP', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7000/ZZZ', 'name' => 'JUMLAH KOS PENTADBIRAN', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],

            // =========================================================================
            // SEKSYEN 6: PERBELANJAAN JUALAN & EKSPORT (7010 SERIES)
            // =========================================================================
            ['coa_code' => '7010/000', 'name' => 'SELLING EXPENSES', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7010/B01', 'name' => 'BUNDLING FEES & KONTENA', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7010/D01', 'name' => 'DOCUMENT AGEN/FORWADING/INSURAN MARI', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7010/G01', 'name' => 'GRADING FEES', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7010/K01', 'name' => 'KOMISEN JUALAN EKSPORT', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7010/L01', 'name' => 'LEVI/CESS', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7010/S01', 'name' => 'SHIPPING CHARGES', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7010/T01', 'name' => 'TAMBANG/PENGANGKUTAN PAPAN', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => true],
            ['coa_code' => '7010/T02', 'name' => 'TAMBANG PENGANGKUTAN WOOD CHIP', 'cost_type' => 'Variable', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7010/U01', 'name' => 'UPAH MASAK KAYU/KD/KETAM', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
            ['coa_code' => '7010/ZZZ', 'name' => 'JUMLAH SELLING EXPENSES', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],

            // =========================================================================
            // SEKSYEN 7: KOS PEMBUATAN WOODCHIP (7020 SERIES)
            // =========================================================================
            ['coa_code' => '7020/000', 'name' => 'MANUFACTURING COST - WOODCHIP', 'cost_type' => 'Summary', 'basis_type' => 'Summary', 'is_reducible' => false],
            ['coa_code' => '7020/M01', 'name' => 'MISCELLANEOUS - WOODCHIP', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7020/S01', 'name' => 'SELENGGARA MESIN - WOODCHIP', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7020/T01', 'name' => 'TRIBUTE - WOODCHIP', 'cost_type' => 'Fixed', 'basis_type' => 'Historical', 'is_reducible' => true],
            ['coa_code' => '7020/U01', 'name' => 'UPAH KONTRAK WOODCHIP', 'cost_type' => 'Variable', 'basis_type' => 'Contract', 'is_reducible' => false],
        ];

        foreach ($coas as $item) {
            CoaItem::updateOrCreate(
                ['coa_code' => $item['coa_code']],
                [
                    'name'                  => $item['name'],
                    'cost_type'             => $item['cost_type'],
                    'basis_type'            => $item['basis_type'],
                    'standard_rate_per_ton' => 0.00,
                    'is_reducible'          => $item['is_reducible'],
                ]
            );
        }
    }
}