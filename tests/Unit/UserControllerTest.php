<?php

namespace Tests\Unit;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /** @test */
    public function a_status_201_is_returned_when_register_a_new_user()
    {
        $mockRepository = $this->mock(UserRepositoryInterface::class, function ($mock)
        {
            $mock->shouldReceive('register')->once()->andReturn([]);
        });

        $request = Request::create('/user', 'POST', [
            'username' => 'someone',
            'first_name' => 'someone firstname',
            'last_name' => 'someone lastname',
            'email' => 'someemail@email.com',
            'password' => 'somepassword2002'
        ]);

        // Note: If you check an editor error, the problem was in the intellisense
        $controller = new UserController($mockRepository);

        $response = $controller->store($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
