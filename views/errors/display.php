<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 text-center">
            <div class="card h-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <h1 class="display-1 fw-bold mb-0"><?= escape($statusCode) ?></h1>
                        <h2 class="mt-2"><?= escape($title) ?></h2>
                    </div>

                    <p class="lead text-muted mb-4">
                        <?= escape($message) ?>
                    </p>

                    <p class="text-muted mb-4">
                        <?= escape($description) ?>
                    </p>

                    <a href="/" class="btn btn-primary px-4">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>