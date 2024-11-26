<?php

use SynergiTech\Alert\Alert;

if (! function_exists('alert')) {
    /**
     * Arrange for an alert message.
     */
    function alert(?string $message = null, string $title = ''): Alert
    {
        /** @var Alert $notifier */
        $notifier = app('synergitech.alert');

        if ($message !== null) {
            return $notifier->message($message, $title);
        }

        return $notifier;
    }
}
