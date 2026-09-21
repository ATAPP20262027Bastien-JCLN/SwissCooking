<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">All Recipes</h2>

            <?php if (!empty($recipes)) : ?>

                <div class="row g-4 main-content">
                    <?php foreach ($recipes as $recipe) : ?>

                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 shadow-sm">

                                <div class="card-body d-flex flex-column">

                                    <h5 class="card-title">
                                        <?= htmlspecialchars($recipe->name) ?>
                                    </h5>

                                    <p class="card-text mb-2">
                                        <strong>Category:</strong>
                                        <?= htmlspecialchars($recipe->category) ?>
                                    </p>

                                    <p class="card-text mb-2">
                                        <strong>Author:</strong>
                                        <?= isset($users[$recipe->id])
                                            ? htmlspecialchars($users[$recipe->id]->name)
                                            : 'Unknown' ?>
                                    </p>

                                    <p class="card-text">
                                        <?= htmlspecialchars($recipe->description) ?>
                                    </p>

                                    <div class="mt-auto">

                                        <p class="card-text mb-3">
                                            <small class="text-muted">
                                                <strong>Average Rating:</strong>

                                                <?php if ($recipe->averageRating !== null) : ?>
                                                    <?= number_format($recipe->averageRating, 2) ?>/5
                                                <?php else : ?>
                                                    No ratings yet
                                                <?php endif; ?>
                                            </small>
                                        </p>

                                        <a
                                            href="/recipe/<?= htmlspecialchars((string) $recipe->id) ?>"
                                            class="btn btn-primary w-100"
                                        >
                                            View Recipe
                                        </a>

                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            <?php else : ?>

                <div class="text-center py-5">
                    <p class="lead">No recipes available.</p>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>