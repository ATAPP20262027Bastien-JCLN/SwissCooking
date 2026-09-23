<div class="invisible-nav-replacer"></div>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">

        <a class="navbar-brand" href="/">
            Swiss Cooking
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <?php if (!empty($_SESSION['user_id'])) : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/recipes">
                            Recipes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/profile">
                            <?= escape(
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

                    <li class="nav-item">
                        <a class="nav-link" href="/logout">
                            Logout
                        </a>
                    </li>

                <?php else : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/login">
                            Log In
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/register">
                            Register
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

            <button
                type="button"
                id="darkModeToggle"
                class="btn btn-outline-secondary"
            >
                🌙 Dark mode
            </button>

        </div>
    </div>
</nav>