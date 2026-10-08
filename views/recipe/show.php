<div class="container py-5">
    <div class="row justify-content-center main-content">
        <div class="col-12 col-lg-9">
            <a href="/recipes" class="btn btn-outline-secondary mb-4">
                ← Retour aux recettes
            </a>
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5 position-relative">
                    <div class="position-absolute top-0 end-0 mt-3 me-3 d-flex gap-2">
                        <?php if ($user !== null): ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="/recipe/<?php echo escape($recipe->id) ?>/favorite" method="POST"
                            class="d-inline">
                            <button type="submit"
                                class="btn btn-sm <?php echo $isFavorite ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                <?php echo $isFavorite ? '♥' : '♡'; ?>
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if ((int) ($_SESSION['user_role'] ?? 0) === 1 || (int) $recipe->user_id === (int) ($_SESSION['user_id'] ?? 0)): ?>
                        <a href="/recipe/<?php echo escape($recipe->id) ?>/edit" class="btn btn-sm btn-outline-primary">
                            Modifier
                        </a>
                        <form action="/recipe/<?php echo escape($recipe->id) ?>/delete" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Supprimer
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <span class="badge bg-secondary">
                            <?php echo escape($recipe->category) ?>
                        </span>
                    </div>
                    <h1 class="mb-3">
                        <?php echo escape($recipe->name) ?>
                    </h1>
                    <?php if ($user): ?>
                    <p class="text-muted">
                        Recette de
                        <a href="/user/<?php echo escape($user->id ?? 0) ?>"
                            style="text-decoration: none; color: inherit;">
                            <span class="visually-hidden">View profile of </span>
                            <?php echo escape($user->name ?? 'Unknown') ?>
                        </a>
                    </p>
                    <?php endif; ?>
                    <hr>
                    <h3 class="mt-4">Description</h3>
                    <p>
                        <?php echo nl2br(escape($recipe->description)) ?>
                    </p>
                    <div class="recipe-ingredients">
                        <h3 class="mt-5">Ingredients</h3>
                        <?php if (! empty($recipe->ingredients)): ?>
                        <div class="list-group">
                            <?php foreach ($recipe->ingredients as $ingredient): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">
                                            <?php echo escape($ingredient->name ?? '') ?>
                                        </h5>
                                        <?php if (! empty($ingredient->description)): ?>
                                        <p class="mb-1 text-muted">
                                            <?php echo escape($ingredient->description) ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($ingredient->quantity !== null || $ingredient->unit !== null): ?>
                                    <span class="badge bg-secondary">
                                        <?php echo escape((string) ($ingredient->quantity ?? '')) ?>
                                        <?php echo escape($ingredient->unit ?? '') ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-muted">
                            Aucun ingrédient listé.
                        </p>
                        <?php endif; ?>
                    </div>
                    <h3 class="mt-5">Instructions</h3>
                    <div class="recipe-instructions">
                        <?php if (! empty($recipe->steps)): ?>
                        <p class="text-muted">
                            <?php $steps = explode('|', $recipe->steps); ?>
                            <?php foreach ($steps as $index => $step): ?>
                            <?php echo $index + 1 ?>. <?php echo escape(trim($step)) ?><br>
                            <?php endforeach; ?>
                        </p>
                        <?php else: ?>
                        <p class="text-muted">
                            Aucune instruction listée.
                        </p>
                        <?php endif; ?>
                    </div>
                    <h3 class="mt-5">Note</h3>
                    <div class="mb-3">
                        <?php if ($recipe->averageRating !== null): ?>
                        <span class="fs-4">
                            <?php echo number_format($recipe->averageRating, 2); ?>/5
                        </span>
                        <?php else: ?>
                        <span class="text-muted">
                            Aucune note.
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="/recipe/<?php echo escape($recipe->id); ?>/rate" method="POST">
                        <p class="mb-2">
                            <?php if ($userRating !== null): ?>
                            Votre note:
                            <strong><?php echo escape($userRating); ?>/5</strong>
                            <?php else: ?>
                            Notez cette recette:
                            <?php endif; ?>
                        </p>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <button id="<?php echo $i ?>" type="submit" name="score" value="<?php echo $i; ?>"
                                class="rating-star <?php echo $userRating !== null && $i <= $userRating ? 'selected' : ''; ?>"
                                aria-label="Rate <?php echo $i; ?> out of 5">★
                            </button>
                            <?php endfor; ?>
                            <?php if ($userRating !== null): ?>
                            <button type="submit" name="score" value="0" class="btn btn-sm btn-outline-secondary ms-2">
                                Supprimer la note
                            </button>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                        <h3 class="mb-0">Commentaires</h3>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#commentModal">
                            + Ajouter un commentaire
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php if (! empty($recipe->comments)): ?>
                    <div class="comments-list">
                        <?php foreach ($recipe->comments as $comment): ?>
                        <div class="card mb-3 border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo escape("/" . $comment->user_profile_picture === "/" ? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user_name ?? 'Anonymous') . '&size=256' : "/" . $comment->user_profile_picture) ?>"
                                            alt="" class="rounded-circle" width="40" height="40">
                                        <a href="/user/<?php echo escape($comment->user_id ?? 0) ?>"
                                            style="text-decoration: none; color: inherit;">
                                            <strong>
                                                <?php echo escape($comment->user_name ?? 'Anonymous') ?>
                                            </strong>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (! empty($comment->created_at)): ?>
                                        <small class="text-muted">
                                            <?php echo escape($comment->created_at) ?>
                                        </small>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['user_id'])&& ((int) $_SESSION['user_id'] === (int) $comment->user_id|| (int) ($_SESSION['user_role'] ?? 0) === 1)): ?>
                                        <form action="/comment/<?php echo escape($comment->id); ?>/delete" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Supprimer
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="mb-0 mt-2">
                                    <?php echo nl2br(escape($comment->content ?? '')) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">
                        Aucun commentaire pour le moment.
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_SESSION['user_id'])): ?>
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">
                    Ajouter un commentaire
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="/recipe/<?php echo escape($recipe->id); ?>/comment" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="commentContent" class="form-label">
                            Votre commentaire
                        </label>
                        <textarea class="form-control" id="commentContent" name="content" rows="5" maxlength="5000"
                            required placeholder="Écrivez votre commentaire..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Publier le commentaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const stars = document.querySelectorAll('.rating-star');
let selectedRating = <?php echo $userRating !== null ? (int) $userRating : 0; ?>;
stars.forEach((star, index) => {
    star.addEventListener('mouseover', () => {
        stars.forEach((s, i) => {
            s.classList.remove('selected');
            if (i <= index) {
                s.classList.add('hovered');
            } else {
                s.classList.remove('hovered');
            }
        });
    });
    star.addEventListener('mouseout', () => {
        stars.forEach((s) => {
            s.classList.remove('hovered');
        });
        stars.forEach((s, i) => {
            if (i < selectedRating) {
                s.classList.add('selected');
            } else {
                s.classList.remove('selected');
            }
        });
    });
    star.addEventListener('click', () => {
        selectedRating = index + 1;
        stars.forEach((s, i) => {
            s.classList.remove('hovered');
            if (i < selectedRating) {
                s.classList.add('selected');
            } else {
                s.classList.remove('selected');
            }
        });
    });
});
</script>