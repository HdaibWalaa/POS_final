<div class="login-form">
    <div class="txt_field">
        <?php if (!empty($_SESSION) && isset($_SESSION['error']) && !empty($_SESSION['error'])) : ?>
            <div class='alert alert-danger mb-3' role='alert'>
                <?= $_SESSION['error'] ?>
            </div>
        <?php
            $_SESSION['error'] = null;
        endif; ?>
        <form method="POST" action="/authenticate">
            <h1>Login</h1>
            <div>
                <label for="admin-username">Username</label>
                <input type="text" id="admin-username" name="username" required>
            </div>
            <div>
                <label for="admin-password">Password</label>
                <input type="password" id="admin-password" name="password" required>
            </div>
            <div>
                <input type="checkbox" name="remember_me">
                Remember me
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
    </div>