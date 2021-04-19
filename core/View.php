<?php
    namespace Core;

    /**
     * Class View
     * @package Core
     */
    class View
    {
        /**
         * View constructor.
         */
        public function __construct()
        {

        }

        /**
         * @param $view
         * @param $parameters
         * @param $return
         * @return false|string|null
         */
        public function render($view='index.html', $parameters=array(), $return=FALSE)
        {
            $output = NULL;
            $file   = APPLICATION_PATH . "/views/" . $view;
            if (file_exists($file))
            {
                // Extract the variables to a local namespace
                extract($parameters);

                // Start output buffering
                ob_start();

                // Include the template file
                include $file;

                // End buffering and return its contents
                $output = ob_get_clean();
                if ($return)
                {
                    return $output;
                }
                echo $output;
                return TRUE;
            }
            else
            {
                show_error(404);
            }
            return FALSE;
        }
    }