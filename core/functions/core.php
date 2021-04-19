<?php

    /**
     *
     */
    spl_autoload_register(function ($classname) {
        $parts = explode('\\', $classname);
        $filename = array_pop($parts);
        $directory = implode('/', $parts);
        try
        {
            include_once BASE_PATH . "/" . strtolower($directory) . "/" . $filename . '.php';
        }
        catch(\Exception $e)
        {
            try
            {
                include_once APPLICATION_PATH . "/3rdparty/" . strtolower($directory) . "/" . $filename . '.php';
            }
            catch(\Exception $e)
            {
                // TODO
            }
        }
    });

    /**
     *
     */
    foreach (glob(CORE_PATH . "/functions/*.php") as $filename)
    {
        include_once $filename;
    }

    /**
     *
     */
    foreach (glob(APPLICATION_PATH . "/functions/*.php") as $filename)
    {
        include_once $filename;
    }