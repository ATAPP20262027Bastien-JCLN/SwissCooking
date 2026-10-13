<?php $isEditing = $category !== null; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">
                    <?= $isEditing ? 'Modifier la catégorie' : 'Nouvelle catégorie' ?>
                </h1>
                <a href="/admin/categories" class="btn btn-outline-secondary">
                    Retour
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="/admin/categories/save">
                        <!-- Add your CSRF token here. -->

                        <?php if ($isEditing): ?>
                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int) $category->id ?>"
                            >
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nom
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="255"
                                required
                                value="<?= htmlspecialchars(
                                    $category->name ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Description
                            </label>
                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="4"
                            ><?= htmlspecialchars(
                                $category->description ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <?= $isEditing ? 'Enregistrer' : 'Créer' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>