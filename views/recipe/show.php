<div class="container py-5">

    <div class="row justify-content-center main-content">
        <div class="col-12 col-lg-9">

            <a href="/recipes" class="btn btn-outline-secondary mb-4">
                ← Back to recipes
            </a>

            <div class="card shadow-sm">

                <div class="card-body p-4 p-md-5">

                    <div class="mb-4">
                        <span class="badge bg-secondary">
                            <?= htmlspecialchars($recipe->category) ?>
                        </span>
                    </div>

                    <h1 class="mb-3">
                        <?= htmlspecialchars($recipe->name) ?>
                    </h1>

                    <?php if ($user) : ?>
                        <p class="text-muted">
                            Recipe by
                            <strong>
                                <?= htmlspecialchars($user->name) ?>
                            </strong>
                        </p>
                    <?php endif; ?>

                    <hr>

                    <h3 class="mt-4">Description</h3>

                    <p>
                        <?= nl2br(htmlspecialchars($recipe->description)) ?>
                    </p>

                    <div class="recipe-ingredients">
                        <h3 class="mt-5">Ingredients</h3>

                        <?php if (!empty($recipe->ingredients)) : ?>

                            <div class="list-group">
                                <?php foreach ($recipe->ingredients as $ingredient) : ?>

                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">

                                            <div>
                                                <h5 class="mb-1">
                                                    <?= htmlspecialchars($ingredient->name ?? '') ?>
                                                </h5>

                                                <?php if (!empty($ingredient->description)) : ?>
                                                    <p class="mb-1 text-muted">
                                                        <?= htmlspecialchars($ingredient->description) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>

                                            <?php if ($ingredient->quantity !== null || $ingredient->unit !== null) : ?>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars((string) ($ingredient->quantity ?? '')) ?>
                                                    <?= htmlspecialchars($ingredient->unit ?? '') ?>
                                                </span>
                                            <?php endif; ?>

                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </div>

                        <?php else : ?>

                            <p class="text-muted">
                                No ingredients listed.
                            </p>

                        <?php endif; ?>
                    </div>

                    <!-- maybe add instructions section here in the future -->
                    <!-- <h3 class="mt-5">Instructions</h3>

                    <div class="recipe-instructions">
                        <?php if (!empty($recipe->instructions)) : ?>
                            <p>
                                <?= nl2br(htmlspecialchars($recipe->instructions)) ?>
                            </p>
                        <?php else : ?>
                            <p class="text-muted">
                                No instructions listed.
                            </p>
                        <?php endif; ?>
                    </div> -->

                    <h3 class="mt-5">Rating</h3>

                    <p>
                        <?php if ($recipe->averageRating !== null) : ?>

                            <span class="fs-4">
                                <?= number_format($recipe->averageRating, 2) ?>/5
                            </span>

                        <?php else : ?>

                            <span class="text-muted">
                                No ratings yet.
                            </span>

                        <?php endif; ?>
                    </p>

                </div>
            </div>

        </div>
    </div>

</div>