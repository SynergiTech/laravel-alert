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

        if (! isset($input['icon'])) {
            $this->assertEmpty($output['icon']);
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
            'icon' => 'success',
        ];

        alert()->{$input['icon']}($input['text'], $input['title']);

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
            'icon' => 'success',
        ];

        Alert::{$input['icon']}($input['text'], $input['title']);

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

    public function testOutputs(): void
    {
        $ogConfig = config('alert');

        // add a second output type to config
        $newConfig = $ogConfig;
        $newConfig['output']['toast'] = $newConfig['output']['sweetalert'];
        config(['alert' => $newConfig]);

        alert()->info('hello');

        // assert both output types from config were used simultaneously

        $output = session()->pull('alert.sweetalert');
        $this->assertNotNull($output);

        $output = session()->pull('alert.toast');
        $this->assertNotNull($output);

        // create both kinds of alerts
        alert()->info('hello');

        // specifically request only a toast
        alert()->as('toast')->info('hello');

        // specific toast should unset the original alert
        $output = session()->pull('alert.sweetalert');
        $this->assertNull($output);

        // confirm toast still exists
        $output = session()->pull('alert.toast');
        $this->assertNotNull($output);

        // repeat using output instead of as

        alert()->info('hello');
        alert()->output('toast')->info('hello');
        $output = session()->pull('alert.sweetalert');
        $this->assertNull($output);
        $output = session()->pull('alert.toast');
        $this->assertNotNull($output);

        // put the config back just in case?
        // config(['alert' => $ogConfig]);
    }

    public function testHandleUnexpectedMethodFromFacade(): void
    {
        $this->expectException(BadMethodCallException::class);
        Alert::unknownMethod();
    }

    public function testCustomConfig(): void
    {
        config([
            'alert.output.custom' => [
                'alpha' => 'text',
                'beta' => 'title',
                'delta' => 'type',
            ],
        ]);

        $input = [
            'text' => 'this is a message',
            'title' => 'title',
            'type' => 'success',
        ];

        Alert::{$input['type']}($input['text'], $input['title']);

        $output = session()->pull('alert.custom');
        $this->assertNotNull($output);

        $output = (array) json_decode($output);

        $this->assertSame('this is a message', $output['alpha']);
        $this->assertSame('title', $output['beta']);
        $this->assertSame('success', $output['delta']);
    }
}
