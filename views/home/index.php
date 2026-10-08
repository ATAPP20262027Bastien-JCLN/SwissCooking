<div class="container py-5">
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-8 text-center">
            <h1 class="mt-0">Bienvenue sur Swiss Cooking</h1>
            <p class="lead">Découvrez les meilleures recettes suisses et conseils de cuisine!</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10 text-center">
            <h2 class="mb-2">Meilleures recettes</h2>
            <?php if (! empty($recipes)): ?>
            <div class="row justify-content-center g-4">
                <?php foreach ($recipes as $recipe): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm text-start">
                        <a href="/recipe/<?php echo $recipe->id ?>" class="home-recipe-card">
                            <div class="card-body p-4">
                                <h5 class="card-title"><?php echo escape($recipe->name) ?></h5>
                                <p class="card-text">
                                    <strong>Catégorie:</strong>
                                    <?php echo escape($recipe->category) ?>
                                </p>
                                <p class="card-text">
                                    <strong>Auteur:</strong>
                                    <?php echo isset($users[$recipe->id]) ? escape($users[$recipe->id]->name) : 'Inconnu' ?>
                                </p>
                                <p class="card-text"><?php echo escape($recipe->description) ?></p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <strong>Note moyenne:</strong>
                                        <?php echo $recipe->averageRating !== null ? number_format($recipe->averageRating, 2) : 'Pas encore de notes'; ?>
                                    </small>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p>Il n'y a pas encore de recettes disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</div>