<?php

if (! function_exists('alert')) {
    /**
     * Arrange for an alert message.
     *
     * @param string|null $message
     * @param string      $title
     *
     * @return \SynergiTech\AlertNotifier
     */
    function alert($message = null, $title = '')
    {
        $notifier = app('synergitech.alert');

        if ($message !== null) {
            return $notifier->message($message, $title);
        }

        return $notifier;
    }
}
