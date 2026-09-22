<div class="container py-5">
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-8 text-center">
            <h1 class="mt-0">Welcome to Swiss Cooking</h1>
            <p class="lead">Discover the best Swiss recipes and cooking tips!</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10 text-center">
            <h2 class="mb-2">Top Rated Recipes</h2>
            <?php if (!empty($recipes)) : ?>
                <div class="row justify-content-center g-4">
                    <?php foreach ($recipes as $recipe) : ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm text-start">
                                <a href="/recipe/<?= $recipe->id ?>" class="home-recipe-card">
                                    <div class="card-body p-4">
                                        <h5 class="card-title">
                                            <?= htmlspecialchars($recipe->name) ?>
                                        </h5>
                                        <p class="card-text">
                                            <strong>Category:</strong>
                                            <?= htmlspecialchars($recipe->category) ?>
                                        </p>
                                        <p class="card-text">
                                            <strong>Author:</strong>
                                            <?= isset($users[$recipe->id])
                                                ? htmlspecialchars($users[$recipe->id]->name)
                                                : 'Unknown' ?>
                                        </p>
                                        <p class="card-text">
                                            <?= htmlspecialchars($recipe->description) ?>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <strong>Average Rating:</strong>
                                                <?= $recipe->averageRating !== null
                                                    ? number_format($recipe->averageRating, 2)
                                                    : 'No ratings yet' ?>
                                            </small>
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p>No recipes available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>