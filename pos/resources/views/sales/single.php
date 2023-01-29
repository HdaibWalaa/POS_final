<?php

use Core\Helpers\Helper;
?>
<div class=" transaction box p-1 pe-5 ps-5 ">


    <div class="single-trans-info my-5">

        <h1 class="text-center item_title pt-0">
            <h5>Transaction id: </h5><?= $data->transaction->id ?>
        </h1>
        <p class="item_description">
        <h5>Item Name: </h5><?= $data->transaction->item_name ?>
        </p>
        <p class="item_description">
        <h5>Price: </h5><?= $data->transaction->price ?>JOD
        </p>
        <p class="item_description">
        <h5>Quantity: </h5><?= $data->transaction->quantity ?>
        </p>
        <p class="item_description">
        <h5>Total: </h5><?= $data->transaction->total ?>JOD
        </p>
        <p class="item_description">
        <h5>Created at: </h5><?= $data->transaction->created_at ?>
        </p>
        <p class="item_description">
        <h5>Updated at: </h5><?= $data->transaction->updated_at ?>
        </p>
    </div>
    <div class="single-trans-btn mt-5 d-flex flex-row-reverse gap-3">
        <?php if (Helper::check_permission(['transaction:read', 'transaction:update'])) : ?>
            <a href="/sales/edit?id=<?= $data->transaction->id ?>" class="btn btn-warning">Edit</a>
        <?php endif;
        if (Helper::check_permission(['transaction:read', 'transaction:update'])) :
        ?>
            <a href="/sales/delete?id=<?= $data->transaction->id ?>" class="btn btn-danger">Delete</a>
        <?php endif; ?>
    </div>
</div>