<div class="card-deck">
    <div class="row">
        <div class="card col-lg-3 col-md-4 col-sm-6">
            <img class="card-img-top" src="/resources/image/totalsales.png" alt="total-img">
            <div class="card-body">
                <h5 class="card-title">Total Sales:</h5>
                <p class="card-text"><?= $data->total_sales ?> JOD</p>
            </div>
        </div>
        <div class="card col-lg-3 col-md-4 col-sm-6">
            <img class="card-img-top" src="/resources/image/totaltransaction.png" alt="total-img">
            <div class="card-body">
                <h5 class="card-title">Total Transactions:</h5>
                <p class="card-text"><?= $data->transaction_count ?></p>
            </div>
        </div>
        <div class="card col-lg-3 col-md-4 col-sm-6">
            <img class="card-img-top" src="/resources/image/totaluser.png" alt="total-img">
            <div class="card-body">
                <h5 class="card-title">Total Users:</h5>
                <p class="card-text"><?= $data->users_count ?></p>
            </div>
        </div>
        <div class="card col-lg-3 col-md-4 col-sm-6">
            <img class="card-img-top" src="/resources/image/totalitem.png" alt="total-img">
            <div class="card-body">
                <h5 class="card-title">Total Items:</h5>
                <p class="card-text"><?= $data->items_count ?></p>
            </div>
        </div>
    </div>
</div>


<div class="topfive-item">
    <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="/resources/image/coffeebeans.png" alt="Card-image">
        <div class="card-body">
            <h5 class="card-title">Top Five Expensive Items</h5>
            <ul>
                <?php
                $counter = 1;
                foreach ($data->top_five as $item) : ?>
                    <li><?= $counter ?>- <a href="./item?id=<?= $item->id ?>"><?= $item->item_name ?></a> - <?= $item->selling_price ?> JOD</li>
                <?php $counter++;
                endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<div class="low-item">
    <div class="card">
        <img class="card-img-top" src="/resources/image/out.png" alt="Card2-image">
        <div class="card-body">
            <h5 class="card-title"> Almost Out Of the stocke</h5>
            <ul>
                <?php
                $counter = 1;
                foreach ($data->lowQuantity_Items as $item) : ?>
                    <li><?= $counter ?>- <a href="./item?id=<?= $item->id ?>"><?= $item->item_name ?> - Only <?= $item->quantity ?> left in stock</li>
                <?php $counter++;
                endforeach; ?>
            </ul>
        </div>
    </div>
</div>