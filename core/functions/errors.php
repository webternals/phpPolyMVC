<?php

    use Core\Constants\HttpStatusCode as HttpStatusCode;
    /**
     *
     */
    function show_error($code=500, $message=null)
    {

        $routing = array(
            'controller' => 'Error',
            'action' => 'error_'.$code
        );

        try{
            include_once APPLICATION_PATH . "/controllers/Error.php";
        } catch (ErrorException $ex) {
            \Core\Errors::http_response_code($code);
            die();
        }

        $controller = "\\" . APPLICATION_NAMESPACE ."\\Controllers\\{$routing['controller']}";
        $instance = new $controller();

        if ( method_exists($controller, $routing['action']) || method_exists($controller, 'error') ) // Individual methods
        {
            \Core\Errors::http_response_code($code);
            if (isset($routing['params']))
            {
                if ( method_exists($controller, $routing['action']) )
                {
                    call_user_func(array($instance, $routing['action']), $routing['params']);
                }
                else
                {
                    call_user_func(array($instance, 'error'), $routing['params']);
                }
            }
            else
            {
                if ( method_exists($controller, $routing['action']) )
                {
                    call_user_func(array($instance, $routing['action']));
                }
                else
                {
                    if (is_null($message))
                    {
                        $message = \Core\Errors::get_response_message($code);
                    }
                    call_user_func(array($instance, 'error'), $code, $message);
                }
            }
        }
        else
        {
            \Core\Errors::http_response_code($code);
        }
        die();
    }