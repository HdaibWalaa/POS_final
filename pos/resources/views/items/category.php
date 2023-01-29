<div class="m-auto ms-2 ">
    <h1 class="d-flex justify-content-around item_title "> All Items (<?= $data->items_count ?>)</h1>
    <div class="container-item">
        <div class="row my-5 m-auto d-flex justify-content-center">
            <?php foreach ($data->items as $item) : ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 px-3 mb-4">
                    <a href="./item?id=<?= $item->id ?>">
                        <div class="card">
                            <?php if (isset($item->image) && !empty($item->image)) : ?>
                                <img id="card_single" src="./resources/image/<?= $item->image ?>" alt="" width='60%' class="mt-3 m-auto">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title text-center"> <?= $item->item_name ?></h5>
                                <p class="text-center mb-0">Price:<?= $item->selling_price ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>