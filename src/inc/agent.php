<?php
if (php_sapi_name() === 'cli') {
    return;
}

// Make a copy of $_POST before the main script could be have interfered with it.
$input = $_POST;

register_shutdown_function(function () use ($input) {
    if (session_status() === \PHP_SESSION_NONE) {
        throw new \LogicException('Session must be started before using Dora.');
    }

    // Make a copy of the input submitted using Dora form.
    if (isset($input['gajus']['dora']['uid'])) {
        $_SESSION['gajus']['dora']['flash'][$input['gajus']['dora']['uid']] = $input;
    } else if (!array_filter(headers_list(), function ($e) { return mb_strpos(mb_strtolower($e), 'location:') !== false; })) {
        unset($_SESSION['gajus']['dora']['flash']);
    }
});
