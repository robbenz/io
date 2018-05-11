<?php

class View
{
    protected static $instance;
    protected $viewPath;

    public function __construct($viewPath = '')
    {
        $this->viewPath = $viewPath;
    }

    public static function make($viewPath)
    {
        return static::setInstance(new View($viewPath));
    }

    public static function setInstance($instance)
    {
        static::$instance = $instance;

        return $instance;
    }

    private function render($view, $vars = [])
    {
        ob_start();
        extract($vars);
        require sprintf(
            '%s/%s.php',
            $this->viewPath,
            $view
        );

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([static::$instance, $name], $arguments);
    }
}
