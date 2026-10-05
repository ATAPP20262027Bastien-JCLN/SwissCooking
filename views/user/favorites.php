<div class="container py-5">

    <div class="row justify-content-center main-content">
        <div class="col-12 col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-1">My favorites</h1>
                    <p class="text-muted mb-0">
                        Recipes you have saved.
                    </p>
                </div>

                <a
                    href="/recipes"
                    class="btn btn-outline-secondary"
                >
                    Browse recipes
                </a>
            </div>

            <?php if (! empty($recipes)): ?>

                <div class="row g-4">

                    <?php foreach ($recipes as $recipe): ?>

                        <div class="col-12 col-md-6">

                            <div class="card shadow-sm h-100">

                                <div class="card-body d-flex flex-column">

                                    <div class="mb-3">
                                        <span class="badge bg-secondary">
                                            <?php echo escape(
                                                $categories[$recipe->category_id - 1 ?? 0]->name ?? ''
                                            ) ?>
                                        </span>
                                    </div>

                                    <h3 class="card-title">
                                        <?php echo escape($recipe->name) ?>
                                    </h3>

                                    <?php if (
                                        isset($users[$recipe->id]) &&
                                        $users[$recipe->id] !== null
                                    ): ?>

                                        <p class="text-muted mb-2">
                                            Recipe by

                                            <a
                                                href="/user/<?php echo escape(
                                                    $users[$recipe->id]->id
                                                ) ?>"
                                                class="text-decoration-none"
                                            >
                                                <?php echo escape(
                                                    $users[$recipe->id]->name
                                                ) ?>
                                            </a>
                                        </p>

                                    <?php endif; ?>

                                    <p class="card-text text-muted">
                                        <?php echo escape(
                                            $recipe->description
                                        ) ?>
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-4">

                                        <a
                                            href="/recipe/<?php echo escape($recipe->id) ?>"
                                            class="btn btn-primary"
                                        >
                                            View recipe
                                        </a>

                                        <form
                                            action="/recipe/<?php echo escape($recipe->id) ?>/favorite"
                                            method="POST"
                                        >
                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger"
                                            >
                                                ♥ Remove
                                            </button>
                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">

                        <div class="fs-1 mb-3">
                            ♡
                        </div>

                        <h3>No favorite recipes yet</h3>

                        <p class="text-muted">
                            Recipes you favorite will appear here.
                        </p>

                        <a
                            href="/recipes"
                            class="btn btn-primary mt-2"
                        >
                            Browse recipes
                        </a>

                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>