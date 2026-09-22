<?php
// session_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4">Welcome Back</h1>
                    <p class="text-center text-muted mb-4">
                        Log in to your Swiss Cooking account.
                    </p>
                    <?php if (!empty($_SESSION['error'])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= escape($_SESSION['error']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['errors'])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $error) : ?>
                                    <li><?= escape($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="you@example.com"
                                value="<?= escape($_POST['email'] ?? '') ?>"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password
                            </label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                            >
                        </div>
                        <!-- Remember me -->
                        <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                >
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="/forgot-password">
                                Forgot password?
                            </a>
                        </div> -->
                        <button type="submit" class="btn btn-success w-100">
                            Log In
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Don't have an account?
                            <a href="/register">Create one</a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>