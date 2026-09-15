<?php

namespace Ugarit\Ui\Tests\AuthBackend;

use Heritage\Auth\Events\Attempting;
use Heritage\Auth\Events\Logout;
use Heritage\Foundation\Auth\AuthenticatesUsers;
use Heritage\Foundation\Testing\RefreshDatabase;
use Heritage\Http\Request;
use Heritage\Routing\Pipeline;
use Heritage\Support\Facades\Auth;
use Heritage\Support\Facades\Event;
use Heritage\Testing\TestResponse;
use Heritage\Validation\ValidationException;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[WithMigration]
class AuthenticatesUsersTest extends TestCase
{
    use AuthenticatesUsers, RefreshDatabase;

    protected function tearDown(): void
    {
        Auth::logout();

        parent::tearDown();
    }

    #[Test]
    public function it_can_authenticate_a_user()
    {
        Event::fake();

        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);

        Event::assertDispatched(function (Attempting $event) {
            return $event->remember === false;
        });
    }

    #[Test]
    public function it_can_deauthenticate_a_user()
    {
        Event::fake();

        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $request = Request::create('/logout', 'POST', [], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing(
            $request, fn ($request) => $this->logout($request)
        )->assertStatus(204);

        Event::assertDispatched(fn (Logout $event) => $user->is($event->user));
    }

    #[Test]
    public function it_can_authenticate_a_user_with_remember_as_false()
    {
        Event::fake();

        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);

        Event::assertDispatched(function (Attempting $event) {
            return $event->remember === false;
        });
    }

    #[Test]
    public function it_can_authenticate_a_user_with_remember_as_true()
    {
        Event::fake();

        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => true,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);

        Event::assertDispatched(function (Attempting $event) {
            return $event->remember === true;
        });
    }

    #[Test]
    public function it_cant_authenticate_a_user_with_invalid_password()
    {
        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'email' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
    }

    #[Test]
    public function it_cant_authenticate_unknown_credential()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'taylor@ugarit.com',
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'email' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
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
