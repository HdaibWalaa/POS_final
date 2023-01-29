<?php

use Core\Helpers\Helper; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS system</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= "http://" . $_SERVER['HTTP_HOST'] ?>/resources/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>

<body class="admin-view">
    <nav class="navbar navbar-expand-lg user-name-img">
        <div class="container-fluid w-100">
            <div class="d-flex align-items-center gap-2">
                <a href="/dashboard"><img id="logo" src="/resources/image/logo.png" alt="logo-img"></a>
            </div>
            <!-- <?php if ($_SESSION['user']['user_id'] == $_GET['id'] || $_SESSION['user']['role'] == "admin") : ?>
                    <a href="./user?id=<?= $user->id ?>" class="btn user-profile p-1 pe-2">
                        <img src="<?= "http://" . $_SERVER['HTTP_HOST'] ?>/resources/image/<?= $_SESSION['user']['image'] ?>" class="user_image">
                        welcome<?= $_SESSION['user']['display_name'] ?>
                    </a>
                    <?php endif; ?> -->
            <div class="mr-5w-25">
                <a href="/profile?id=<?= $_SESSION['user']['user_id'] ?>" class="btn user-profile p-1 pe-2">
                    <div>
                        <img src="<?= "http://" . $_SERVER['HTTP_HOST'] ?>/resources/image/<?= $_SESSION['user']['image'] ?>" class="user_image">
                        welcome ,<?= $_SESSION['user']['display_name'] ?>
                    </div>
                </a>
            </div>

        </div>
    </nav>
    <div id="admin-sidebar" class="row w-100 ">

        <div class="col-lg-1 col-md-2 col-sm-2  sidebar">
            <ul class="list-group list-group-flush mt-3 ">
                <?php if ($_SESSION['user']['role'] == "admin") : ?>
                    <li class="list-group-item p-3 ">
                        <a href="/dashboard"><img id="side-img" src="/resources/image/home.png" alt="home-img"></a>
                    </li>
                <?php endif; ?>

                <?php if (Helper::check_permission(['item:read'])) : ?>
                    <li class="list-group-item p-3">
                        <a href="/items"><img id="side-img" src="/resources/image/menu.png" alt="menu-img"></a>
                    </li>
                <?php endif;
                if (Helper::check_permission(['item:create'])) :
                ?>
                    <li class="list-group-item p-3">
                        <a href="/items/create"><img id="side-img" src="/resources/image/addmenu.webp" alt="addmenu-img"></a>
                    </li>
                <?php endif;

                if (Helper::check_permission(['transaction:read'])) :
                ?>
                    <li class="list-group-item p-3">
                        <a href="/sales"><img id="side-img" src="/resources/image/sales.png" alt="sales-img"></a>
                    </li>
                <?php endif;
                if (Helper::check_permission(['transaction:create'])) :
                ?>
                    <li class="list-group-item p-3">
                        <a href="/sales/all_transactions"><img id="side-img" src="/resources/image/transaction.png" alt="transaction-img"></a>
                    </li>
                <?php endif; ?>
                <?php if (Helper::check_permission(['user:read'])) :
                ?>
                    <li class="list-group-item p-3">
                        <a href="/users"><img id="side-img" src="/resources/image/users.png" alt="users-img"></a>
                    </li>
                <?php endif;
                if (Helper::check_permission(['user:create'])) :
                ?>
                    <li class="list-group-item p-3">
                        <a href="/users/create"><img id="side-img" src="/resources/image/adduser.png" alt="adduser-img"></a>
                    </li>
                <?php endif; ?>
                <li class="list-group-item p-3">
                    <a href="/logout"><img id="side-img" src="/resources/image/log-out.png" alt="adduser-img"></a>
                </li>
            </ul>
        </div>
        <div class="col-xl-11 col-sm-10 cl-md-10 cl-xs-10 admin-area-content">
            <div class="container my-5 dashboard-divs">