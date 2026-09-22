use SwissCookingDB;

DELETE FROM recipe_ingredients;
DELETE FROM comments;
DELETE FROM ratings;
DELETE FROM favorites;
DELETE FROM recipes;
DELETE FROM users;
DELETE FROM ingredients;
DELETE FROM categories;

ALTER TABLE recipe_ingredients AUTO_INCREMENT = 1;
ALTER TABLE comments AUTO_INCREMENT = 1;
ALTER TABLE ratings AUTO_INCREMENT = 1;
ALTER TABLE favorites AUTO_INCREMENT = 1;
ALTER TABLE recipes AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE ingredients AUTO_INCREMENT = 1;
ALTER TABLE categories AUTO_INCREMENT = 1;

SELECT * FROM roles;

-- =========================
-- INSERT INGREDIENTS
-- =========================

INSERT INTO ingredients (name, description) VALUES
('Spaghetti', 'Long, thin pasta.'),
('Ground Beef', 'Minced beef meat.'),
('Tomato Sauce', 'Sauce made from tomatoes.'),
('Chicken', 'Poultry meat.'),
('Curry Powder', 'A blend of spices used in Indian cooking.'),
('Vegetables', 'A mix of fresh vegetables.');


-- =========================
-- INSERT CATEGORIES
-- =========================

INSERT INTO categories (name, description) VALUES
('Italian', 'Traditional Italian cuisine.'),
('Indian', 'Spicy and flavorful Indian dishes.'),
('Asian', 'Diverse dishes from various Asian countries.');


-- =========================
-- INSERT USERS
-- =========================

INSERT INTO users (name, email, password_hash, id_role) VALUES
    ('Admin', 'admin.dev@right.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 2),
    ('Alice', 'alice@example.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm.', 1),
    ('Bob', 'bob@example.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
    ('Charlie', 'charlie@example.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1);


-- =========================
-- INSERT RECIPES
-- =========================


INSERT INTO recipes (name, description, steps, user_id, category_id) VALUES
('Spaghetti Bolognese', 'A classic Italian pasta dish with a rich meat sauce.', '1. Cook the spaghetti according to the package instructions. |2. In a large pan, brown the ground beef until no longer pink. |3. Add the tomato sauce and simmer for 10 minutes. |4. Serve the sauce over the cooked spaghetti.', 1, 1),
('Chicken Curry', 'A flavorful and spicy chicken curry.', '1. In a large pan, cook the chicken until no longer pink. |2. Add the curry powder and cook for another minute. |3. Add the vegetables and cook until tender. |4. Serve hot with rice.', 2, 2),
('Vegetable Stir Fry', 'A quick and healthy vegetable stir fry.', '1. Heat oil in a large pan over high heat. |2. Add the vegetables and stir-fry for 3-5 minutes until crisp-tender. |3. Add any desired seasonings and stir to combine. |4. Serve hot with rice or noodles.', 3, 3);


-- =========================
-- INSERT COMMENTS
-- =========================

INSERT INTO comments (user_id, recipe_id, content, created_at) VALUES
(1, 1, 'Delicious! My family loved it.', CURRENT_TIMESTAMP),
(2, 2, 'The curry was a bit too spicy for me.', CURRENT_TIMESTAMP),
(3, 3, 'Quick and easy to make.', CURRENT_TIMESTAMP);


-- =========================
-- INSERT RATINGS
-- =========================

INSERT INTO ratings (user_id, recipe_id, score) VALUES
(1, 1, 5),
(2, 2, 4),
(3, 3, 5);


-- =========================
-- INSERT FAVORITES
-- =========================

INSERT INTO favorites (user_id, recipe_id) VALUES
(1, 2),
(1, 3),
(2, 1),
(2, 3),
(3, 1),
(3, 2);


-- =========================
-- INSERT RECIPE INGREDIENTS
-- =========================

INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit) VALUES
(1, 1, 200, 'grams'),
(1, 2, 150, 'grams'),
(1, 3, 100, 'ml'),
(2, 4, 300, 'grams'),
(2, 5, 2, 'tablespoons'),
(3, 6, 250, 'grams');