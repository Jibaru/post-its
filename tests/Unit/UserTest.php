<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_new_user_should_has_an_email()
    {
        $user = User::factory()->create();

        $this->assertTrue(isset($user->email));
    }

    /** @test */
    public function an_user_has_one_or_many_groups()
    {
        $user = User::factory()->create();
        
        Group::factory()->count(10)->create([
            'created_by_id' => $user->id 
        ]);

        $this->assertDatabaseHas('groups', [
            'created_by_id' => $user->id 
        ]);
    }


}
