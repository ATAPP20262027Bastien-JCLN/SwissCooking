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

            <ul class="navbar-nav me-auto">

                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>
                <li class="nav-item"><a class="nav-link" href="/recipes">Recipes</a></li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>
                <li class="nav-item"><a class="nav-link" href="/categories">Categories</a></li>
                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <?php if (! empty($_SESSION['user_id'])): ?>
                <li class="nav-item"><a class="nav-link" href="/favorites">Favorites</a></li>
                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>
                <li class="nav-item"><a class="nav-link" href="/recipe/create">Create Recipe</a></li>
                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profile">
                        <?php echo escape(
    \BastienJcln\SwissCooking\Models\User::findById(
        $_SESSION['user_id']
    )
        ? \BastienJcln\SwissCooking\Models\User::findById(
        $_SESSION['user_id']
    )->name
        : 'Unknown'
) ?>
                    </a>
                </li>
                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/logout">
                        Logout
                    </a>
                </li>

                <?php else: ?>

                <li class="nav-item">
                    <a class="nav-link" href="/login">
                        Log In
                    </a>
                </li>

                <li class="nav-item desktop-link-separator">
                    <div class="nav-link">|</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">
                        Register
                    </a>
                </li>

                <?php endif; ?>

            </ul>

        </div>

            <button type="button" id="darkModeToggleDesktop" class="btn btn-outline-secondary desktop-theme-toggle">
                🌙 Dark mode
            </button>
    </div>
</nav>