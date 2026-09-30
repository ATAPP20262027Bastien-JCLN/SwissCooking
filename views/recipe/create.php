<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12"> <a href="/recipes" class="btn btn-outline-secondary mb-4"> ← Back to recipes </a>
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h1 class="mb-4"> Add a new recipe </h1>
                    <?php if (! empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo escape($error) ?>
                    </div>
                    <?php
                    endif; ?>
                    <form method="POST" action="/recipe/create">
                        <div class="row g-4">

                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel">
                                    <h4 class="mb-4">Recipe details</h4>

                                    <div class="mb-4">
                                        <label for="name" class="form-label">Recipe name</label>
                                        <input type="text" class="form-control" id="name" name="name" maxlength="255"
                                            required value="<?php echo escape($data['name'] ?? '') ?>">
                                    </div>

                                    <div class="mb-4">
                                        <label for="category_id" class="form-label">Category</label>

                                        <select class="form-select" id="category_id" name="category_id" required>

                                            <option value>Select a category</option>

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

                                        <textarea class="form-control" id="description" name="description" rows="6" style="resize: none;"
                                            required><?php echo escape($data['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel d-flex flex-column">

                                    <div class="recipe-panel-header">
                                        <h4 class="mb-4">Ingredients</h4>
                                    </div>

                                    <div id="ingredients-container" class="recipe-scroll flex-grow-1">

                                        <div class="ingredient-row mb-3">

                                            <div class="row g-2">

                                                <div class="col-12">
                                                    <select class="form-select" name="ingredient_id[]" required>

                                                        <option value>
                                                            Select an ingredient
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
                                                        min="0.01" max="99999999.99" step="0.01" placeholder="Quantity"
                                                        required>
                                                </div>

                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="unit[]"
                                                        maxlength="100" placeholder="Unit" required>
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
                                        <button type="button" id="add-ingredient"
                                            class="btn btn-outline-secondary w-100">
                                            + Add ingredient
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="border rounded p-4 recipe-panel d-flex flex-column">

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
                                                    placeholder="Write a step" required>

                                                <button type="button" class="btn btn-outline-danger remove-step">
                                                    ×
                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="recipe-panel-footer pt-3">

                                        <button type="button" id="add-step" class="btn btn-outline-secondary w-100">
                                            + Add step
                                        </button>

                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="d-flex gap-2 mt-4"> <a href="/recipes" class="btn btn-outline-secondary"> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Create recipe </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ingredientContainer = document.getElementById('ingredients-container');
    const addIngredientButton = document.getElementById('add-ingredient');
    const firstIngredientRow = ingredientContainer.querySelector('.ingredient-row');
    addIngredientButton.addEventListener('click', () => {
        const row = firstIngredientRow.cloneNode(true);
        row.querySelectorAll('input').forEach((input) => {
            input.value = '';
        });
        row.querySelectorAll('select').forEach((select) => {
            select.selectedIndex = 0;
        });
        ingredientContainer.appendChild(row);
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
    }); /*     * Steps     */
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
        row.innerHTML =
            `<div class="input-group">
                <span class="input-group-text"></span>
                <input type="text" class="form-control" name="steps[]" placeholder="Write a step" required>
                <button type="button" class="btn btn-outline-danger remove-step">×</button>
            </div>`;
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