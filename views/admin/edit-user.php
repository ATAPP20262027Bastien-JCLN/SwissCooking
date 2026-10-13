<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">Modifier un utilisateur</h1>
                <a href="/admin/users" class="btn btn-outline-secondary">
                    Retour
                </a>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars(
                        (string) $_GET['error'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form
                        method="POST"
                        action="/admin/users/<?= (int) $user->id ?>/edit"
                    >
                        <!-- Add your CSRF token here. -->

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="100"
                                required
                                value="<?= htmlspecialchars(
                                    $user->name ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                                value="<?= htmlspecialchars(
                                    $user->email ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="id_role" class="form-label">Rôle</label>
                            <select
                                class="form-select"
                                id="id_role"
                                name="id_role"
                                required
                            >
                                <?php foreach ($roles as $roleId => $role): ?>
                                    <option
                                        value="<?= (int) $roleId ?>"
                                        <?= (int) $user->id_role === (int) $roleId
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            $role->name,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Enregistrer les modifications
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>