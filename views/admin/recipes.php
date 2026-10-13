<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Recettes</h1>
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
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th>Auteur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recipes as $recipe): ?>
                                <tr>
                                    <td><?= (int) $recipe->id ?></td>
                                    <td>
                                        <?= htmlspecialchars($recipe->name ?? '') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($recipe->category ?? '—') ?>
                                    </td>
                                    <td>
                                        <span class="badge text-bg-secondary">
                                            <?= htmlspecialchars($recipe->status ?? '') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= $recipe->user_id === null
                                            ? '—'
                                            : (int) $recipe->user_id ?>
                                    </td>
                                    <td>
                                        <a href="/admin/recipes/<?= (int) $recipe->id ?>/edit" class="btn btn-sm btn-primary">
                                            Modifier
                                        </a>
                                        <form
                                            method="POST"
                                            action="/admin/recipes/<?= (int) $recipe->id ?>/delete"
                                            class="d-inline"
                                        >
                                            <!-- Add your CSRF token here. -->
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
    
                            <?php if (empty($recipes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Aucune recette trouvée.
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