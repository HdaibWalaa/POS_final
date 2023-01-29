<?php

namespace Core\Controller;

use Core\Base\Controller;
use Core\Base\View;
use Core\Helpers\Helper;
use Core\Helpers\Tests;
use Core\Model\Item;


class Items extends Controller
{

    use Tests;
    /**
     * make instance of class view
     *
     * @return void
     */
    public function render()
    {
        if (!empty($this->view))
            $this->view();
    }
    /**
     * check if user login or not and if admin will show him specific view
     *
     * @return void
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
        //the user can see this page just if have item:read permission
        $this->permissions(['item:read']);

        // include file index in folder items that have all items
        $this->view = 'items.index';

        //make instance of model Item to have info that i need from database 
        $item = new Item;

        // here we use the Test to check if i have all items from database or not
        self::check_if_empty($item->get_all());

        // now i put all items form db in variable data to show it in the privous html script
        $this->data['items'] = $item->get_all();

        // counting number of data that come from database
        $this->data['items_count'] = count($item->get_all());
    }
    /**
     * Gets specific item
     *
     * @return array
     */

    public function single()
    {
        //the user can see this page just if have item:read permission
        $this->permissions(['item:read']);

        // we use test to check if i have id in get or not
        self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

        // indicate the html page that i want it 
        $this->view = 'items.single';

        // make instanse form Item model
        $item = new Item();

        // put the information of item that i need in variable by using method get_by_id();
        $this->data['item'] = $item->get_by_id($_GET['id']);
    }

    /**
     * Display the HTML form for item creation
     *
     * @return void
     */
    public function create()
    {
        //the user can see this page just if have item:create permission
        $this->permissions(['item:create']);

        //that will includ the html that i want to see
        $this->view = 'items.create';
    }

    /**
     * Creates new item
     *
     * @return void
     */


    public function store()
    {

        //the user can see this page just if have item:create permission
        $this->permissions(['item:create']);

        // make validation on item_name and quantity and cost and selling_price to make sure not empty
        self::check_if_empty($_POST['item_name']);
        self::check_if_empty($_POST['quantity']);
        self::check_if_empty($_POST['cost']);
        self::check_if_empty($_POST['selling_price']);
        self::check_if_empty($_POST['category']);

        //make instance of Item model
        $item = new Item();

        //i put variable that have last id to make the image uniqe as possible
        $data['photo_count'] = $item->last("items");
        $conuter = (int)$data['photo_count'][0] + "1";

        // make another number to emphesis to make image name unique
        $name = count($item->get_all());

        //declear variable that have string type
        $file_name = '';

        //check if the globle variable $_FIlES is empty or not
        if (!empty($_FILES)) {
            // to find the extention of image we need to divide  $_FILES['image']['type'] in the last /
            $file_ext = substr(
                $_FILES['image']['type'], // 'image/jpeg'
                strpos($_FILES['image']['type'], '/') + 1 // 5
            );
            //now reassign the $file_name to new name to save it in folder image 
            $file_name = "image-.$name.$conuter.{$file_ext}";
            move_uploaded_file($_FILES['image']['tmp_name'], "./resources/image/$file_name");
        }

        //if $_FILES['image']['name'] empty i want to put defult image
        if (empty($_FILES['image']['name'])) {
            $file_name = "image-defult.png";
        }

        //now i want to reassign $_POST['image'] to new name of image
        $_POST['image'] = $file_name;


        // i use method to protect $_post from xss attack
        $this->htmlspecial($_POST);

        //here i want to create $_POSt in database
        $item->create($_POST);

        //after the create new item will redirect user to /items that contain all items
        Helper::redirect('/items');
    }

    /**
     * Display the HTML form for item update
     *
     * @return void
     */
    public function edit()
    {
        //the user can see this page just if have 'item:read', 'item:update' permission
        $this->permissions(['item:read', 'item:update']);

        // we use test to check if i have id in get or not
        self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

        //that will includ the html that i want to see
        $this->view = 'items.edit';

        //make instance of model Item to have info that i need from database 
        $item = new Item();

        // put the information of item that i need in variable by using method get_by_id();
        $this->data['item'] = $item->get_by_id($_GET['id']);
    }

    /**
     * Updates the item
     *
     * @return void
     */

    public function update()
    {


        //the user can see this page just if have 'item:read', 'item:update' permission
        $this->permissions(['item:read', 'item:update']);

        // make validation on item_name and quantity and cost and selling_price to make sure not empty
        self::check_if_empty($_POST['item_name']);
        self::check_if_empty($_POST['quantity']);
        self::check_if_empty($_POST['cost']);
        self::check_if_empty($_POST['selling_price']);

        //make instance of model Item to have info that i need from database 
        $item = new Item();

        //select the item that i need to make updat on by form that have hidden id input
        $selected = $item->get_by_id($_POST['id']);


        //protect the $_POST from xss attacks
        $this->htmlspecial($_POST);

        //update the item in database
        $item->update($_POST);

        //after update the item will redirect the user to single item page
        Helper::redirect('/item?id=' . $_POST['id']);
    }



    /**
     * Delete the item
     *
     * @return void
     */
    public function delete()
    {
        //the user can see this page just if have 'item:read', 'item:delete' permission
        $this->permissions(['item:read', 'item:delete']);

        // we use test to check if i have id in get or not
        self::check_if_exists(isset($_GET['id']), "Please make sure the id is exists");

        //make instance of model Item to have info that i need from database
        $item = new Item();

        //delete the item from db 
        $item->delete($_GET['id']);

        //redirect the user to all items page
        Helper::redirect('/items');
    }
}
