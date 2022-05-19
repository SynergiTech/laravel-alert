<?php

if (! function_exists('alert')) {
    /**
     * Arrange for an alert message.
     */
    function alert(string $message = null, string $title = ''): \SynergiTech\Alert\Alert
    {
        $notifier = app('synergitech.alert');

        if ($message !== null) {
            return $notifier->message($message, $title);
        }

        return $notifier;
    }
}
