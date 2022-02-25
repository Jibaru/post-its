<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Postit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostitTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_new_postit_should_has_title_and_description()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create([
            'created_by_id' => $user->id
        ]);

        $postit = Postit::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);

        $this->assertTrue(
            isset($postit->title) &&
            isset($postit->description) 
        );
    }
    
    /** @test */
    public function a_new_postit_should_has_a_user()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create([
            'created_by_id' => $user->id
        ]);

        $postit = Postit::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);

        $this->assertTrue($user->id == $postit->user->id);
    }
}
