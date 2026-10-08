<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4">Créer un compte</h1>
                    <p class="text-center text-muted mb-4">Créez votre compte Swiss Cooking aujourd'hui!</p>
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
                    <form action="/register" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label"> Nom </label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Entrez votre nom"
                                maxlength="255" value="<?php echo escape($_POST['name'] ?? '') ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"> Email </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="you@example.com" maxlength="255"
                                value="<?php echo escape($_POST['email'] ?? '') ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"> Mot de passe </label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Entrez votre mot de passe" required />
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirmer le mot de passe
                            </label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Répétez votre mot de passe" required />
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Créer un compte
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Vous avez déjà un compte?
                            <a href="/login">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>