<div class="m-auto ms-2 ">
    <!-- <div class="card-container-pages">
        <div class="card">
            <a href="/resources/views/items/index.php">
                <image src="/resources/image/all.png" alt="ALL-items">
                    <p>ALL</p>
            </a>
        </div>
        <div class="card">
            <?php $category = 'coffee'; ?>
            <a href="./items/category/<?= $category ?>">
                <image src="/resources/image/coffee.png" alt="COFFEE">
                    <p>COFFEE</p>
            </a>
        </div>
        <div class="card">
            <?php $category = 'juice'; ?>
            <a href="./items/category/<?= $category ?>">
                <image src="/resources/image/juice.png" alt="JUICES">
                    <p>JUICES</p>
            </a>
        </div>
        <div class="card">
            <a href="/resources/views/items/milk-passed.php">
                <image src="/resources/image/milk.png" alt="Milk Pased">
                    <p>Milk Pased</p>
            </a>
        </div>
        <div class="card">
            <a href="/resources/views/items/deseret.php">
                <image src="/resources/image/cover.png" alt="Deseret">
                    <p>Deseret</p>
            </a>
        </div>
        <div class="card">
            <a href="/resources/views/items/Sadwiches.php">
                <image src="/resources/image/sandwich.png" alt="sandwich">
                    <p>Sandwich</p>
            </a>
        </div>
    </div> -->

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