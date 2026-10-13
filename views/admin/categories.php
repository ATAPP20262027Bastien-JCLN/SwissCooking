<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Catégories</h1>

        <div class="d-flex gap-2">
            <a href="/admin" class="btn btn-outline-secondary">
                Retour
            </a>
            <a href="/admin/categories/new" class="btn btn-primary">
                Nouvelle catégorie
            </a>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Opération effectuée avec succès.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars((string) $_GET['error']) ?>
        </div>
    <?php endif; ?>

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
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= (int) $category->id ?></td>
                                    <td>
                                        <?= htmlspecialchars($category->name ?? '') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($category->description ?? '—') ?>
                                    </td>
                                    <td class="text-end">
                                        <a
                                            href="/admin/categories/<?= (int) $category->id ?>/edit"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Modifier
                                        </a>
    
                                        <form
                                            method="POST"
                                            action="/admin/categories/<?= (int) $category->id ?>/delete"
                                            class="d-inline"
                                            onsubmit="return confirm('Supprimer cette catégorie ?')"
                                        >
                                            <!-- Add your CSRF token here. -->
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
    
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        Aucune catégorie trouvée.
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.search.includes('success') || window.location.search.includes('error')) {
            setTimeout(function () {
                window.location.href = '/admin/categories';
            }, 3000);
        }
    });
</script>