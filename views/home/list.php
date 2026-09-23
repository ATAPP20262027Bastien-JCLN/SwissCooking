<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">All Recipes</h2>

                <div style="max-width: 350px; width: 100%;">
                    <input
                        type="text"
                        id="recipeSearch"
                        class="form-control"
                        placeholder="Search recipes..."
                        autocomplete="off"
                    >
                </div>
            </div>

            <?php if (! empty($recipes)): ?>

                <div class="row g-4 main-content" id="recipeList">
                    <?php foreach ($recipes as $recipe): ?>

                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 shadow-sm">

                                <div class="card-body d-flex flex-column">

                                    <h5 class="card-title">
                                        <?php echo escape($recipe->name) ?>
                                    </h5>

                                    <p class="card-text mb-2">
                                        <strong>Category:</strong>
                                        <?php echo escape($recipe->category) ?>
                                    </p>

                                    <p class="card-text mb-2">
                                        <strong>Author:</strong>
                                        <?php echo isset($users[$recipe->id]) ? escape($users[$recipe->id]->name) : 'Unknown' ?>
                                    </p>

                                    <p class="card-text">
                                        <?php echo escape($recipe->description) ?>
                                    </p>

                                    <div class="mt-auto">

                                        <p class="card-text mb-3">
                                            <small class="text-muted">
                                                <strong>Average Rating:</strong>

                                                <?php if ($recipe->averageRating !== null): ?>
                                                    <?php echo number_format($recipe->averageRating, 2) ?>/5
                                                <?php else: ?>
                                                    No ratings yet
                                                <?php endif; ?>
                                            </small>
                                        </p>

                                        <a
                                            href="/recipe/<?php echo escape((string) $recipe->id) ?>"
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

            <?php else: ?>

                <div class="text-center py-5">
                    <p class="lead">No recipes available.</p>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('recipeSearch');
    const recipeList = document.getElementById('recipeList');

    searchInput.addEventListener('input', async function () {
        const search = this.value.trim();

        try {
            const response = await fetch(
                '/recipes?search=' + encodeURIComponent(search),
                {
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            recipeList.innerHTML = await response.text();

        } catch (error) {
            console.error('Search error:', error);
        }
    });
</script>