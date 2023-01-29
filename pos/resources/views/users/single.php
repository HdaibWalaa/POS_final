<div class="single-user">
    <div class="m-auto col-xl-6 col-md-7 col-sm-12 col-xs-12 box p-2 pe-3 pb-5">
        <div class="row">
            <div class="col-sm-4">
                <img class="profile-image" src="./resources/image/<?= $data->user->image ?>" alt="Profile image" width="100%">
            </div>
            <div class="user-containt col-sm-8">
                <h3 class="user-info"><?= $data->user->display_name ?></h3>
                <p class="user-info"><strong>username:</strong><?= $data->user->username ?></p>
                <p class="user-info"><strong>E-mail:</strong><?= $data->user->email ?></p>
                <p class="user-info"><strong>Role:</strong><?= $data->user->role ?></p>

                <a href="/users/edit?id=<?= $data->user->id ?>" class="btn btn-warning mb-2">Edit</a>
                <?php if ($_SESSION['user']['role'] == "admin") : ?>
                    <a href="/users/delete?id=<?= $data->user->id ?>" class="btn btn-danger mb-2">Delete</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>