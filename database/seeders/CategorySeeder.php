<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        $categories = [
            ['name' => 'Salary & Income', 'type' => 'income',  'color' => '#16a34a'],
            ['name' => 'Savings',         'type' => 'income',  'color' => '#2563eb'],
            ['name' => 'Food & Dining',   'type' => 'expense', 'color' => '#ea580c'],
            ['name' => 'Transport',       'type' => 'expense', 'color' => '#7c3aed'],
            ['name' => 'Health',          'type' => 'expense', 'color' => '#dc2626'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name'    => $cat['name'],
                'type'    => $cat['type'],
                'color'   => $cat['color'],
            ]);
        }
    }
}