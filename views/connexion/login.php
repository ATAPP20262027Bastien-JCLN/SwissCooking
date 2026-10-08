<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4">Connexion</h1>
                    <p class="text-center text-muted mb-4">
                        Connectez-vous à votre compte Swiss Cooking.
                    </p>
                    <?php if (! empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo escape($_SESSION['error']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty($_SESSION['errors'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo escape($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label"> Email </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="you@example.com" value="<?php echo escape($_POST['email'] ?? '') ?>"
                                required />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"> Mot de passe </label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Entrez votre mot de passe" required />
                        </div>
                        <button type="submit" class="btn btn-success w-100">Se connecter</button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Pas encore de compte ?
                            <a href="/register">Créer un compte</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>