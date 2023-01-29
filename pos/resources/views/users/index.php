<div class="row all-user">
    <h1 class="d-flex justify-content-around item_title "> All Users (<?= $data->users_count ?>)</h1>
    <?php foreach ($data->users as $user) : ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card">
                <a href="./user?id=<?= $user->id ?>">
                    <img src="./resources/image/<?= $user->image ?>" class="card-img-top" alt="<?= $user->display_name ?> img">
                    <div class="card-body">
                        <h5 class="card-title"><?= $user->display_name ?></h5>
                        <p class="card-text"><?= $user->role ?></p>
                    </div>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>