<div class="container py-5">

    <div class="row">
        <div class="col-12">

            <h2 class="mb-4">Categories</h2>

            <?php if (!empty($categories)): ?>

                <div class="row g-4">

                    <?php foreach ($categories as $category): ?>

                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">

                            <form action="/recipes" method="POST">

                                <input type="hidden" name="category" value="<?= escape($category->name) ?>">

                                <button type="submit" class="card h-100 shadow-sm category-card w-100 border-0">
                                    <div class="card-body text-center">
                                        <h5 class="card-title mb-0">
                                            <?= escape($category->name) ?>
                                        </h5>
                                    </div>
                                </button>

                            </form>

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

<script>
    document.querySelectorAll('.category-link').forEach(function (link) {

        link.addEventListener('click', function (event) {
            event.preventDefault();

            const category = this.dataset.category;

            history.pushState(
                { fromCategory: true, category: category },
                '',
                '/recipes'
            );

            window.location.href =
                '/recipes?search=' + encodeURIComponent(category)
                + '&fromCategory=1';
        });

    });
</script>