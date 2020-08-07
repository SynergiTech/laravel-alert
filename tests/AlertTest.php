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
     */

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Alert' => Facade::class,
        ];
    }

    /**
     * avoid duplicating the assertions
     */

    private function assertInputMatchesOutput(array $input)
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

    public function testFunction()
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        alert($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testTypeMethodFromFunction()
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
            'type' => 'success',
        ];

        alert()->{$input['type']}($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testPropertyMethodFromFunction()
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        alert()->text($input['text'])->title($input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testHandleUnexpectedMethodFromFunction()
    {
        $this->expectException(BadMethodCallException::class);
        alert()->unknownMethod();
    }

    public function testFacade()
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        Alert::message($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testTypeMethodFromFacade()
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
            'type' => 'success',
        ];

        Alert::{$input['type']}($input['text'], $input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testPropertyMethodFromFacade()
    {
        $input = [
            'text' => 'this is a message',
            'title' => 'title',
        ];

        Alert::text($input['text'])->title($input['title']);

        $this->assertInputMatchesOutput($input);
    }

    public function testHandleUnexpectedMethodFromFacade()
    {
        $this->expectException(BadMethodCallException::class);
        Alert::unknownMethod();
    }
}
