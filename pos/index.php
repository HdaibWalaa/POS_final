<?php
session_start();

use Core\Model\User;
use Core\Router;

spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'Core') === false)
        return;
    $class_name = str_replace("\\", '/', $class_name);
    $file_path = __DIR__ . "/" . $class_name . ".php";
    require_once $file_path;
});

// Check if the user_id cookie is set and the user session variable is not set
if (isset($_COOKIE['user_id']) && !isset($_SESSION['user'])) {
    // Create a new User object
    $user = new User();

    // Retrieve the user data by calling the get_by_id method on the User object
    $logged_in_user = $user->get_by_id($_COOKIE['user_id']);

    // Create an array containing some of the user's data
    $_SESSION['user'] = array(
        'username' => $logged_in_user->username, // user's name
        'display_name' => $logged_in_user->display_name, // user's display name
        'user_id' => $logged_in_user->id, // user's id
        'is_admin_view' => true  // hard coded boolean value
    );
}






Router::get('/', 'authentication.login');

Router::get('/logout', "authentication.logout");
Router::post('/authenticate', "authentication.validate");


Router::get('/dashboard', "admin.index");


Router::get('/items', "items.index");
Router::get('/item', "items.single");
Router::get('/items/create', "items.create");
Router::post('/items/store', "items.store");
Router::get('/items/edit', "items.edit");
Router::post('/items/update', "items.update");
Router::get('/items/delete', "items.delete");
Router::post('/items/image', "items.image");
Router::get('/category', "items.get_by_category");





Router::get('/transactions', "transactions.index");
Router::post('/transactions/create', "transactions.create");
Router::delete('/transactions/delete', "transactions.delete");
Router::put('/transactions/update', 'transactions.update');

Router::get('/sales', "sales.index");
Router::get('/sales/all_transactions', "sales.all_transactions");
Router::get('/sales/delete', "sales.delete");
Router::get('/sales/single', "sales.single");
Router::get('/sales/edit', "sales.edit");
Router::post('/sales/update', "sales.update");




Router::get('/users', "users.index");
Router::get('/user', "users.single");
Router::get('/profile', "users.profile");
Router::get('/users/create', "users.create");
Router::post('/users/store', "users.store");
Router::get('/users/edit', "users.edit");
Router::post('/users/update', "users.update");
Router::get('/users/delete', "users.delete");
Router::post('/users/image', "users.image");


Router::redirect();
