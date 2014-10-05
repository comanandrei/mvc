<?php

namespace Cool\system\core;

/**
 * Class Input
 * @package Cool\system\core
 */
class Input {

    private $controller;
    private $function;
    private $url = '';

    var $get_vars  = array();
    var $post_vars = array();
    var $functionParam = array();

    var $vars = array();

    function __construct($config)
    {
        $this->raw_uri = $this->_get_raw_url();
        $this->script_uri = $this->_get_raw_script();

        $this->method = $this->_get_method();

        $this->script = basename($this->script_uri);
        $this->base_url = dirname($this->script_uri);

        $this->_parse_uri();
        $this->_parse_routes($config['routes']);

        if ($this->method == 'POST') {
            $this->post_vars = $this->_get_post_vars();
        }

        $this->vars = array_merge($this->get_vars, $this->post_vars);

    }

    /**
     * @return array
     * Return the post var
     */
    private function _get_post_vars()
    {
        return $_POST;
    }

    /**
     * @param array $routes
     * Application routing
     */
    private function _parse_routes($routes)
    {
        if (empty($routes)) {
            return;
        }

        foreach ($routes as $key => $val)
        {
            $key = str_replace(':string', '(.+)', str_replace(':number', '[0-9]+', $key));

            if (preg_match('#^'.$key.'$#', $this->url))
            {

                if (strpos($val, '$') !== false && strpos($key, '(') !== false)
                {
                    $this->url = preg_replace('#^'.$key.'$#', $val, $this->url);

                }
            }
        }

        $this->_parse_args();
    }

    /**
     * Parses the args to determine the controller, function and function's parameter(s);
     */
    private function _parse_args()
    {
        $segments = explode('/', $this->url);

        if (isset($segments[0])) {
            $this->controller = $segments[0];
            unset($segments[0]);
        }
        if (isset($segments[1])) {
            $this->function = $segments[1];
            unset($segments[1]);
        }

        $this->functionParam = array_values($segments);
    }


    /**
     * Gets the url without the scrip file and arguments
     */
    private function _parse_uri()
    {

        if (strpos($this->raw_uri, $this->script_uri) === 0) {
            $this->url = substr($this->raw_uri, 0, strlen($this->script_uri));
        } elseif (strpos($this->raw_uri, $this->base_url) === 0) {
            $this->url = substr($this->raw_uri, strlen($this->base_url));
        }


        $vars = preg_split('/\?/', $this->url, 2);

        $this->url = ltrim(rtrim($vars[0], '/'), '/');


        if (isset($vars[1])) {

            $this->_parse_get_vars($vars[1]);
        }

    }


    /**
     * @param array $vars
     * Creates an array() from get vars;
     */
    private function _parse_get_vars($vars)
    {
        foreach (explode('&', $vars) as $key_val) {

            $split = explode('=', $key_val, 2);

            /** if is not a get_var and after "=" is blank */
            if(isset($split[1])){
                $this->get_vars[$split[0]] = $split[1];
            }

        }
    }


    /**
     * @return string
     * The raw URl as from $_SERVER['REQUEST_URI'];
     */
    private function _get_raw_url()
    {
        return $_SERVER['REQUEST_URI'];
    }


    /**
     * @return string
     * Returns the script file as from  $_SERVER['SCRIPT_NAME'];
     */
    private function _get_raw_script()
    {
        return $_SERVER['SCRIPT_NAME'];
    }

    /**
     * @return mixed
     * Get controller
     */
    public function getController()
    {
        return $this->controller;
    }


    /**
     * @return string
     * Return the request method as from $_SERVER['REQUEST_METHOD'];
     */
    private function _get_method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return mixed
     * Get function
     */
    public function getFunction()
    {
        return $this->function;
    }

    public function __toString()
    {
        return 'Input Core';
    }

    public function __call($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling object method '$name' "
            . implode(', ', $arguments). "\n";
    }

    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling static method '$name' "
            . implode(', ', $arguments). "\n";
    }

    function __invoke()
    {
        echo "Invoking Input() Class";
        return $this;
    }
}