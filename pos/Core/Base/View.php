<?php

namespace Core\Base;

/**
 * Include the php html template
 * @param $view $data
 */
class View
{
    public function __construct(string $view, array $data = array())
    {
        // Replace '.' with '/' in the view name
        $view = \str_replace('.', '/', $view);

        // Cast the data array to an object
        $data = (object) $data;

        // Default header and footer file names
        $header = 'header';
        $footer = 'footer';

        // Check if the is_admin_view session is set and true
        if (isset($_SESSION['user']['is_admin_view'])) {
            if ($_SESSION['user']['is_admin_view']) {
                // Change header and footer file names
                $header = 'header-admin';
                $footer = 'footer-admin';
            }
        }

        // Include the header file
        include_once \dirname(__DIR__, 2) . "/resources/views/partials/$header.php";

        // Include the main view file
        include_once \dirname(__DIR__, 2) . "/resources/views/$view.php";

        // Include the footer file
        include_once \dirname(__DIR__, 2) . "/resources/views/partials/$footer.php";
    }
}
