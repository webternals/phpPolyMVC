<?php
    namespace Application\Controllers;
    if (!defined('PHP_FRAMEWORK')) header("Location: /");

    use Core\Controller;

    /**
     * Class Root
     * @package Application\Controllers
     */
    class Root extends Controller
    {
        /**
         * Root constructor.
         */
        public function __construct()
        {
            parent::__construct();
        }

        /**
         *
         */
        public function index()
        {
	        // If the user is logged in they will land here so just push them off to the dashboard
	        echo "Hello World!";
        }
    }
