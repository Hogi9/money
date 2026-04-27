<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionName;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();
        if (! $admin) {
            $this->command->error('Admin user not found.');
            return;
        }

        DB::transaction(function () use ($admin) {
            [$wallets, $balances]       = $this->createWallets($admin);
            [$incCats, $outCats]        = $this->createCategories($admin);
            [$incNames, $outNames]      = $this->createTransactionNames($admin, $incCats, $outCats);
            $this->createTransactions($admin, $wallets, $balances, $incCats, $outCats, $incNames, $outNames);
        });

        $this->command->info('Finance dummy data seeded successfully.');
    }

    private function createWallets(User $admin): array
    {
        $data = [
            ['name' => 'BCA',  'description' => 'Rekening BCA utama'],
            ['name' => 'Cash', 'description' => 'Uang tunai harian'],
            ['name' => 'OVO',  'description' => 'Dompet digital OVO'],
        ];

        $wallets  = [];
        $balances = [];

        foreach ($data as $item) {
            $wallet = Wallet::create([
                'name'        => $item['name'],
                'description' => $item['description'],
                'balance'     => 0,
                'user_id'     => $admin->id,
            ]);
            $wallets[$item['name']]  = $wallet;
            $balances[$wallet->id]   = 0;
        }

        return [$wallets, $balances];
    }

    private function createCategories(User $admin): array
    {
        $incomeCategories = [
            ['name' => 'Gaji',      'description' => 'Pendapatan gaji bulanan'],
            ['name' => 'Freelance', 'description' => 'Pendapatan proyek freelance'],
            ['name' => 'Investasi', 'description' => 'Return investasi & dividen'],
            ['name' => 'Bonus',     'description' => 'Bonus dan insentif kerja'],
        ];

        $outcomeCategories = [
            ['name' => 'Makanan & Minuman', 'description' => 'Kebutuhan makan & minum harian'],
            ['name' => 'Transportasi',      'description' => 'Bensin, ojek, tol, parkir'],
            ['name' => 'Utilitas',          'description' => 'Listrik, air, internet, pulsa'],
            ['name' => 'Belanja',           'description' => 'Kebutuhan rumah tangga & pribadi'],
            ['name' => 'Hiburan',           'description' => 'Streaming, bioskop, rekreasi'],
            ['name' => 'Kesehatan',         'description' => 'Obat-obatan, dokter, vitamin'],
            ['name' => 'Pendidikan',        'description' => 'Kursus, buku, langganan belajar'],
            ['name' => 'Tabungan & Dana',   'description' => 'Setor tabungan & dana darurat'],
        ];

        $incCats = [];
        foreach ($incomeCategories as $item) {
            $cat = Category::create([
                'name'        => $item['name'],
                'type'        => 'income',
                'description' => $item['description'],
                'user_id'     => $admin->id,
            ]);
            $incCats[$item['name']] = $cat;
        }

        $outCats = [];
        foreach ($outcomeCategories as $item) {
            $cat = Category::create([
                'name'        => $item['name'],
                'type'        => 'outcome',
                'description' => $item['description'],
                'user_id'     => $admin->id,
            ]);
            $outCats[$item['name']] = $cat;
        }

        return [$incCats, $outCats];
    }

    private function createTransactionNames(User $admin, array $incCats, array $outCats): array
    {
        $incomeNames = [
            'Gaji'      => ['Gaji Pokok', 'Tunjangan Kinerja', 'Uang Makan Kerja'],
            'Freelance' => ['Proyek Web Development', 'Desain UI/UX', 'Konsultasi IT', 'Copywriting'],
            'Investasi' => ['Dividen Saham', 'Return Reksa Dana', 'Bunga Deposito'],
            'Bonus'     => ['Bonus Tahunan', 'Bonus Proyek', 'Cashback Bank'],
        ];

        $outcomeNames = [
            'Makanan & Minuman' => ['Warung Makan', 'GoFood / ShopeeFood', 'Kopi & Snack', 'Grocery Supermarket', 'Makan Siang Kantor'],
            'Transportasi'      => ['Bensin Motor', 'Gojek / Grab', 'Tol & Parkir', 'Servis Kendaraan'],
            'Utilitas'          => ['Tagihan Listrik PLN', 'Tagihan Internet Indihome', 'Pulsa & Paket Data', 'Tagihan PDAM'],
            'Belanja'           => ['Shopee / Tokopedia', 'Indomaret / Alfamart', 'Pakaian & Aksesoris', 'Peralatan Rumah'],
            'Hiburan'           => ['Netflix', 'Spotify', 'Steam / Game', 'Bioskop', 'Jalan-jalan'],
            'Kesehatan'         => ['Apotek', 'Dokter / Klinik', 'Vitamin & Suplemen', 'Gym & Olahraga'],
            'Pendidikan'        => ['Udemy / Coursera', 'Buku & E-book', 'Langganan Medium'],
            'Tabungan & Dana'   => ['Setor Tabungan BCA', 'Dana Darurat', 'Investasi Reksa Dana'],
        ];

        $incNames = [];
        foreach ($incomeNames as $catName => $names) {
            $incNames[$catName] = [];
            foreach ($names as $name) {
                $tn = TransactionName::create([
                    'name'        => $name,
                    'category_id' => $incCats[$catName]->id,
                    'user_id'     => $admin->id,
                ]);
                $incNames[$catName][] = $tn;
            }
        }

        $outNames = [];
        foreach ($outcomeNames as $catName => $names) {
            $outNames[$catName] = [];
            foreach ($names as $name) {
                $tn = TransactionName::create([
                    'name'        => $name,
                    'category_id' => $outCats[$catName]->id,
                    'user_id'     => $admin->id,
                ]);
                $outNames[$catName][] = $tn;
            }
        }

        return [$incNames, $outNames];
    }

    private function createTransactions(
        User $admin,
        array $wallets,
        array $balances,
        array $incCats,
        array $outCats,
        array $incNames,
        array $outNames
    ): void {
        // 6 bulan: Nov 2025 – Apr 2026
        $startMonth = Carbon::create(2025, 11, 1);
        $bca        = $wallets['BCA'];
        $cash       = $wallets['Cash'];
        $ovo        = $wallets['OVO'];

        $transactions = [];

        for ($m = 0; $m < 6; $m++) {
            $month      = $startMonth->copy()->addMonths($m);
            $monthYear  = $month->format('Y-m');
            $daysInMonth = $month->daysInMonth;

            // --- INCOME ---

            // Gaji pokok tanggal 1
            $transactions[] = $this->tx('income', $bca, null, $incCats['Gaji'], $incNames['Gaji'][0],
                8500000, "$monthYear-01", $admin, "Gaji pokok bulan {$month->translatedFormat('F Y')}");

            // Tunjangan kinerja tanggal 3
            $transactions[] = $this->tx('income', $bca, null, $incCats['Gaji'], $incNames['Gaji'][1],
                1200000, "$monthYear-03", $admin, "Tunjangan kinerja bulan {$month->translatedFormat('F Y')}");

            // Uang makan tanggal 3
            $transactions[] = $this->tx('income', $cash, null, $incCats['Gaji'], $incNames['Gaji'][2],
                500000, "$monthYear-03", $admin, "Uang makan bulanan");

            // Freelance (tidak setiap bulan, variasi)
            if (in_array($m, [0, 1, 3, 4, 5])) {
                $freelanceDay   = rand(10, 25);
                $freelanceDay   = min($freelanceDay, $daysInMonth);
                $freelanceNames = $incNames['Freelance'];
                $fn             = $freelanceNames[array_rand($freelanceNames)];
                $amount         = rand(8, 25) * 100000;
                $transactions[] = $this->tx('income', $bca, null, $incCats['Freelance'], $fn,
                    $amount, "$monthYear-" . str_pad($freelanceDay, 2, '0', STR_PAD_LEFT),
                    $admin, "Pendapatan freelance");
            }

            // Investasi (dividen tiap bulan ke-2 dan ke-5)
            if (in_array($m, [1, 4])) {
                $transactions[] = $this->tx('income', $bca, null, $incCats['Investasi'], $incNames['Investasi'][0],
                    450000, "$monthYear-15", $admin, "Dividen saham bulanan");
            }
            if ($m === 5) {
                $transactions[] = $this->tx('income', $bca, null, $incCats['Investasi'], $incNames['Investasi'][2],
                    600000, "$monthYear-20", $admin, "Bunga deposito triwulan");
            }

            // Bonus akhir tahun (bulan pertama = Nov)
            if ($m === 0) {
                $transactions[] = $this->tx('income', $bca, null, $incCats['Bonus'], $incNames['Bonus'][0],
                    5000000, "$monthYear-28", $admin, "Bonus akhir tahun");
            }
            if ($m === 3) { // Feb — cashback bank
                $transactions[] = $this->tx('income', $ovo, null, $incCats['Bonus'], $incNames['Bonus'][2],
                    125000, "$monthYear-10", $admin, "Cashback promo bank");
            }

            // --- OUTCOME: Makanan (harian, ~20 transaksi/bulan) ---
            $foodNames = $outNames['Makanan & Minuman'];
            $foodDays  = $this->randomDays($daysInMonth, 18);
            foreach ($foodDays as $day) {
                $fn             = $foodNames[array_rand($foodNames)];
                $amount         = rand(15, 120) * 1000;
                $wallet         = rand(0, 1) ? $cash : $ovo;
                $transactions[] = $this->tx('outcome', $wallet, null, $outCats['Makanan & Minuman'], $fn,
                    $amount, "$monthYear-" . str_pad($day, 2, '0', STR_PAD_LEFT), $admin);
            }

            // --- OUTCOME: Transportasi (~8 transaksi/bulan) ---
            $transDays = $this->randomDays($daysInMonth, 8);
            foreach ($transDays as $idx => $day) {
                $tn             = $outNames['Transportasi'][$idx % count($outNames['Transportasi'])];
                $amount         = in_array($idx % 4, [0]) ? rand(50, 150) * 1000 : rand(15, 80) * 1000;
                $wallet         = $idx % 2 === 0 ? $cash : $ovo;
                $transactions[] = $this->tx('outcome', $wallet, null, $outCats['Transportasi'], $tn,
                    $amount, "$monthYear-" . str_pad($day, 2, '0', STR_PAD_LEFT), $admin);
            }

            // --- OUTCOME: Utilitas (tanggal tetap tiap bulan) ---
            $utilData = [
                [5,  $outNames['Utilitas'][0], rand(200, 350) * 1000, $bca,  "Listrik bulan {$month->format('M Y')}"],
                [7,  $outNames['Utilitas'][1], 300000,                $bca,  "Internet Indihome bulan {$month->format('M Y')}"],
                [12, $outNames['Utilitas'][2], rand(50, 100) * 1000,  $ovo,  "Pulsa & paket data"],
                [15, $outNames['Utilitas'][3], rand(80, 150) * 1000,  $bca,  "Tagihan PDAM"],
            ];
            foreach ($utilData as [$day, $tn, $amount, $wallet, $desc]) {
                $transactions[] = $this->tx('outcome', $wallet, null, $outCats['Utilitas'], $tn,
                    $amount, "$monthYear-" . str_pad($day, 2, '0', STR_PAD_LEFT), $admin, $desc);
            }

            // --- OUTCOME: Belanja (~3-5 transaksi/bulan) ---
            $belanjaDays = $this->randomDays($daysInMonth, rand(3, 5));
            foreach ($belanjaDays as $idx => $day) {
                $tn             = $outNames['Belanja'][$idx % count($outNames['Belanja'])];
                $amount         = rand(50, 400) * 1000;
                $wallet         = $idx % 3 === 0 ? $cash : ($idx % 3 === 1 ? $ovo : $bca);
                $transactions[] = $this->tx('outcome', $wallet, null, $outCats['Belanja'], $tn,
                    $amount, "$monthYear-" . str_pad($day, 2, '0', STR_PAD_LEFT), $admin);
            }

            // --- OUTCOME: Hiburan (~4 transaksi/bulan) ---
            $hiburanFixed = [
                [5,  $outNames['Hiburan'][0], 54000,  $bca, "Langganan Netflix"],
                [5,  $outNames['Hiburan'][1], 30000,  $bca, "Langganan Spotify"],
            ];
            foreach ($hiburanFixed as [$day, $tn, $amount, $wallet, $desc]) {
                $transactions[] = $this->tx('outcome', $wallet, null, $outCats['Hiburan'], $tn,
                    $amount, "$monthYear-" . str_pad($day, 2, '0', STR_PAD_LEFT), $admin, $desc);
            }
            if (rand(0, 1)) {
                $hiburanDay     = rand(8, 25);
                $hiburanDay     = min($hiburanDay, $daysInMonth);
                $tn             = $outNames['Hiburan'][rand(2, 4)];
                $amount         = rand(30, 200) * 1000;
                $transactions[] = $this->tx('outcome', $cash, null, $outCats['Hiburan'], $tn,
                    $amount, "$monthYear-" . str_pad($hiburanDay, 2, '0', STR_PAD_LEFT), $admin);
            }

            // --- OUTCOME: Kesehatan (~2 transaksi/bulan) ---
            $kesehatanDays = $this->randomDays($daysInMonth, 2);
            foreach ($kesehatanDays as $idx => $day) {
                $tn             = $outNames['Kesehatan'][$idx % count($outNames['Kesehatan'])];
                $amount         = rand(20, 250) * 1000;
                $wallet         = $idx % 2 === 0 ? $bca : $cash;
                $transactions[] = $this->tx('outcome', $wallet, null, $outCats['Kesehatan'], $tn,
                    $amount, "$monthYear-" . str_pad($day, 2, '0', STR_PAD_LEFT), $admin);
            }

            // --- OUTCOME: Pendidikan (~1-2 transaksi/bulan) ---
            if ($m % 2 === 0) {
                $tn             = $outNames['Pendidikan'][rand(0, count($outNames['Pendidikan']) - 1)];
                $amount         = rand(50, 200) * 1000;
                $day            = rand(10, 20);
                $transactions[] = $this->tx('outcome', $bca, null, $outCats['Pendidikan'], $tn,
                    $amount, "$monthYear-" . str_pad($day, 2, '0', STR_PAD_LEFT), $admin);
            }

            // --- OUTCOME: Tabungan/Dana (tanggal 25 tiap bulan) ---
            $transactions[] = $this->tx('outcome', $bca, null, $outCats['Tabungan & Dana'], $outNames['Tabungan & Dana'][1],
                1000000, "$monthYear-25", $admin, "Setor dana darurat rutin");

            // --- TRANSFER: BCA → Cash (tarik tunai, tanggal 1 & 15) ---
            $transactions[] = $this->txTransfer($bca, $cash, 600000,
                "$monthYear-02", $admin, "Tarik tunai bulanan awal");
            $transactions[] = $this->txTransfer($bca, $cash, 400000,
                "$monthYear-16", $admin, "Tarik tunai tengah bulan");

            // --- TRANSFER: BCA → OVO (top up, tiap 2 minggu) ---
            $transactions[] = $this->txTransfer($bca, $ovo, 300000,
                "$monthYear-04", $admin, "Top up OVO awal bulan");
            if ($daysInMonth >= 20) {
                $transactions[] = $this->txTransfer($bca, $ovo, 200000,
                    "$monthYear-18", $admin, "Top up OVO tengah bulan");
            }
        }

        // Simpan semua transaksi dan update saldo wallet
        $walletBalances = [];
        foreach ($wallets as $wallet) {
            $walletBalances[$wallet->id] = 0;
        }

        foreach ($transactions as $data) {
            Transaction::create(array_diff_key($data, ['_bca' => 1, '_cash' => 1, '_ovo' => 1]));

            $type       = $data['type'];
            $amount     = $data['amount'];
            $walletId   = $data['wallet_id'];
            $toWalletId = $data['to_wallet_id'] ?? null;

            if ($type === 'income') {
                $walletBalances[$walletId] = ($walletBalances[$walletId] ?? 0) + $amount;
            } elseif ($type === 'outcome') {
                $walletBalances[$walletId] = ($walletBalances[$walletId] ?? 0) - $amount;
            } elseif ($type === 'transfer') {
                $walletBalances[$walletId]   = ($walletBalances[$walletId] ?? 0) - $amount;
                $walletBalances[$toWalletId] = ($walletBalances[$toWalletId] ?? 0) + $amount;
            }
        }

        foreach ($walletBalances as $walletId => $balance) {
            Wallet::where('id', $walletId)->update(['balance' => $balance]);
        }
    }

    private function tx(
        string $type,
        Wallet $wallet,
        ?Wallet $toWallet,
        Category $category,
        TransactionName $transactionName,
        float $amount,
        string $date,
        User $user,
        string $description = ''
    ): array {
        return [
            'type'                => $type,
            'amount'              => $amount,
            'wallet_id'           => $wallet->id,
            'to_wallet_id'        => $toWallet?->id,
            'transaction_name_id' => $transactionName->id,
            'category_id'         => $category->id,
            'description'         => $description,
            'date'                => $date,
            'user_id'             => $user->id,
            'team_id'             => null,
        ];
    }

    private function txTransfer(Wallet $from, Wallet $to, float $amount, string $date, User $user, string $description = ''): array
    {
        return [
            'type'                => 'transfer',
            'amount'              => $amount,
            'wallet_id'           => $from->id,
            'to_wallet_id'        => $to->id,
            'transaction_name_id' => null,
            'category_id'         => null,
            'description'         => $description,
            'date'                => $date,
            'user_id'             => $user->id,
            'team_id'             => null,
        ];
    }

    private function randomDays(int $daysInMonth, int $count): array
    {
        $days = range(1, $daysInMonth);
        shuffle($days);
        $selected = array_slice($days, 0, min($count, $daysInMonth));
        sort($selected);
        return $selected;
    }
}
