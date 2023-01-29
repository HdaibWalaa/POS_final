<?php

namespace Core\Base;

use Core\Model\User;
use Core\Helpers\Helper;


abstract class Controller
{
    /**
     * 
     *force all controllers to use render method so it can have view on the browes
     * 
     */
    abstract public function render();


    //we use this two protected prop to render the view in the application
    protected $view = null;
    protected $data = array();

    /**
     * create a new instance of the View class and render the view
     * This method is used to render the view, and it will be executed in the(controller class)
     *
     * 
     */
    protected function view()
    {
        new View($this->view, $this->data);
    }

    /**
     * 
     *Check if a user is logged in, if not redirect them to the login page
     * 
     */
    protected function auth()
    {
        //checking the $_SESSION array for if there user 
        if (!isset($_SESSION['user'])) {
            //redirects the user to the login page by calling the Helper class's 
            Helper::redirect('/');
            exit;
        }
    }

    /**
     * 
     * check if the user has the required permissions to access a certain route(Check if the user has the assigned permissions)
     * permissions_set: the set of permissions required to access the route, page or functionality
     * 
     */
    protected function permissions(array $permissions_set)
    {
        // check if user is logged in, if not redirect them to the login page
        $this->auth();
        //get the user 
        $user = new User;
        //get the user assigned permissions 
        $assigned_permissions = $user->get_permissions();


        foreach ($permissions_set as $permission) {
            // check if user has all the permissions
            if (!in_array($permission, $assigned_permissions)) {
                // check user role if the user has role seller redirect them to the selling dashboard
                if ($_SESSION['user']['role'] == 'seller') {
                    Helper::redirect('/sales');
                }
                // if the user has role accountant redirect them to the transaction dashboard 
                elseif ($_SESSION['user']['role'] == 'accountant') {
                    Helper::redirect('/sales/all_transactions');
                }
                // if the user has role porcurement redirect them to the items dashboard 
                elseif ($_SESSION['user']['role'] == 'porcurement') {
                    Helper::redirect('/items');
                }
            }
        }
    }

    /**
     * Change the header view in the layout.
     *
     *  $switch - whether to show or hide the admin view in the layout.
     * 
     */
    protected function admin_view(bool $switch): void
    {
        // check if is_admin_view key is in the session 
        if (isset($_SESSION['user']['is_admin_view'])) {
            // set the value of the switch to is_admin_view in the session
            $_SESSION['user']['is_admin_view'] = $switch;
        }
    }


    /**
     * make the input from user secured from xss attack
     *
     *  $variable
     *  data from xss attack
     */
    protected function htmlspecial(array &$variable)
    {
        // Loop through each value in the input array
        foreach ($variable as &$value) {
            // Check if the value is an array
            if (is_array($value)) {
                // If the value is an array, call the function recursively
                // to process the nested array
                $this->htmlspecial($value);
            } else {
                // Otherwise, apply htmlspecialchars to the value
                // this will transfer any script to special character 
                $value = \htmlspecialchars($value);
            }
        }
    }
}
