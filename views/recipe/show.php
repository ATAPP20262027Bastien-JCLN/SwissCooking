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
                            <?= escape($recipe->category) ?>
                        </span>
                    </div>

                    <h1 class="mb-3">
                        <?= escape($recipe->name) ?>
                    </h1>

                    <?php if ($user): ?>
                    <p class="text-muted">
                        Recipe by
                        <strong>
                            <?= escape($user->name) ?>
                        </strong>
                    </p>
                    <?php endif; ?>

                    <hr>

                    <h3 class="mt-4">Description</h3>

                    <p>
                        <?= nl2br(escape($recipe->description)) ?>
                    </p>

                    <div class="recipe-ingredients">
                        <h3 class="mt-5">Ingredients</h3>

                        <?php if (!empty($recipe->ingredients)): ?>
                        <div class="list-group">
                            <?php foreach ($recipe->ingredients as $ingredient): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">
                                            <?= escape($ingredient->name ?? '') ?>
                                        </h5>

                                        <?php if (!empty($ingredient->description)): ?>
                                        <p class="mb-1 text-muted">
                                            <?= escape($ingredient->description) ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($ingredient->quantity !== null || $ingredient->unit !== null): ?>
                                    <span class="badge bg-secondary">
                                        <?= escape((string) ($ingredient->quantity ?? '')) ?>
                                        <?= escape($ingredient->unit ?? '') ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-muted">
                            No ingredients listed.
                        </p>
                        <?php endif; ?>
                    </div>

                    <h3 class="mt-5">Instructions</h3>

                    <div class="recipe-instructions">
                        <?php if (!empty($recipe->steps)): ?>
                        <p class="text-muted">
                            <?php $steps = explode('|', $recipe->steps); ?>

                            <?php foreach ($steps as $step): ?>
                            <?= escape(trim($step)) ?><br>
                            <?php endforeach; ?>
                        </p>
                        <?php else: ?>
                        <p class="text-muted">
                            No instructions listed.
                        </p>
                        <?php endif; ?>
                    </div>

                    <h3 class="mt-5">Rating</h3>

                    <p>
                        <?php if ($recipe->averageRating !== null): ?>
                        <span class="fs-4">
                            <?= number_format($recipe->averageRating, 2) ?>/5
                        </span>
                        <?php else: ?>
                        <span class="text-muted">
                            No ratings yet.
                        </span>
                        <?php endif; ?>
                    </p>

                    <h3 class="mt-5">Comments</h3>

                    <?php if (!empty($recipe->comments)): ?>
                    <div class="comments-list">
                        <?php foreach ($recipe->comments as $comment): ?>
                        <div class="card mb-3 border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= escape(
                                                    "/" . $comment->user_profile_picture === "/"
                                                    ? 'https://ui-avatars.com/api/?name='
                                                    . urlencode($comment->user_name ?? 'Anonymous')
                                                    . '&size=256' : "/" . $comment->user_profile_picture
                                                ) ?>" alt="" class="rounded-circle" width="40" height="40">

                                        <strong>
                                            <?= escape($comment->user_name ?? 'Anonymous') ?>
                                        </strong>
                                    </div>

                                    <?php if (!empty($comment->created_at)): ?>
                                    <small class="text-muted">
                                        <?= escape($comment->created_at) ?>
                                    </small>
                                    <?php endif; ?>
                                </div>

                                <p class="mb-0 mt-2">
                                    <?= nl2br(escape($comment->content ?? '')) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">
                        No comments yet.
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>