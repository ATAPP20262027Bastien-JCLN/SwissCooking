<div class="container py-5">

    <div class="row">
        <div class="col-12">

            <h2 class="mb-4">Categories</h2>

            <?php if (!empty($categories)): ?>

                <div class="row g-4">

                    <?php foreach ($categories as $category): ?>

                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">

                            <a
                                href="/recipes?search=<?= urlencode($category->name) ?>+&fromCategory=1"
                                class="text-decoration-none"
                            >

                                <div class="card h-100 shadow-sm category-card">

                                    <div class="card-body text-center">

                                        <h5 class="card-title mb-0">
                                            <?= escape($category->name) ?>
                                        </h5>

                                    </div>

                                </div>

                            </a>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="text-center py-5">
                    <p class="lead">No categories available.</p>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>