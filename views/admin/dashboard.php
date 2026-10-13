<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">Administration</h1>
            <p class="text-body-secondary mb-0">
                Gestion de Swiss Cooking
            </p>
        </div>
    </div>

    <div class="row g-4">
        <?php
        ?>

        <?php foreach ($cards as $card): ?>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h6 text-body-secondary">
                            <?= htmlspecialchars($card['title']) ?>
                        </h2>

                        <p class="display-5 fw-semibold mb-3">
                            <?= (int) $card['count'] ?>
                        </p>

                        <a
                            href="<?= htmlspecialchars($card['url']) ?>"
                            class="btn btn-outline-primary"
                        >
                            Gérer
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>