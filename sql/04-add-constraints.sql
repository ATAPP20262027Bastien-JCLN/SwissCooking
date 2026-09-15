USE SwissCookingDB;

-- =========================================
-- UNIQUE constraints
-- =========================================

ALTER TABLE roles
    ADD CONSTRAINT uq_roles_name
    UNIQUE (name);

ALTER TABLE users
    ADD CONSTRAINT uq_users_email
    UNIQUE (email);

ALTER TABLE categories
    ADD CONSTRAINT uq_categories_name
    UNIQUE (name);

ALTER TABLE ingredients
    ADD CONSTRAINT uq_ingredients_name
    UNIQUE (name);

ALTER TABLE recipe_ingredients
    ADD CONSTRAINT uq_recipe_ingredients
    UNIQUE (recipe_id, ingredient_id);

ALTER TABLE favorites
    ADD CONSTRAINT uq_favorites_user_recipe
    UNIQUE (user_id, recipe_id);

ALTER TABLE ratings
    ADD CONSTRAINT uq_ratings_user_recipe
    UNIQUE (user_id, recipe_id);


-- =========================================
-- FOREIGN KEY constraints
-- =========================================

ALTER TABLE users
    ADD CONSTRAINT fk_users_role
    FOREIGN KEY (id_role)
    REFERENCES roles(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT;

ALTER TABLE recipes
    ADD CONSTRAINT fk_recipes_user
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE recipes
    ADD CONSTRAINT fk_recipes_category
    FOREIGN KEY (category_id)
    REFERENCES categories(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE recipe_ingredients
    ADD CONSTRAINT fk_recipe_ingredients_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE recipe_ingredients
    ADD CONSTRAINT fk_recipe_ingredients_ingredient
    FOREIGN KEY (ingredient_id)
    REFERENCES ingredients(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE favorites
    ADD CONSTRAINT fk_favorites_user
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE favorites
    ADD CONSTRAINT fk_favorites_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE ratings
    ADD CONSTRAINT fk_ratings_user
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE ratings
    ADD CONSTRAINT fk_ratings_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE comments
    ADD CONSTRAINT fk_comments_user
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE comments
    ADD CONSTRAINT fk_comments_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;


-- =========================================
-- CHECK constraint for rating score
-- =========================================

ALTER TABLE ratings
    ADD CONSTRAINT chk_ratings_score
    CHECK (score BETWEEN 1 AND 5);