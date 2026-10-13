<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Utilisateurs</h1>
        <a href="/admin" class="btn btn-outline-secondary">
            Retour au dashboard
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Modification effectuée avec succès.
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
                                <th>Email</th>
                                <th>Rôle</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= (int) $user->id ?></td>
                                    <td>
                                        <?= htmlspecialchars($user->name ?? '') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($user->email ?? '') ?>
                                    </td>
                                    <td><?= (int) $user->id_role ?></td>
                                    <td class="text-end">
                                        <a
                                            href="/admin/users/<?= (int) $user->id ?>/edit"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Modifier
                                        </a>
                                        <form
                                            method="POST"
                                            action="/admin/users/<?= (int) $user->id ?>/block"
                                            class="d-inline"
                                        >
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir bloquer cet utilisateur ?');"
                                            >
                                                <?= $user->blocked ? 'Débloquer' : '&nbsp;Bloquer&nbsp;' ?>
                                            </button>
                                        </form>
                                        <form
                                            method="POST"
                                            action="/admin/users/<?= (int) $user->id ?>/delete"
                                            class="d-inline"
                                        >
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
    
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Aucun utilisateur trouvé.
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