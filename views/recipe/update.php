<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <a href="/recipe/<?php echo escape($recipe->id) ?>" class="btn btn-outline-secondary mb-4">
                ← Retour à la recette
            </a>
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h1 class="mb-4">
                        Modifier la recette
                    </h1>
                    <?php if (! empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo escape($error) ?>
                    </div>
                    <?php endif; ?>
                    <form method="POST" action="/recipe/<?php echo escape($recipe->id) ?>/edit">
                        <div class="row g-4">
                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel recipe-panel-desktop">
                                    <h4 class="mb-4">
                                        Détails de la recette
                                    </h4>
                                    <div class="mb-4">
                                        <label for="name" class="form-label">
                                            Nom de la recette
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" maxlength="255"
                                            required value="<?php echo escape($recipe->name ?? '') ?>">
                                    </div>
                                    <div class="mb-4">
                                        <label for="category_id" class="form-label">
                                            Catégorie
                                        </label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">
                                                Selectionnez une catégorie
                                            </option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo escape($category->id) ?>"
                                                <?php echo (int) ($recipe->category_id ?? 0) === (int) $category->id ? 'selected' : '' ?>>
                                                <?php echo escape($category->name) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="description" class="form-label">
                                            Description
                                        </label>
                                        <textarea class="form-control mb-3" id="description" name="description" rows="3"
                                            style="resize: none;"
                                            required><?php echo escape($recipe->description ?? '') ?></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="status" class="form-label">Recipe status</label>

                                        <select class="form-select" id="status" name="status" required>
                                            <?php foreach ([
                                                    'draft'     => 'Draft',
                                                    'private'   => 'Private',
                                                    'published' => 'Published',
                                            ] as $value => $label): ?>
                                            <option value="<?php echo escape($value) ?>"
                                                <?php echo($data['status'] ?? $recipe->status) === $value ? 'selected' : '' ?>>
                                                <?php echo escape($label) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel d-flex flex-column recipe-panel-desktop">
                                    <div class="recipe-panel-header">
                                        <h4 class="mb-4">
                                            Ingredients
                                        </h4>
                                    </div>
                                    <div id="ingredients-container" class="recipe-scroll flex-grow-1">
                                        <?php if (! empty($recipe->ingredients)): ?>
                                        <?php foreach ($recipe->ingredients as $recipeIngredient): ?>
                                        <div class="ingredient-row mb-3">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <select class="form-select ingredient-select" name="ingredient_id[]"
                                                        required>
                                                        <option value="" disabled>
                                                            Sélectionnez un ingrédient
                                                        </option>
                                                        <?php foreach ($ingredients as $availableIngredient): ?>
                                                        <option value="<?php echo escape($availableIngredient->id) ?>"
                                                            <?php echo (int) $availableIngredient->id === (int) $recipeIngredient->id ? 'selected' : '' ?>>
                                                            <?php echo escape($availableIngredient->name) ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <input type="number" class="form-control" name="quantity[]"
                                                        min="0.01" max="99999999.99" step="0.01" placeholder="Quantité"
                                                        required
                                                        value="<?php echo escape($recipeIngredient->quantity ?? '') ?>">
                                                </div>
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="unit[]"
                                                        maxlength="100" placeholder="Unité" required
                                                        value="<?php echo escape($recipeIngredient->unit ?? '') ?>">
                                                </div>
                                                <div class="col-2">
                                                    <button type="button"
                                                        class="btn btn-outline-danger w-100 remove-ingredient">
                                                        ×
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <div class="ingredient-row mb-3">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <select class="form-select ingredient-select" name="ingredient_id[]"
                                                        required>
                                                        <option value="" disabled selected>
                                                            Sélectionnez un ingrédient
                                                        </option>
                                                        <?php foreach ($ingredients as $availableIngredient): ?>
                                                        <option value="<?php echo escape($availableIngredient->id) ?>">
                                                            <?php echo escape($availableIngredient->name) ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <input type="number" class="form-control" name="quantity[]"
                                                        min="0.01" max="99999999.99" step="0.01" placeholder="Quantité"
                                                        required>
                                                </div>
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="unit[]"
                                                        maxlength="100" placeholder="Unité" required>
                                                </div>
                                                <div class="col-2">
                                                    <button type="button"
                                                        class="btn btn-outline-danger w-100 remove-ingredient">
                                                        ×
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
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
                                        <h4 class="mb-4">
                                            Instructions
                                        </h4>
                                    </div>
                                    <div id="steps-container" class="recipe-scroll flex-grow-1">
                                        <?php $steps = ! empty($recipe->steps) ? explode('|', $recipe->steps) : ['']; ?>
                                        <?php foreach ($steps as $step): ?>
                                        <div class="step-row mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    1
                                                </span>
                                                <input type="text" class="form-control" name="steps[]"
                                                    placeholder="Écrire une étape" required
                                                    value="<?php echo escape(trim($step)) ?>">
                                                <button type="button" class="btn btn-outline-danger remove-step">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="recipe-panel-footer pt-3">
                                        <button type="button" id="add-step" class="btn btn-outline-secondary w-100">
                                            + Ajouter une étape
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <a href="/recipe/<?php echo $recipe->id; ?>" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Enregistrer les modifications
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
            <div class="col-12">
                <select class="form-select ingredient-select"
                        name="ingredient_id[]" required>
                </select>
            </div>
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

        const newSelect = row.querySelector('select');

        // Copy the options, without copying the existing selection.
        originalSelect.querySelectorAll('option').forEach((option) => {
            const newOption = option.cloneNode(true);
            newOption.selected = false;
            newSelect.appendChild(newOption);
        });

        newSelect.selectedIndex = 0;

        ingredientContainer.appendChild(row);

        initializeIngredientSelect(newSelect);
    });

    ingredientContainer.addEventListener('click', (event) => {
        if (!event.target.classList.contains('remove-ingredient')) {
            return;
        }

        const rows = ingredientContainer.querySelectorAll('.ingredient-row');

        if (rows.length === 1) {
            return;
        }

        event.target.closest('.ingredient-row').remove();
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
                <input type="text" class="form-control" name="steps[]" placeholder="Écrire une étape" required>
                <button type="button" class="btn btn-outline-danger remove-step">
                    ×
                </button>
            </div>
        `;

        stepContainer.appendChild(row);
        updateStepNumbers();
        row.querySelector('input').focus();
    });

    stepContainer.addEventListener('click', (event) => {
        if (!event.target.classList.contains('remove-step')) {
            return;
        }

        const rows = stepContainer.querySelectorAll('.step-row');

        if (rows.length === 1) {
            return;
        }

        event.target.closest('.step-row').remove();

        updateStepNumbers();
    });

    updateStepNumbers();
});
</script>