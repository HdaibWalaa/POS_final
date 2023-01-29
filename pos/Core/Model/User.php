<?php

namespace Core\Model;

use Core\Base\Model;

class User extends Model
{
    // assign constants that contain the permission of all roles

    const ADMIN = array(
        "item:read", "item:create", "item:update", "item:delete",
        "user:read", "user:create", "user:update", "user:delete",
        "transaction:read", "transaction:create", "transaction:update", "transaction:delete"
    );

    const SELLER = array(
        "transaction:create", "transaction:update", "transaction:delete"
    );

    const PROCUREMENT = array(
        "item:update", "item:delete"

    );

    const ACCOUNTANT = array(
        "transaction:read", "transaction:update", "transaction:delete"
    );

    /**
     * check the username if he exists or not
     *
     * 
     */
    public function check_username(string $username)
    {
        $result = $this->connection->query("SELECT * FROM $this->table WHERE username='$username'");
        if ($result) { // if there is an error in the connection or if there is syntax error in the SQL.
            if ($result->num_rows > 0) {
                return $result->fetch_object();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * fetch the permission from db of user that currently logged in 
     *
     * 
     */
    public function get_permissions(): array
    {
        //assign the variable as array type to put inside the permission
        $permissions = array();

        //fetch the iformation of user from session using get_by_id
        $user = $this->get_by_id($_SESSION['user']['user_id']);

        if ($user) {
            //make permission that come from db as string to array 
            $permissions = \unserialize($user->permissions);
        }
        return $permissions;
    }
}
