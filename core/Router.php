<?php

    namespace Core;

    /**
     * Class Router
     */
    class Router
    {
        /**
         * Router constructor.
         */
        public function __construct()
        {

        }

        /**
         * @param $url
         * @return array|string[]
         */
        private function _router($url)
        {
            // Trim off the $_GET variables and use just the first part
            $url = explode('?',$url)[0];
            // root url
            if ('/' == $url) {
                return array(
                    'controller' => DEFAULT_CONTROLLER,
                    'action' => 'index'
                );
            }

            // If we didn't match the first one we need to trim off the trailing /
            $url = rtrim(explode('?',$url)[0], '/');

            // generic urls
            //   /{controllername}
            //   /{controllername}/{method}/{paramname}
            if (preg_match('#^/([^/]+)$#', $url, $matches)) {
                return array(
                    'controller' => ucfirst(strtolower($matches[1])),
                    'action' => 'index'
                );
            }
            else if (preg_match('#^/([^/]+)/([^/]+)/([^\?]+)(.+)?$#', $url, $matches)) {
                return array(
                    'controller' => ucfirst(strtolower($matches[1])),
                    'action' => strtolower($matches[2]),
                    'params' => explode("/", trim($matches[3],'/')),
                );
            }
            else if (preg_match('#^/([^/]+)/([^/]+)$#', $url, $matches)) {
                return array(
                    'controller' => ucfirst(strtolower($matches[1])),
                    'action' => strtolower($matches[2])
                );
            }

            // if nothing else matches, then 404
            return array(
                'controller' => 'Error',
                'action' => 'error_404'
            );
        }

        /**
         *
         */
        public function route()
        {
            $routing = $this->_router(str_replace( '/'. strtolower(APPLICATION_NAMESPACE), '', $_SERVER['REQUEST_URI']));
            try {
                // Do the routing
                include_once APPLICATION_PATH . "/controllers/{$routing['controller']}.php";
            } catch (\ErrorException $ex) {
                try{
                    $routing = array(
                        'controller' => 'Error',
                        'action' => 'error_404'
                    );
                    include_once APPLICATION_PATH . "/controllers/Error.php";
                } catch (\ErrorException $ex) {
                    show_error(404);
                }
            }


            $controller = "\\" . APPLICATION_NAMESPACE . "\\Controllers\\{$routing['controller']}";

            $instance = new $controller();

            // We reserve _<method_name> for protected hidden internal functions.
            if ( method_exists($controller, $routing['action']) && !( 0 === strpos($routing['action'], '_') ))
            {
                if (isset($routing['params']))
                {
                    call_user_func_array(array($instance, $routing['action']), $routing['params']);
                }
                else
                {
                    call_user_func(array($instance, $routing['action']));
                }
            }
            else
            {
                show_error(404);
            }
        }
    }