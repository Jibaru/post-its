<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Postit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_group_has_zero_postits_when_it_is_created()
    {
        $user = User::factory()->create();

        $group = Group::factory()->create([
            'created_by_id' => $user->id
        ]);

        $this->assertCount(0, $group->postits);
    }
}
