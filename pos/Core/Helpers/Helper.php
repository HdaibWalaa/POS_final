<?php

namespace Core\Helpers;

use Core\Model\User;

class Helper
{
     /**
     * redirect to specific url
     *
     * @return void
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
    }

    /**
     * check the permission of the user and the permission of the method
     *@param $permissions_set
     * @return void
     */

    public static function check_permission(array $permissions_set): bool
    {
        //assign variable boolean type
        $display = true;

        //if there is no user login ite will return false
        if (!isset($_SESSION['user'])) {
            return false;
        }

        //make new instance of user
        $user = new User;

        //now we get the premissions of the user that logged in and we should compare it with permission of method
        $assigned_permissions = $user->get_permissions();
        foreach ($permissions_set as $permission) {
            if (!in_array($permission, $assigned_permissions)) {
                return false;
            }
        }
        return $display;
    }
}
