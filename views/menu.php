<div class="invisible-nav-replacer"></div>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            Swiss Cooking
        </a>

        <button type="button" id="darkModeToggleMobile" class="btn btn-outline-secondary mobile-theme-toggle">
            🌙
        </button>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse"
            style="background-color: rgba(var(--bs-tertiary-bg-rgb),var(--bs-bg-opacity)) !important;" id="navbarNav">

            <?php if (!empty($_SESSION['user_id'])): ?>
            <?php
                $user = \BastienJcln\SwissCooking\Models\User::findById(
                    $_SESSION['user_id']
                );
                ?>

            <ul class="navbar-nav me-auto">

                <?php if ($user && $user->id_role === ADMIN_ROLE_ID): ?>

                <!-- ADMIN: Administration and logout only -->
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Administration</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/logout">Déconnexion</a>
                </li>

                <?php else: ?>

                <!-- REGULAR USERS AND MODERATORS -->
                <li class="nav-item">
                    <a class="nav-link" href="/">Accueil</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/recipes">Recettes</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/categories">Catégories</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/favorites">Favoris</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/recipe/create">
                        Créer une recette
                    </a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/profile">
                        <?php
                                echo escape($user ? $user->name : 'Unknown');
                                ?>
                    </a>
                </li>

                <?php if ($user && $user->id_role === MODERATOR_ROLE_ID): ?>
                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/admin">
                        Administration
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/logout">Déconnexion</a>
                </li>

                <?php endif; ?>

            </ul>

            <?php else: ?>

            <!-- VISITORS -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/">Accueil</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/recipes">Recettes</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/categories">Catégories</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/login">Connexion</a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/register">Inscription</a>
                </li>

            </ul>

            <?php endif; ?>

        </div>

        <button type="button" id="darkModeToggleDesktop" class="btn btn-outline-secondary desktop-theme-toggle">
            🌙 Dark mode
        </button>
    </div>
</nav>