<?php

namespace Ugarit\Ui\Tests\AuthBackend;

use Heritage\Foundation\Auth\ThrottlesLogins as ThrottlesLoginsTrait;
use Orchestra\Testbench\TestCase;
use Heritage\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ThrottleLoginsTest extends TestCase
{
    #[Test]
    #[DataProvider('emailProvider')]
    public function it_can_generate_throttle_key(string $email, string $expectedEmail): void
    {
        $throttle = $this->createStub(ThrottlesLogins::class);
        $throttle->method('username')->willReturn('email');
        $reflection = new \ReflectionClass($throttle);
        $method = $reflection->getMethod('throttleKey');

        if (PHP_VERSION_ID < 80100) {
            $method->setAccessible(true);
        }

        $request = $this->mock(Request::class);
        $request->expects('input')->with('email')->andReturn($email);
        $request->expects('ip')->andReturn('192.168.0.1');

        $this->assertSame($expectedEmail . '|192.168.0.1', $method->invoke($throttle, $request));
    }

    public static function emailProvider(): array
    {
        return [
            'lowercase special characters' => ['ⓣⓔⓢⓣ@ⓛⓐⓡⓐⓥⓔⓛ.ⓒⓞⓜ', 'test@ugarit.com'],
            'uppercase special characters' => ['ⓉⒺⓈⓉ@ⓁⒶⓇⒶⓋⒺⓁ.ⒸⓄⓂ', 'test@ugarit.com'],
            'special character numbers' => ['test⑩⓸③@ugarit.com', 'test1043@ugarit.com'],
            'default email' => ['test@ugarit.com', 'test@ugarit.com'],
        ];
    }
}

class ThrottlesLogins
{
    use ThrottlesLoginsTrait;

    public function username()
    {
        return 'email';
    }
}
