<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12"> <a href="/recipes" class="btn btn-outline-secondary mb-4"> ← Retour aux recettes </a>
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h1 class="mb-4">Ajouter une nouvelle recette</h1>
                    <?php if (! empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo escape($error) ?>
                    </div>
                    <?php endif; ?>
                    <form method="POST" action="/recipe/create">
                        <div class="row g-4">
                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel">
                                    <h4 class="mb-4">Détails de la recette</h4>
                                    <div class="mb-4">
                                        <label for="name" class="form-label">Nom de la recette</label>
                                        <input type="text" class="form-control" id="name" name="name" maxlength="255"
                                            value="<?php echo escape($data['name'] ?? '') ?>">
                                    </div>
                                    <div class="mb-4">
                                        <label for="category_id" class="form-label">Catégorie</label>
                                        <select class="form-select" id="category_id" name="category_id">
                                            <option value>Selectionnez une catégorie</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo escape($category->id) ?>"
                                                <?php echo (int) ($data['category_id'] ?? 0) === (int) $category->id ? 'selected' : '' ?>>
                                                <?php echo escape($category->name) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="description" class="form-label">
                                            Description
                                        </label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            style="resize: none;"><?php echo escape($data['description'] ?? '') ?></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="status" class="form-label">Statut</label>

                                        <select class="form-select" id="status" name="status" required>
                                            <option value="draft"
                                                <?php echo($data['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>
                                                Brouillon
                                            </option>

                                            <option value="private"
                                                <?php echo($data['status'] ?? 'draft') === 'private' ? 'selected' : '' ?>>
                                                Privé
                                            </option>

                                            <option value="published"
                                                <?php echo($data['status'] ?? 'draft') === 'published' ? 'selected' : '' ?>>
                                                Publique
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel d-flex flex-column recipe-panel-desktop">
                                    <div class="recipe-panel-header">
                                        <h4 class="mb-4">Ingredients</h4>
                                    </div>
                                    <div id="ingredients-container" class="recipe-scroll flex-grow-1">
                                        <div class="ingredient-row mb-3">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <select class="form-select ingredient-select" name="ingredient_id[]">
                                                        <option value="" disabled selected>
                                                            Selectionnez un ingrédient
                                                        </option>
                                                        <?php foreach ($ingredients as $ingredient): ?>
                                                        <option value="<?php echo escape($ingredient->id) ?>">
                                                            <?php echo escape($ingredient->name); ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <input type="number" class="form-control" name="quantity[]"
                                                        min="0.01" max="99999999.99" step="0.01" placeholder="Quantité">
                                                </div>
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="unit[]"
                                                        maxlength="100" placeholder="Unité">
                                                </div>
                                                <div class="col-2">
                                                    <button type="button"
                                                        class="btn btn-outline-danger w-100 remove-ingredient">
                                                        ×
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="recipe-panel-footer pt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                            data-bs-toggle="modal" data-bs-target="#addIngredientModal">
                                            + Créer un nouvel ingrédient
                                        </button>
                                        <button type="button" id="add-ingredient"
                                            class="btn btn-outline-secondary w-100">
                                            + Ajouter un ingrédient
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel d-flex flex-column recipe-panel-desktop">
                                    <div class="recipe-panel-header">
                                        <h4 class="mb-4">Instructions</h4>
                                    </div>
                                    <div id="steps-container" class="recipe-scroll flex-grow-1">
                                        <div class="step-row mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    1
                                                </span>
                                                <input type="text" class="form-control" name="steps[]"
                                                    placeholder="Écrire une étape">
                                                <button type="button" class="btn btn-outline-danger remove-step">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="recipe-panel-footer pt-3">
                                        <button type="button" id="add-step" class="btn btn-outline-secondary w-100">
                                            + Ajouter une étape
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4"> <a href="/recipes" class="btn btn-outline-secondary"> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Créer la recette
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addIngredientModal" tabindex="-1" aria-labelledby="addIngredientModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="new-ingredient-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIngredientModalLabel">
                        Créer un ingrédient
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <div id="ingredient-message" class="alert d-none" role="alert"></div>

                    <div class="mb-3">
                        <label for="new-ingredient-name" class="form-label">
                            Nom de l'ingrédient
                        </label>
                        <input type="text" class="form-control" id="new-ingredient-name" name="name" maxlength="255"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="new-ingredient-description" class="form-label">
                            Description
                        </label>
                        <textarea class="form-control" id="new-ingredient-description" name="description"
                            rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" id="save-ingredient" class="btn btn-primary">
                        Créer l'ingrédient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ingredientContainer = document.getElementById('ingredients-container');
    const addIngredientButton = document.getElementById('add-ingredient');

    function initializeIngredientSelect(select) {
        if (select.tomselect) {
            return;
        }

        new TomSelect(select, {
            create: false,
            placeholder: 'Rechercher un ingrédient...',
            allowEmptyOption: false,
            maxOptions: null,
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });
    }

    ingredientContainer
        .querySelectorAll('select[name="ingredient_id[]"]')
        .forEach(initializeIngredientSelect);

    addIngredientButton.addEventListener('click', () => {
        const firstRow = ingredientContainer.querySelector('.ingredient-row');
        const originalSelect = firstRow.querySelector('select');

        const row = document.createElement('div');
        row.className = 'ingredient-row mb-3';

        row.innerHTML = `
            <div class="row g-2">
                <div class="col-12 ingredient-select-container"></div>
                <div class="col-5">
                    <input type="number" class="form-control"
                        name="quantity[]" min="0.01" max="99999999.99"
                        step="0.01" placeholder="Quantité" required>
                </div>
                <div class="col-5">
                    <input type="text" class="form-control"
                        name="unit[]" maxlength="100"
                        placeholder="Unité" required>
                </div>
                <div class="col-2">
                    <button type="button"
                        class="btn btn-outline-danger w-100 remove-ingredient">
                        ×
                    </button>
                </div>
            </div>
        `;

        const select = document.createElement('select');
        select.className = 'form-select ingredient-select';
        select.name = 'ingredient_id[]';
        select.required = true;

        originalSelect.querySelectorAll('option').forEach((option, index) => {
            const copy = option.cloneNode(true);

            copy.selected = false;

            select.add(copy);
        });

        select.selectedIndex = 0;

        row.querySelector('.ingredient-select-container').appendChild(select);
        ingredientContainer.appendChild(row);

        initializeIngredientSelect(select);
    });

    ingredientContainer.addEventListener('click', (event) => {
        const removeButton = event.target.closest('.remove-ingredient');

        if (!removeButton || !ingredientContainer.contains(removeButton)) {
            return;
        }

        const rows = ingredientContainer.querySelectorAll('.ingredient-row');

        if (rows.length === 1) {
            return;
        }

        const row = removeButton.closest('.ingredient-row');
        const select = row.querySelector('select[name="ingredient_id[]"]');

        if (select && select.tomselect) {
            select.tomselect.destroy();
        }

        row.remove();
    });

    const stepContainer = document.getElementById('steps-container');
    const addStepButton = document.getElementById('add-step');

    function updateStepNumbers() {
        const rows = stepContainer.querySelectorAll('.step-row');

        rows.forEach((row, index) => {
            row.querySelector('.input-group-text').textContent = index + 1;
        });
    }

    addStepButton.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'step-row mb-3';

        row.innerHTML = `
            <div class="input-group">
                <span class="input-group-text"></span>
                <input type="text"
                    class="form-control"
                    name="steps[]"
                    placeholder="Écrire une étape"
                    required>
                <button type="button"
                    class="btn btn-outline-danger remove-step">
                    ×
                </button>
            </div>
        `;

        stepContainer.appendChild(row);
        updateStepNumbers();
        row.querySelector('input').focus();
    });

    stepContainer.addEventListener('click', (event) => {
        const removeButton = event.target.closest('.remove-step');

        if (!removeButton || !stepContainer.contains(removeButton)) {
            return;
        }

        const rows = stepContainer.querySelectorAll('.step-row');

        if (rows.length === 1) {
            return;
        }

        removeButton.closest('.step-row').remove();
        updateStepNumbers();
    });

    updateStepNumbers();

    const newIngredientForm = document.getElementById('new-ingredient-form');
    const ingredientMessage = document.getElementById('ingredient-message');
    const saveIngredientButton = document.getElementById('save-ingredient');

    newIngredientForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const name = document
            .getElementById('new-ingredient-name')
            .value.trim();

        const description = document
            .getElementById('new-ingredient-description')
            .value.trim();

        ingredientMessage.className = 'alert d-none';
        ingredientMessage.textContent = '';

        if (!name) {
            ingredientMessage.textContent = 'Le nom est obligatoire.';
            ingredientMessage.className = 'alert alert-danger';
            return;
        }

        saveIngredientButton.disabled = true;
        saveIngredientButton.textContent = 'Création...';

        try {
            const response = await fetch('/ingredients/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    description
                })
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.error || 'Impossible de créer cet ingrédient.'
                );
            }

            const ingredientId = String(result.ingredient.id);
            const ingredientName = result.ingredient.name;

            ingredientContainer
                .querySelectorAll('select[name="ingredient_id[]"]')
                .forEach((select) => {
                    if (select.tomselect) {
                        select.tomselect.addOption({
                            value: ingredientId,
                            text: ingredientName
                        });
                    } else {
                        select.add(
                            new Option(ingredientName, ingredientId)
                        );
                    }
                });

            const rows = ingredientContainer.querySelectorAll('.ingredient-row');
            const lastRow = rows[rows.length - 1];

            const lastSelect = lastRow.querySelector(
                'select[name="ingredient_id[]"]'
            );

            if (lastSelect.tomselect) {
                lastSelect.tomselect.setValue(ingredientId);
            } else {
                lastSelect.value = ingredientId;
            }

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('addIngredientModal')
            ).hide();

            newIngredientForm.reset();

            ingredientMessage.className = 'alert d-none';
            ingredientMessage.textContent = '';

        } catch (error) {
            ingredientMessage.textContent = error.message;
            ingredientMessage.className = 'alert alert-danger';
        } finally {
            saveIngredientButton.disabled = false;
            saveIngredientButton.textContent = "Créer l'ingrédient";
        }
    });
});
</script>