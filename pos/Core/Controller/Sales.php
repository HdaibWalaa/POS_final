<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Helpers\Helper;
use Core\Model\Item;
use Core\Model\Transaction;
use Core\Helpers\Tests;

class Sales extends Controller
{
        use Tests;
        /**
         * make instance of class view
         *
         * 
         */
        public function render()
        {
                if (!empty($this->view))
                        $this->view();
        }
        /**
         * check if user login or not and if admin will show him specific view
         *
         * 
         */

        function __construct()
        {
                $this->auth();
                $this->admin_view(true);
        }
        /**
         * Gets all items
         *
         * @return array
         */

        public function index()
        {
                //the user can see this page just if have transaction:create permission
                $this->permissions(['transaction:create']);

                //make instance of model Item to have info that i need from database
                $item = new Item;

                // here we use the Test to check if i have all items from database or not
                self::check_if_empty($item->get_all());

                // now i put all items form db in variable data to show it in the privous html script
                $this->data['items'] = $item->get_all();

                //i need user_id to put it in transaction form to use it later
                $this->data['id'] = $_SESSION['user']['user_id'];

                // include file index in folder sales that have form of transaction 
                $this->view = 'sales.index';
        }
        /**
         * Gets specific transaction
         *
         * @return array
         */
        public function single()
        {
                //the user can see this page just if have transaction:read permission
                $this->permissions(['transaction:read']);

                // we use test to check if i have id in get or not
                self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

                // indicate the html page that i want it 
                $this->view = 'sales.single';

                // make instanse form Transaction model
                $transaction = new Transaction();

                // put the information of transaction that i need in variable by using method get_by_id();
                $this->data['transaction'] = $transaction->get_by_id($_GET['id']);
        }
        /**
         * Gets all transactions
         *
         * @return array
         */

        public function all_transactions()
        {
                //the user can see this page just if have transaction:read permission
                $this->permissions(['transaction:read']);

                //make instance of model Transaction to have info that i need from database 
                $transaction = new Transaction;

                // here we use the Test to check if i have all transactions from database or not
                self::check_if_empty($transaction->get_all());

                // now i put all transactions form db in variable data to show it in the privous html script
                $this->data['transactions'] = $transaction->get_all();

                // counting number of data that come from database
                $this->data['transaction_count'] = count($transaction->get_all());

                // include file all_transaction in folder sales that have all items
                $this->view = 'sales.all_transactions';
        }
        /**
         * Display the HTML form for transaction update
         *
         * 
         */

        public function edit()
        {
                //the user can see this page just if have 'transaction:read', 'transaction:update' permission
                $this->permissions(['transaction:read', 'transaction:update']);

                //protect the $_POST from xss attacks
                $this->htmlspecial($_POST);


                // we use test to check if i have id in get or not
                self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

                //that will includ the html that i want to see
                $this->view = 'sales.edit';

                //make instance of model Transaction to have info that i need from database 
                $transaction = new Transaction();

                // put the information of transaction that i need in variable by using method get_by_id();
                $this->data['transaction'] = $transaction->get_by_id($_GET['id']);
        }

        /**
         * Updates the transaction
         *
         * 
         */

        public function update()
        {
                $this->permissions(['transaction:read', 'transaction:update']);
                $transaction = new Transaction();
                $transaction->update($_POST);
                $newTotal = ($_POST['quantity'] * $_POST['price']);
                Helper::redirect('/sales/single?id=' . $_POST['id']);
        }

        /**
         * Delete the transaction
         *
         * 
         */


        public function delete()
        {
                //the user can see this page just if have 'transaction:read', 'transaction:delete' permission
                $this->permissions(['transaction:read', 'transaction:delete']);

                // we use test to check if i have id in get or not
                self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

                //make instance of model Transaction to have info that i need from database
                $transaction = new Transaction();

                //i put varible for id that come from get
                $id = $_GET['id'];

                // prepare the sql statement
                $stmt = $transaction->connection->prepare("DELETE FROM users_transactions WHERE transaction_id=?");

                // bind the params per data type
                $stmt->bind_param('i', $id);

                // execute the statement on the DB
                $stmt->execute();

                // get the result of the execution
                $result = $stmt->get_result();
                $stmt->close();

                //delete the transaction from database
                $transaction->delete($id);

                //redirect the user to all transaction page
                Helper::redirect('/sales/all_transactions');
        }
}
