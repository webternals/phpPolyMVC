<?php

    namespace Core;

    /**
     * Class Controller
     * @package Core
     */
    class Controller
    {
        /**
         * Controller constructor.
         */
        public function __construct()
        {

        }

        /**
         * @param $view
         * @param array $parameters
         * @param false $return
         * @return bool|string|null
         */
        protected function _render($view, $parameters=array(), $return=FALSE)
        {
            $model = new View();
            return $model->render($view, $parameters, $return);
        }
    }