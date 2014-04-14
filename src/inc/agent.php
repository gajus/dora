<?php
if (php_sapi_name() === 'cli') {
    return;
}

register_shutdown_function(function () {
    if (session_status() === \PHP_SESSION_NONE) {
        throw new \LogicException('Session must be started before using Dora.');
    }

    // Make a copy of the input submitted using Dora form.
    if (isset($_POST['gajus']['dora']['uid'])) {
        // It might be that $_POST have been modified during the execution of the script.
        parse_str(file_get_contents('php://input'), $input);

        $_SESSION['gajus']['dora']['flash'][$_POST['gajus']['dora']['uid']] = $input;
    } else if (!array_filter(headers_list(), function ($e) { return strpos($e, 'Location:'); })) {
        unset($_SESSION['gajus']['dora']['flash']);
    }
});