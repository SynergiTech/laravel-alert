<?php

namespace SynergiTech\Alert\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use SynergiTech\Alert\ServiceProvider;
use SynergiTech\Alert\Facade;
use BadMethodCallException;
use Alert;

class AlertTest extends BaseTestCase
{
    /**
     * setup testbench
     *
     * @return array<class-string>
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Alert' => Facade::class,
        ];
    }

    /**
     * avoid duplicating the assertions
     */
    private function assertInputMatchesOutput(array $input): void
    {
        $output = session()->pull('alert.sweetalert');
        $this->assertNotNull($output);

        $output = (array) json_decode($output);

        foreach (array_keys($input) as $key) {
            $this->assertSame($input[$key], $output[$key]);
        }

        if (! isset($input['type'])) {
            $this->assertEmpty($output['type']);
        }
    }

    /**
     * tests below
     */
    public function testFunction(): void
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        alert($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testTypeMethodFromFunction(): void
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
            'type' => 'success',
        ];

        alert()->{$input['type']}($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testPropertyMethodFromFunction(): void
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        alert()->text($input['text'])->title($input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testHandleUnexpectedMethodFromFunction(): void
    {
        $this->expectException(BadMethodCallException::class);
        alert()->unknownMethod();
    }

    public function testFacade(): void
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        Alert::message($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testTypeMethodFromFacade(): void
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
            'type' => 'success',
        ];

        Alert::{$input['type']}($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testPropertyMethodFromFacade(): void
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        Alert::text($input['text'])->title($input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testHandleUnexpectedMethodFromFacade(): void
    {
        $this->expectException(BadMethodCallException::class);
        Alert::unknownMethod();
    }
}
