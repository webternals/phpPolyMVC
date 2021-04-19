<?php
    namespace Bootstrap;

    global $gRouter;

    define('BASE_PATH', __DIR__);
    define('APPLICATION_PATH', BASE_PATH . '/'. strtolower(APPLICATION_NAMESPACE));
    define('CORE_PATH', BASE_PATH . "/core");
    define('DEFAULT_CONTROLLER', "Root"); // Must be capitalized

    include_once CORE_PATH . "/functions/core.php";

    set_error_handler("exception_error_handler");

    $gRouter = new \Core\Router();
    $gRouter->route();
