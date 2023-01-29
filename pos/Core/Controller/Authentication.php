<?php

namespace Core\Controller;

use Core\Helpers\Tests;
use Core\Base\Controller;
use Core\Helpers\Helper;
use Core\Model\User;

class Authentication extends Controller
{
        use Tests;
        private $user = null;
        /**
         * make instance of view class that have two argument $data and $view
         *
         * 
         */

        public function render()
        {
                if (!empty($this->view))
                        $this->view();
        }
        /**
         * check if user login or not 
         *
         * 
         */

        function __construct()
        {
                $this->admin_view(false);
                if (isset($_SESSION['user']))
                        //if the user have the permission redirect to dashboeard
                        Helper::redirect('/dashboard');
        }

        /**
         * Displays login form
         *
         * 
         */
        public function login()
        {
                $this->view = 'login';
        }

        /**
         * Login Validation
         *
         * 
         */
        public function validate()
        {

                // we use trait test to check if the username and password not empty
                self::check_if_exists((!empty($_POST['username']) && !empty($_POST['password'])),
                        "Please fillout the required information"
                );

                // make instance of class user
                $user = new User();

                // we use method check_username to check if i have username in database and will retrun the username ifo
                $logged_in_user = $user->check_username($_POST['username']);


                // if i dont have user have the information in database will return false from privous method
                if (!$logged_in_user) {
                        $this->invalid_redirect();
                }

                // now i have all info of user from database and i want to make check if password equal the input password
                if (!\password_verify($_POST['password'], $logged_in_user->password)) {
                        $this->invalid_redirect();
                }

                // check if the user check the remeber me or not to make cookies
                if (isset($_POST['remember_me'])) {

                        // we set the cookie by name, value, expire
                        \setcookie('user_id', $logged_in_user->id, time() + (86400 * 30));
                }
                //now i set the $_SESSION['user'] because these info i will use it in project
                $_SESSION['user'] = array(
                        'username' => $logged_in_user->username,
                        'display_name' => $logged_in_user->display_name,
                        'user_id' => $logged_in_user->id,
                        'role' => $logged_in_user->role,
                        'image' => $logged_in_user->image,
                        'is_admin_view' => true


                );

                //after the validation finish i want to redirect every user depend on his role
                if ($_SESSION['user']['role'] == 'admin') {
                        Helper::redirect('/dashboard');
                } elseif ($_SESSION['user']['role'] == 'seller') {
                        Helper::redirect('/sales');
                } elseif ($_SESSION['user']['role'] == 'accountant') {
                        Helper::redirect('/sales/all_transactions');
                } elseif ($_SESSION['user']['role'] == 'porcurement') {
                        Helper::redirect('/items');
                }
        }
        /**
         * logout 
         *
         * 
         */

        public function logout()
        {
                \session_destroy();
                \session_unset();
                \setcookie('user_id', '', time() - 3600);
                Helper::redirect('/');
        }
        /**
         * invalid username or password redirect  
         *
         * 
         */

        //if the username not in database we need to direct him to login page
        private function invalid_redirect()
        {
                $_SESSION['error'] = "Invalid Username or Password";
                Helper::redirect('/');
                exit();
        }
}
