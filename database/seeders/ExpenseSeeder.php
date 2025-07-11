<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); 

        if (!$user) {
            $user = User::factory()->create(); // create one if not found
        }

        $categories = ['Food', 'Transport', 'Bills', 'Health', 'Shopping', 'Education', 'Travel', 'Entertainment', 'Recharge', 'Groceries', 'Rent', 'Utilities', 'Others']
;

        for ($i = 0; $i < 50; $i++) {
            Expense::create([
                'user_id' => $user->id,
                'amount' => fake()->randomFloat(2, 50, 1000), // ₹50 to ₹1000
                'category' => fake()->randomElement($categories),
                'description' => fake()->sentence(),
                'date' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }
    }
}

