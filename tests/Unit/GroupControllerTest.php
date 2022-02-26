<?php

namespace Tests\Unit;

use App\Contracts\Repositories\GroupRepositoryInterface;
use App\Http\Controllers\GroupController;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;
use Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_status_201_is_returned_when_create_a_new_user()
    {
        $mockRepository = $this->mock(GroupRepositoryInterface::class, function ($mock)
        {
            $mock->shouldReceive('saveGroup')->once()->andReturn([]);
        });

        $request = Request::create('/group', 'POST', [
            'name' => 'some name',
            'description' => 'some description'
        ]);

        $user = User::factory()->create();

        Auth::shouldReceive('user')->once()->andReturn($user);

        // Note: If you check an editor error, the problem was in the intellisense
        $controller = new GroupController($mockRepository);

        $response = $controller->store($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /** @test */
    public function an_unauthenticated_user_can_not_create_a_group()
    {
        $mockRepository = $this->mock(GroupRepositoryInterface::class);

        $request = Request::create('/group', 'POST', [
            'name' => 'some name',
            'description' => 'some description'
        ]);

        Auth::shouldReceive('user')->once()->andReturn(null);

        // Note: If you see an editor error, the problem is in the intellisense
        $controller = new GroupController($mockRepository);

        $response = $controller->store($request);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /** @test */
    public function a_status_200_is_returned_when_all_groups_have_been_got()
    {
        $mockRepository = $this->mock(GroupRepositoryInterface::class, function ($mock)
        {
            $mock->shouldReceive('getAllGroups')->once()->andReturn([]);
        });

        $request = Request::create('/groups', 'GET');

        // Note: If you check an editor error, the problem was in the intellisense
        $controller = new GroupController($mockRepository);

        $response = $controller->index($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /** @test */
    public function a_status_200_is_returned_when_a_group_is_deleted()
    {
        $mockRepository = $this->mock(GroupRepositoryInterface::class, function ($mock)
        {
            $mock->shouldReceive('deleteGroup')->once()->andReturn(true);
        });

        $user = User::factory()->create();

        $group = Group::factory()->create([
            'created_by_id' => $user->id
        ]);

        // Note: If you check an editor error, the problem was in the intellisense
        $controller = new GroupController($mockRepository);

        $response = $controller->destroy($group->id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
