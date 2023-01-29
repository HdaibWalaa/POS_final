<?php

namespace Core\Controller;

use Core\Base\Controller;   // import the Controller class
use Core\Helpers\Helper;   // import Helper class
use Core\Model\Transaction; //import Transaction class
use Core\Model\User;       //import User class
use Core\Model\Item;       //import Item class
use Core\Helpers\Tests;    //import Tests class

//Admin class extends Controller
class Admin extends Controller
{
        use Tests; //use trait Tests
        /**
         * make instance of class view
         * and render the view
         *
         * @return void
         */
        public function render()
        {
                //check if view variable is not empty
                if (!empty($this->view))
                        $this->view();
        }
        /**
         * constructor
         *
         * @return void
         */

        function __construct()
        {
                //check authentication
                $this->auth();
                //set the session variable indicating the this is an admin view
                $this->admin_view(true);
        }


        /**
         *
         * @return array
         */

        public function index()
        {
                //Check if user has the permission to read user data
                $this->permissions(['user:read']);

                //The view to be displayed is 'dashboard'
                $this->view = 'dashboard';

                //Create new instance of the Transaction model
                $transaction = new Transaction;

                //Count all the transactions and store it in 'transaction_count' property
                $this->data['transaction_count'] = count($transaction->get_all());

                $total_sales = 0;

                $total_sales_of_transaction = $transaction->get_all();

                foreach ($total_sales_of_transaction as $sales) {

                        $total_sales += $sales->total;
                }

                $this->data['total_sales'] = $total_sales;

                //Create new instance of the Item model
                $item = new Item;

                //Count all the items and store it in 'items_count' property
                $this->data['items_count'] = count($item->get_all());

                //Create new instance of the User model
                $user = new User;

                //Count all the users and store it in 'users_count' property
                $this->data['users_count'] = count($user->get_all());

                //Get the top five items based on selling_price and store it in 'top_five' property
                $topfive = $item->topfive("selling_price", "items", 5);
                self::check_if_empty($topfive);
                $this->data['top_five'] = $topfive;

                //get low quantity items
                $lowstock = $item->lowQuantityItems();
                $this->data['lowQuantity_Items'] = $lowstock;
        }
}
