<?php

namespace SynergiTech\Alert;

use Illuminate\Session\Store;
use BadMethodCallException;

class Alert
{
    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Configuration options
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new Alert instance
     *
     * @param \Illuminate\Session\Store $session interface with Laravels session
     */
    public function __construct(Store $session)
    {
        $this->reset();

        $this->session = $session;
    }

    /**
     * Sets all default fields options
     *
     * @return void
     */
    public function reset(): void
    {
        $this->fields = config('alert.fields');
    }

    /**
     * Display an alert message with a text and an optional title.
     *
     * By default the alert is not typed.
     *
     * @param string $text
     * @param string $type
     * @param string $title
     *
     * @return \SynergiTech\Alert\Alert $this
     */
    public function message($text, $title = '', $type = null): Alert
    {
        if (array_key_exists('text', $this->fields)) {
            $this->fields['text'] = $text;
        }

        if (array_key_exists('title', $this->fields)) {
            $this->fields['title'] = $title;
        }

        if (array_key_exists('type', $this->fields) && $type !== null) {
            $this->fields['type'] = $type;
        }

        $this->putConfig();

        return $this;
    }

    /**
     * Handle setting values via a method named after the alert type or a method named after the value
     *
     * @param string $name the called method
     * @param array $args the arguments called on the method
     *
     * @return \SynergiTech\Alert\Alert $this
     */
    public function __call(string $name, array $args): Alert
    {
        if (array_key_exists($name, $this->fields)) {
            $this->fields[$name] = (count($args) == 1) ? reset($args) : $args;

            $this->putConfig();
        } elseif (in_array($name, config('alert.types', []))) {
            $text = $args[0] ?? '';
            $title = (count($args) == 2) ? $args[1] : '';

            $this->message($text, $title, $name);
        } else {
            throw new BadMethodCallException('synergitech/alert is unsure what to do with this');
        }

        return $this;
    }

    /**
     * Put the current alert configuration to the session
     *
     * @return void
     */
    private function putConfig(): void
    {
        foreach (config('alert.output', []) as $plugin => $map) {
            $output = [];
            foreach ($map as $from => $to) {
                $output[$from] = $this->fields[$to] ?? '';

                if (
                    $to == 'icon' &&
                    array_key_exists('icon', $this->fields) &&
                    strlen($this->fields['icon']) == 0 &&
                    array_key_exists('type', $this->fields) &&
                    array_key_exists($this->fields['type'], config('alert.icons_by_type', []))
                ) {
                    $output[$from] = config('alert.icons_by_type')[$output['type']];
                }
            }

            $this->session->put("alert.$plugin", json_encode($output));
        }
    }
}
