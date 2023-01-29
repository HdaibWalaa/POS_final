<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Helpers\Helper;
use Core\Model\Item;
use Core\Model\Transaction;


class Transactions extends Controller
{

        protected $request_body;
        protected $http_code = 200;

        protected $response_schema = array(
                "success" => true,
                "message_code" => "",
                "body" => []
        );
        /*
        *this method will directly make response from method to json object to send it to html page
        * return
        */
        public function render()
        {
                //we should change the content type as json type
                header("Content-Type: application/json");
                //control the http_response conde depend on response
                http_response_code($this->http_code);
                //we should encode the response to be acceptable to see it in html page
                echo json_encode($this->response_schema);
        }
        /** 
         *this method will automatically make request body json_decode to make it php object to use it 
         * return
         */
        function __construct()
        {

                $this->request_body = json_decode(file_get_contents("php://input", true));
        }

        /** 
         *fetch all transactions that meet the conditions
         * @return array
         */
        public function index()
        {
                try {
                        // make instance of transaction model to call with db
                        $transaction = new Transaction;

                        //we will fetch the data that belongs to user from table users_transactions(here we prapare the sql statment)
                        $stmt = $transaction->connection->prepare("SELECT *from users_transactions WHERE user_id=?");

                        // bind the parameter
                        $stmt->bind_param('i', $_SESSION['user']['user_id']);

                        // execute the statement on the DB
                        $stmt->execute();

                        // get the result of the execution 
                        $transaction_query = $stmt->get_result();
                        $stmt->close();

                        //assign variable to put inside the object that i need which is the transaction_id and user_id 
                        $transaction_user = array();

                        //fetche the result 
                        if ($transaction_query->num_rows > 0) {
                                while ($row = $transaction_query->fetch_object()) {
                                        $transaction_user[] = $row;
                                }
                        }

                        //assgin variable to put inside the transactions 
                        $transactions = array();

                        //now i have the id of transactions now i need to fetch all information of transactions in transactions table
                        foreach ($transaction_user as $relation) {

                                //we make the sql statement with condition that created tody
                                $sql = "SELECT *FROM transactions WHERE id= {$relation->transaction_id} AND created_at >= CURDATE()";
                                $transaction_query_conditions = $transaction->connection->query($sql);
                                if ($transaction_query_conditions->num_rows > 0) {
                                        while ($row = $transaction_query_conditions->fetch_object()) {
                                                $transactions[] = $row;
                                        }
                                }
                        }

                        //we use try and catch to handle errors 
                        if (empty($transactions)) {
                                throw new \Exception('No transactions were found!');
                        }

                        //reassign the response_schema['body'] to the data i need
                        $this->response_schema['body'] = $transactions;

                        //reassgin the response_schema['message_code'] with massage i need
                        $this->response_schema['message_code'] = "transactions_collected_successfuly";
                } catch (\Exception $error) {
                        //if i have error will return false and massage code of error
                        $this->response_schema['success'] = false;
                        $this->response_schema['message_code'] = $error->getMessage();
                        $this->http_code = 404;
                }
        }

        /**
         * create new transaction
         * @return array
         */

        public function create()
        {

                try {
                        if (($this->request_body->quantity) <= "0") {
                                $_SESSION['error'] = "you should Enter quantity more than 0";
                                return;
                        }
                        // make new instance of item to call with database
                        $item = new Item;

                        //this variable will have the item that choosen in form 
                        $selected_item = $item->get_by_id($this->request_body->items_id);

                        //check if selected item empty or not
                        if (empty($selected_item)) {
                                throw new \Exception('No item was found!');
                        }

                        //make new instance of trnasaction model
                        $transaction = new Transaction;

                        //assign value for total by multiply the quantity of request body and price of selected item
                        $total = ($this->request_body->quantity) * ($selected_item->selling_price);

                        //assign value of expenses by multiply quantity and cost
                        $expenses = ($this->request_body->quantity) * ($selected_item->cost);

                        //assgin value of column of transaction 
                        $value = array(
                                "items_id" => $this->request_body->items_id,
                                "item_name" => $selected_item->item_name,
                                "quantity" => $this->request_body->quantity,
                                "price" => $selected_item->selling_price,
                                "cost" => $selected_item->cost,
                                "expenses" => $expenses,
                                "total" => $total
                        );

                        $request_item_id = $this->request_body->items_id;
                        //if stock more than the request body quantity
                        if ($selected_item->quantity - $this->request_body->quantity >= '0') {

                                //we need to change the quantity of stock depend on the quantity i put it
                                $selected_item->quantity = $selected_item->quantity - $this->request_body->quantity;
                                $new_quantity = $selected_item->quantity;

                                //update the quantity of stock
                                //prepare the sql statment
                                $stmt = $transaction->connection->prepare("UPDATE items SET quantity =?  WHERE id=?");

                                //bind the param
                                $stmt->bind_param('ii', $new_quantity, $request_item_id);

                                //execute the statement
                                $stmt->execute();

                                // get the result of the execution
                                $result = $stmt->get_result();

                                $stmt->close();
                        } else {
                                //if the request quantity more than request
                                $_SESSION['error'] = "there is no enough quantity in stock";

                                die;
                        }

                        //now we create the transaction on the db
                        $transaction->create($value);
                        if (empty($transaction)) {
                                throw new \Exception('No item was creat!');
                        }

                        //we need to fetch the last transaction to put it in front when create the transacstion
                        $transaction_id = $transaction->connection->insert_id;

                        //we should to insert the user id in users_transaction table
                        $user_id = $this->request_body->user_id;

                        //reassign the  $this->response_schema['body']
                        $this->response_schema['body'] = $transaction->get_by_id($transaction_id);
                        $this->response_schema['message_code'] = "transaction_collected_successfuly";

                        //prepare sql statement to insert the user_id and transaction_id in users_transactions table
                        $stmt2 = $transaction->connection->prepare("INSERT INTO users_transactions (user_id, transaction_id) VALUES (?,?)");

                        //bind the parameter
                        $stmt2->bind_param('ii', $user_id, $transaction_id);

                        //execute the statement
                        $stmt2->execute();

                        // get the result of the execution
                        $result2 = $stmt2->get_result();
                        $stmt2->close();
                } catch (\Exception $error) {
                        //if there is an error in previous if statement
                        $this->response_schema['success'] = false;
                        $this->response_schema['message_code'] = $error->getMessage();
                        $this->http_code = 404;
                }
        }

        /**
         * delete the transaction
         * 
         */
        public function delete()
        {

                // check if there is id in request body or not
                try {
                        if (!isset($this->request_body->id)) {
                                $this->http_code = 422;
                                throw new \Exception('id_param_not_found');
                        }

                        //make instance of transaction model 
                        $transaction = new Transaction;

                        //select the transaction we want to delete it
                        $transaction_id = $this->request_body->id;
                        $transaction_all = $transaction->get_by_id($transaction_id);

                        //we need to change the quantity of stock
                        $deleted_quantity = $transaction_all->quantity;
                        $item_id = $transaction_all->items_id;
                        $item = new Item;
                        $item_update = $item->get_by_id($item_id);
                        $item_old_quantity = $item_update->quantity;
                        $new_quantity = $item_old_quantity + $deleted_quantity;

                        //update the quantity of items
                        $stmt = $transaction->connection->prepare("UPDATE items SET quantity = ?  WHERE id=?");
                        $stmt->bind_param('ii', $new_quantity, $item_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->close();

                        //delete the transaction in users_transactions table
                        $stmt2 = $transaction->connection->prepare("DELETE FROM users_transactions  WHERE transaction_id=?");
                        $stmt2->bind_param('i', $transaction_id);
                        $stmt2->execute();
                        $result = $stmt2->get_result();
                        $stmt2->close();

                        if (!$stmt2) {
                                $this->http_code = 500;
                                throw new \Exception('transaction_and_user_were_not_deleted');
                        }

                        $this->response_schema['message_code'] = "item_was_deleted_in table users_transactions";
                        $transaction->delete($this->request_body->id);
                } catch (\Exception $error) {
                        $this->response_schema['message_code'] = $error->getMessage();
                        $this->response_schema['success'] = false;
                }
        }

        /**
         * update the transaction quantity and the stock of item
         */


        public function update()
        {
                try {
                        if (!isset($this->request_body->id)) {
                                $this->http_code = 422;
                                throw new \Exception('id_param_not_found');
                        }
                        //make new instance of transaction model
                        $transaction = new Transaction;
                        $request_transaction_id = $this->request_body->id;
                        $request_quantity = $this->request_body->quantity;
                        $transaction_all = $transaction->get_by_id($request_transaction_id);
                        if (empty($transaction_all)) {
                                $this->http_code = 404;
                                throw new \Exception('item_not_found');
                        }
                        //quantity of the transaction before update
                        $old_quantity = $transaction_all->quantity;
                        $old_total = $transaction_all->total;
                        $item_id = $transaction_all->items_id;
                        //decrease the quantitiy
                        if ($old_quantity >= $request_quantity) {
                                $different_quantity = $old_quantity - $request_quantity;

                                $item = new Item;
                                $selected_item = $item->get_by_id($item_id);
                                $item_old_quantity = $selected_item->quantity;
                                $price_selcteditem = $selected_item->selling_price;
                                //quantity of the stock will increase by different
                                $item_new_quantity = $item_old_quantity + $different_quantity;

                                $stmt = $transaction->connection->prepare("UPDATE items SET quantity = ?  WHERE id=?");
                                $stmt->bind_param('ii', $item_new_quantity, $item_id);
                                $stmt->execute();
                                $result = $stmt->get_result(); // get the result of the execution
                                $stmt->close();

                                $total = $request_quantity * $price_selcteditem;
                                //we should change the quantitiy of transaction which is request body quantity
                                $stmt2 = $transaction->connection->prepare("UPDATE transactions SET quantity=?, total=?  WHERE id=?");
                                $stmt2->bind_param('idi', $request_quantity, $total, $request_transaction_id);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result(); // get the result of the execution
                                $stmt2->close();

                                if (!$result2) {
                                        $this->http_code = 500;
                                        throw new \Exception('item_was_not_updated');
                                }
                                $this->response_schema['message_code'] = "item_was_updated";
                        }
                        // i need to check if request body more than the old quantitiy of transaction
                        if ($request_quantity >= $old_quantity) {
                                // we should find the differece between the quantities 
                                $different_quantity = $request_quantity - $old_quantity;
                                $item = new Item;
                                $selected_item = $item->get_by_id($item_id);
                                // i want to check the quantity of item enough for the increasing in transaction quantitiy
                                $item_old_quantity = $selected_item->quantity;
                                $price_selcteditem = $selected_item->selling_price;

                                if ($different_quantity > $item_old_quantity) {
                                        $this->request_body->quantity = $old_quantity;
                                        $_SESSION['error'] = "there is no enough quantity in stock ";
                                        die;
                                }
                                //now we proceed the transaction if differance can stock accepte
                                elseif ($different_quantity <= $item_old_quantity) {
                                        // we sould change the stock
                                        $item_new_quantity = $item_old_quantity - $different_quantity;
                                        $stmt3 = $transaction->connection->prepare("UPDATE items SET quantity = ?  WHERE id=?");
                                        $stmt3->bind_param('ii', $item_new_quantity, $item_id);
                                        $stmt3->execute();
                                        $result3 = $stmt3->get_result(); // get the result of the execution
                                        $stmt3->close();
                                        // we should change the total of transaction
                                        $total = $request_quantity * $price_selcteditem;
                                        // the problem start here 
                                        $stmt4 = $transaction->connection->prepare("UPDATE transactions SET quantity=?, total=?  WHERE id=?");
                                        $stmt4->bind_param('idi', $request_quantity, $total, $request_transaction_id);
                                        $stmt4->execute();
                                        $result4 = $stmt4->get_result(); // get the result of the execution
                                        $stmt4->close();
                                        if (!$result4) {
                                                $this->http_code = 500;
                                                throw new \Exception('item_was_not_updated');
                                        }
                                        $this->response_schema['message_code'] = "item_was_updated";
                                }
                        }
                } catch (\Exception $error) {
                        $this->response_schema['message_code'] = $error->getMessage();
                        $this->response_schema['success'] = false;
                }
        }
}
