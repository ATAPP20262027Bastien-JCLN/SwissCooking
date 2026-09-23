<?php if (!empty($recipes)): ?>

    <?php foreach ($recipes as $recipe): ?>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 shadow-sm">

                <div class="card-body d-flex flex-column">

                    <h5 class="card-title">
                        <?= escape($recipe->name) ?>
                    </h5>

                    <p class="card-text mb-2">
                        <strong>Category:</strong>
                        <?= escape($recipe->category) ?>
                    </p>

                    <p class="card-text mb-2">
                        <strong>Author:</strong>
                        <?= isset($users[$recipe->id])
                            ? escape($users[$recipe->id]->name)
                            : 'Unknown' ?>
                    </p>

                    <p class="card-text">
                        <?= escape($recipe->description) ?>
                    </p>

                    <div class="mt-auto">

                        <p class="card-text mb-3">
                            <small class="text-muted">
                                <strong>Average Rating:</strong>

                                <?php if ($recipe->averageRating !== null): ?>
                                    <?= number_format($recipe->averageRating, 2) ?>/5
                                <?php else: ?>
                                    No ratings yet
                                <?php endif; ?>
                            </small>
                        </p>

                        <a
                            href="/recipe/<?= escape((string) $recipe->id) ?>"
                            class="btn btn-primary w-100"
                        >
                            View Recipe
                        </a>

                    </div>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="col-12 text-center py-5">
        <p class="lead">No recipes found.</p>
    </div>

<?php endif; ?>