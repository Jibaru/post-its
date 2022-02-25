<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Postit;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::all()->last();
        $groups = Group::all();

        foreach($groups as $group) 
        {
            Postit::factory()->count(3)->create([
                'group_id' => $group->id,
                'user_id' => $user->id
            ]);
        }
    }
}
