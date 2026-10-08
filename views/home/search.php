<?php if (! empty($recipes)): ?>
<?php foreach ($recipes as $recipe): ?>
<div class="col-12 col-md-6 col-lg-4 col-xl-3 recipe-card" data-category="<?php echo escape($recipe->category) ?>"
    data-rating="<?php echo $recipe->averageRating !== null ? escape((string) $recipe->averageRating) : '0' ?>"
    data-user="<?php echo isset($users[$recipe->id]) ? escape($users[$recipe->id]->name) : 'Inconnu' ?>">
    <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo escape($recipe->name) ?></h5>
            <p class="card-text mb-2">
                <strong>Catégorie:</strong>
                <?php echo escape($recipe->category) ?>
            </p>
            <p class="card-text mb-2">
                <strong>Auteur:</strong>
                <?php echo isset($users[$recipe->id]) ? escape($users[$recipe->id]->name) : 'Inconnu' ?>
            </p>
            <p class="card-text"><?php echo escape($recipe->description) ?></p>
            <div class="mt-auto">
                <p class="card-text mb-3">
                    <small class="text-muted">
                        <strong>Note moyenne:</strong>
                        <?php if ($recipe->averageRating !== null): ?>
                        <?php echo number_format($recipe->averageRating, 2) ?>/5
                        <?php else: ?> Aucune note
                        <?php endif; ?>
                    </small>
                </p>
                <a href="/recipe/<?php echo escape((string) $recipe->id) ?>" class="btn btn-primary w-100">
                    Voir la recette
                </a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="col-12 text-center py-5">
    <p class="lead">Aucune recette trouvée.</p>
</div>
<?php endif; ?>