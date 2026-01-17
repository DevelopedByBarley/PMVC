<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Admin;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $admins = Admin::all();
        if ($admins->isEmpty()) {
            return;
        }

        $actions = ['created', 'updated', 'deleted', 'viewed', 'exported'];
        $statuses = ['success', 'failed', 'critical'];
        $categories = ['auth', 'content', 'files', 'gallery', 'blog', 'programs'];

        for ($i = 0; $i < 12; $i++) {
            $admin = $admins->random();
            $createdAt = $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s');

            (new Activity())->create([
                'admin_id' => $admin->id,
                'adminName' => $admin->name ?? $faker->name(),
                'action' => $faker->randomElement($actions),
                'status' => $faker->randomElement($statuses),
                'category' => $faker->randomElement($categories),
                'description' => $faker->sentence(12),
                'target_type' => $faker->boolean(40) ? 'App\\Models\\Blog' : null,
                'target_id' => $faker->boolean(40) ? $faker->numberBetween(1, 50) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
