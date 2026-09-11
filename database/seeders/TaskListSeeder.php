<?php

namespace Database\Seeders;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budi = User::where('email', 'budi@jara.com')->first();
        $siti = User::where('email', 'siti@jara.com')->first();
        $andi = User::where('email', 'andi@jara.com')->first();

        if (! $budi || ! $siti || ! $andi) {
            return;
        }

        // List 1: Website Project (Owned by Budi, shared with Siti and Andi)
        $list1 = TaskList::firstOrCreate(
            ['name' => 'Project Website Redesign', 'user_id' => $budi->id],
            ['description' => 'Revamping the company corporate website with modern UI and high performance.']
        );
        $list1->members()->syncWithoutDetaching([$siti->id, $andi->id]);

        // List 2: Mobile App Development (Owned by Siti, shared with Budi)
        $list2 = TaskList::firstOrCreate(
            ['name' => 'Mobile App Development', 'user_id' => $siti->id],
            ['description' => 'Building cross-platform mobile application for Android and iOS clients.']
        );
        $list2->members()->syncWithoutDetaching([$budi->id]);

        // List 3: Cloud Infrastructure (Owned by Andi, shared with Budi)
        $list3 = TaskList::firstOrCreate(
            ['name' => 'Cloud Infrastructure & DevOps', 'user_id' => $andi->id],
            ['description' => 'Setting up CI/CD pipelines, Docker containers, and cloud monitoring tools.']
        );
        $list3->members()->syncWithoutDetaching([$budi->id]);

        // List 4: Personal Study (Owned by Budi, personal)
        TaskList::firstOrCreate(
            ['name' => 'Personal Professional Goals', 'user_id' => $budi->id],
            ['description' => 'Personal learning roadmap for advanced backend architecture and design patterns.']
        );
    }
}
