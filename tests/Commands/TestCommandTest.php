<?php

namespace Facade\Ignition\Tests\Commands;

use Facade\Ignition\Tests\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class TestCommandTest extends TestCase
{
    protected $withFlareKey = false;

    protected function getEnvironmentSetUp($app)
    {
        if ($this->withFlareKey) {
            $app['config']['flare.key'] = 'some-key';
        }
    }

    public function withFlareKey()
    {
        $this->withFlareKey = true;

        $this->refreshApplication();
    }

    /** @test */
    public function it_can_not_find_the_test_command_without_a_flare_key()
    {
        $this->expectException(CommandNotFoundException::class);
        $testResult = $this->artisan('flare:test');
    }

    /** @test */
    public function it_can_execute_the_test_command_when_a_flare_key_is_present()
    {
        $this->withFlareKey();

        $testResult = $this->artisan('flare:test');

        is_int($testResult)
            ? $this->assertSame(0, $testResult)
            : $testResult->assertExitCode(0);
    }
}
