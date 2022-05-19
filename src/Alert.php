<?php

namespace SynergiTech\Alert;

use Illuminate\Session\Store;
use BadMethodCallException;

/**
 * For static analysis (Larastan) support, here are the
 * available methods as per the default config.
 *
 * If you use a different configuration, you will need to
 * either extend this class to update this docblock
 * or add ignoreErrors lines to your larastan.neon
 *
 * Set multiple alert properties
 * @method Alert info(string $text, string $title = '')
 * @method Alert success(string $text, string $title = '')
 * @method Alert error(string $text, string $title = '')
 * @method Alert warning(string $text, string $title = '')
 *
 * Set specific alert properties
 * @method Alert title(string $value)
 * @method Alert text(string $value)
 * @method Alert type(string $value)
 */

class Alert
{
    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * @var array<string, mixed>
     */
    protected $fields;

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
     * @param array<string> $args the arguments called on the method
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
            }

            $this->session->put("alert.$plugin", json_encode($output));
        }
    }
}
