<?php

namespace Database\Seeders;

use App\Models\Pocket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $pockets = collect([
            ['name' => 'Cash', 'description' => 'Uang tunai'],
            ['name' => 'Bank BCA', 'description' => 'Rekening BCA'],
            ['name' => 'GoPay', 'description' => 'Saldo GoPay'],
        ])->map(fn($p) => Pocket::create([...$p, 'user_id' => $user->id]));

        $descriptions = [
            'income' => ['Gaji', 'Freelance', 'Bonus', 'Transfer masuk', 'Cashback'],
            'expense' => ['Makan siang', 'Bensin', 'Listrik', 'Internet', 'Belanja', 'Kopi', 'Transportasi', 'Pulsa'],
        ];

        foreach ($pockets as $pocket) {
            for ($m = 1; $m <= 12; $m++) {
                // 1-2 income per month
                for ($i = 0; $i < rand(1, 2); $i++) {
                    Transaction::create([
                        'pocket_id' => $pocket->id,
                        'type' => 'income',
                        'amount' => rand(1, 10) * 500000,
                        'description' => $descriptions['income'][array_rand($descriptions['income'])],
                        'date' => now()->setYear(now()->year)->setMonth($m)->setDay(rand(1, 28)),
                    ]);
                }
                // 3-6 expenses per month
                for ($i = 0; $i < rand(3, 6); $i++) {
                    Transaction::create([
                        'pocket_id' => $pocket->id,
                        'type' => 'expense',
                        'amount' => rand(1, 20) * 25000,
                        'description' => $descriptions['expense'][array_rand($descriptions['expense'])],
                        'date' => now()->setYear(now()->year)->setMonth($m)->setDay(rand(1, 28)),
                    ]);
                }
            }
            $pocket->recalculateBalance();
        }
    }
}
