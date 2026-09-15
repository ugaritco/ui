<?php

namespace Ugarit\Ui\Tests\AuthBackend;

use Heritage\Foundation\Auth\RegistersUsers;
use Heritage\Foundation\Auth\User;
use Heritage\Foundation\Testing\RefreshDatabase;
use Heritage\Http\Request;
use Heritage\Routing\Pipeline;
use Heritage\Support\Facades\Hash;
use Heritage\Support\Facades\Validator;
use Heritage\Testing\TestResponse;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[WithMigration]
class RegistersUsersTest extends TestCase
{
    use RegistersUsers, RefreshDatabase;

    #[Test]
    public function it_can_register_a_user()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'Taylor Otwell',
            'email' => 'taylor@ugarit.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->register($request);
        })->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => 'Taylor Otwell',
            'email' => 'taylor@ugarit.com',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Heritage\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

     /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = (new User())->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->save();

        return $user;
    }

    /**
     * Handle Request using the following pipeline.
     *
     * @param  \Heritage\Http\Request  $request
     * @param  callable  $callback
     * @return \Heritage\Testing\TestResponse
     */
    protected function handleRequestUsing(Request $request, callable $callback)
    {
        return new TestResponse(
            (new Pipeline($this->app))
                ->send($request)
                ->through([
                    \Heritage\Session\Middleware\StartSession::class,
                ])
                ->then($callback)
        );
    }
}
