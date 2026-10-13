<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Ingrédients</h1>
        <a href="/admin" class="btn btn-outline-secondary">
            Retour au dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="admin-table-scroll">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ingredients as $ingredient): ?>
                                <tr>
                                    <td><?= (int) $ingredient->id ?></td>
                                    <td>
                                        <?= htmlspecialchars($ingredient->name ?? '') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($ingredient->description ?? '') ?>
                                    </td>
                                    <td>
                                        <a
                                            href="/admin/ingredients/<?= (int) $ingredient->id ?>/edit"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Modifier
                                        </a>

                                        <form
                                            method="POST"
                                            action="/admin/ingredients/<?= (int) $ingredient->id ?>/delete"
                                            class="d-inline"
                                        >
                                            <!-- Add your CSRF token here. -->
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ingrédient ?')"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
    
                            <?php if (empty($ingredients)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        Aucun ingrédient trouvé.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>