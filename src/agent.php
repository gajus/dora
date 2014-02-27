<?php
register_shutdown_function(function () {
	if (session_status() === PHP_SESSION_NONE) {
        throw new \LogicException('Session must be started before using Dora.');
    }

    // Make a copy of the post data.
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$_SESSION['gajus']['dora']['flash'] = $_POST;

	// Delete copy on the first page that does not issue a redirect.
	} else if (!array_filter(headers_list(), function ($e) { return strpos($e, 'Location:'); })) {
		unset($_SESSION['gajus']['dora']['flash']);
	}
});