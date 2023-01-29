<?php

namespace Core;

// Use statement to include the View class from the Core\Base namespace
use Core\Base\View;

/**
 * Manages the routing process in the application
 */
class Router
{

    // Array to store route information for GET requests
    protected static $get_routes = array();

    // Array to store route information for POST requests
    protected static $post_routes = array();

    // Array to store route information for PUT requests
    protected static $put_routes = array();

    // Array to store route information for DELETE requests
    protected static $delete_routes = array();

    /**
     * redirect:method to handle routing and redirect to appropriate class method
     * 
     */

    public static function redirect(): void
    {

        //take the REQUEST_URI from the superglobal variable $_SERVER 
        $request = $_SERVER['REQUEST_URI'];
        //remove the  any query string
        $request = \explode("?", $request)[0];
        //creates an empty array called $routes
        $routes = array();

        //using switch to fill the $routes array bassed on the REQUEST_METHOD that we take it from $_SERVER
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $routes = self::$get_routes;
                break;
            case 'POST':
                $routes = self::$post_routes;
                break;
            case 'PUT':
                $routes = self::$put_routes;
                break;
            case 'DELETE':
                $routes = self::$delete_routes;
                break;
        }

        //check if the $routes is empty or the request URL is not a key in the array
        if (empty($routes) || !array_key_exists($request, $routes)) {
            //make HTTP response code to 404 
            http_response_code(404);
            //take me(render me)to 404page in the views directory
            new View('404');
            exit;
        }

        //creates a string called $controller_namespace with the value 'Core\Controller\'  and using the scap char to let the \appear 
        $controller_namespace = 'Core\\Controller\\';
        //explode the routes[$request] to get classname and method name
        $class_arr = explode('.', $routes[$request]);
        //make the first index clacc name first letter capital
        $class_name = ucfirst($class_arr[0]);
        //the calass now have the name of the class with thw name space
        $class = $controller_namespace . $class_name;

        // Create new instance of the class
        $instence = new $class;

        // call the method from the class if its exsits
        if (count($class_arr) == 2) {
            call_user_func([$instence, $class_arr[1]]);
        }

        //rendering the class method
        $instence->render();
    }

    /**
     * put the routes and controller in array in get method
     * 
     */
    public static function get($route, $controller): void
    {
        // store the route and controller in the array for get requests
        self::$get_routes[$route] = $controller;
    }
    /**
     * put the routes and controller in array in post method
     * 
     */

    public static function post($route, $controller): void
    {
        // store the route and controller in the array for post requests
        self::$post_routes[$route] = $controller;
    }
    /**
     * put the routes and controller in array in put method
     * 
     */

    public static function put($route, $controller): void
    {
        // store the route and controller in the array for put requests
        self::$put_routes[$route] = $controller;
    }
    /**
     * put the routes and controller in array in delete method
     * 
     */
    public static function delete($route, $controller): void
    {
        // store the route and controller in the array for delet requests
        self::$delete_routes[$route] = $controller;
    }
}
