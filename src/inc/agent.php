<?php
if (php_sapi_name() === 'cli') {
    return;
}

register_shutdown_function(function () {
	if (session_status() === PHP_SESSION_NONE) {
        throw new \LogicException('Session must be started before using Dora.');
    }

    // Make a copy of the input submitted using Dora form.
    if (isset($_POST['gajus']['dora']['uid'])) {
        $_SESSION['gajus']['dora']['flash'] = $_POST;
    } else if (isset($_GET['gajus']['dora']['uid'])) {
        $_SESSION['gajus']['dora']['flash'] = $_GET;
	// Delete copy on the first page that does not issue a redirect.
	} else if (!array_filter(headers_list(), function ($e) { return strpos($e, 'Location:'); })) {
		unset($_SESSION['gajus']['dora']['flash']);
	}
});