USE SwissCookingDB;

-- ============================================================
-- 0. NETTOYAGE COMPLET
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
-- 2. UTILISATEURS
-- ============================================================

INSERT INTO users (name, email, password_hash, id_role)
VALUES
('Admin', 'admin.dev@right.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 2),
('Alice Martin',    'alice@example.com',    '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Bob Dupont',      'bob@example.com',      '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Charlie Bernard', 'charlie@example.com',  '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('David Thomas',    'david@example.com',    '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Emma Petit',      'emma@example.com',     '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Lucas Robert',    'lucas@example.com',    '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Chloe Richard',   'chloe@example.com',    '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Hugo Durand',     'hugo@example.com',     '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Lea Moreau',      'lea@example.com',      '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
('Nathan Simon',    'nathan@example.com',   '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1);


-- ============================================================
-- 3. CATÉGORIES
-- ============================================================

INSERT INTO categories (name, description)
VALUES
('Entrées', 'Petites préparations à servir avant le repas'),
('Plats', 'Plats principaux'),
('Desserts', 'Desserts et préparations sucrées'),
('Pâtes', 'Recettes à base de pâtes'),
('Pizza', 'Pizzas et préparations similaires'),
('Salades', 'Salades froides ou tièdes'),
('Soupes', 'Soupes, veloutés et potages'),
('Végétarien', 'Recettes sans viande ni poisson'),
('Viandes', 'Recettes à base de viande'),
('Poissons', 'Recettes à base de poisson'),
('Petit-déjeuner', 'Recettes pour le petit-déjeuner'),
('Asiatique', 'Recettes inspirées de la cuisine asiatique'),
('Italien', 'Recettes inspirées de la cuisine italienne'),
('Français', 'Recettes inspirées de la cuisine française'),
('Rapide', 'Recettes faciles et rapides à préparer');


-- ============================================================
-- 4. INGRÉDIENTS
-- ============================================================

INSERT INTO ingredients (name, description)
VALUES
('Farine', 'Farine de blé'),
('Sucre', 'Sucre blanc'),
('Sel', 'Sel de cuisine'),
('Poivre', 'Poivre noir'),
('Huile d''olive', 'Huile d''olive extra vierge'),
('Beurre', 'Beurre doux'),
('Œuf', 'Œufs de poule'),
('Lait', 'Lait de vache'),
('Crème fraîche', 'Crème fraîche'),
('Tomate', 'Tomates fraîches'),
('Tomates concassées', 'Tomates concassées'),
('Oignon', 'Oignon jaune'),
('Ail', 'Gousses d''ail'),
('Carotte', 'Carottes'),
('Courgette', 'Courgettes'),
('Poivron', 'Poivrons'),
('Pomme de terre', 'Pommes de terre'),
('Champignon', 'Champignons de Paris'),
('Brocoli', 'Brocolis'),
('Épinards', 'Épinards frais'),
('Poulet', 'Blanc de poulet'),
('Bœuf', 'Viande de bœuf'),
('Porc', 'Viande de porc'),
('Saumon', 'Filet de saumon'),
('Thon', 'Thon'),
('Crevettes', 'Crevettes décortiquées'),
('Jambon', 'Jambon'),
('Fromage râpé', 'Fromage râpé'),
('Mozzarella', 'Mozzarella'),
('Parmesan', 'Parmesan'),
('Emmental', 'Emmental'),
('Riz', 'Riz blanc'),
('Pâtes', 'Pâtes alimentaires'),
('Spaghetti', 'Spaghetti'),
('Nouilles', 'Nouilles asiatiques'),
('Pain', 'Pain'),
('Chapelure', 'Chapelure'),
('Persil', 'Persil frais'),
('Basilic', 'Basilic frais'),
('Coriandre', 'Coriandre fraîche'),
('Thym', 'Thym'),
('Romarin', 'Romarin'),
('Paprika', 'Paprika'),
('Curry', 'Curry en poudre'),
('Cumin', 'Cumin'),
('Sauce soja', 'Sauce soja'),
('Miel', 'Miel'),
('Citron', 'Citron'),
('Vinaigre', 'Vinaigre'),
('Lait de coco', 'Lait de coco'),
('Bouillon', 'Bouillon de légumes ou de volaille');


-- ============================================================
-- 5. TABLE TEMPORAIRE POUR GÉNÉRER 1 000 RECETTES
-- ============================================================

DROP TEMPORARY TABLE IF EXISTS numbers;

CREATE TEMPORARY TABLE numbers (
    n INT PRIMARY KEY
);

INSERT INTO numbers (n)
VALUES
(1),(2),(3),(4),(5),(6),(7),(8),(9),(10),
(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),
(21),(22),(23),(24),(25),(26),(27),(28),(29),(30),
(31),(32),(33),(34),(35),(36),(37),(38),(39),(40),
(41),(42),(43),(44),(45),(46),(47),(48),(49),(50),
(51),(52),(53),(54),(55),(56),(57),(58),(59),(60),
(61),(62),(63),(64),(65),(66),(67),(68),(69),(70),
(71),(72),(73),(74),(75),(76),(77),(78),(79),(80),
(81),(82),(83),(84),(85),(86),(87),(88),(89),(90),
(91),(92),(93),(94),(95),(96),(97),(98),(99),(100);


-- ============================================================
-- 6. GÉNÉRATION DES 1 000 RECETTES
-- ============================================================

INSERT INTO recipes (
    name,
    description,
    user_id,
    category_id
)
SELECT
    CONCAT(
        CASE MOD(n.n, 15)
            WHEN 0 THEN 'Poulet'
            WHEN 1 THEN 'Pâtes'
            WHEN 2 THEN 'Salade'
            WHEN 3 THEN 'Tarte'
            WHEN 4 THEN 'Curry'
            WHEN 5 THEN 'Gratin'
            WHEN 6 THEN 'Soupe'
            WHEN 7 THEN 'Pizza'
            WHEN 8 THEN 'Risotto'
            WHEN 9 THEN 'Poisson'
            WHEN 10 THEN 'Burger'
            WHEN 11 THEN 'Omelette'
            WHEN 12 THEN 'Wok'
            WHEN 13 THEN 'Gâteau'
            ELSE 'Lasagnes'
        END
    ),

    CONCAT(
        'Une délicieuse recette maison créée par ',
        u.name,
        '. Une recette idéale pour un repas savoureux.'
    ),

    u.id,

    c.id

FROM users u

CROSS JOIN numbers n

JOIN categories c
    ON c.id = MOD(n.n - 1, 15) + 1

ORDER BY
    u.id,
    n.n;


-- ============================================================
-- 7. AJOUT DES INGRÉDIENTS
-- ============================================================

/*
   Chaque recette reçoit 5 ingrédients.

   On utilise CROSS JOIN numbers limité aux 5 premières lignes.

   Grâce à MOD(), les ingrédients sont répartis
   différemment selon la recette et l'utilisateur.
*/

INSERT INTO recipe_ingredients (
    recipe_id,
    ingredient_id,
    quantity,
    unit
)

SELECT
    r.id,

    MOD(
        r.id * 7
        + n.n * 13,
        50
    ) + 1,

    CASE n.n
        WHEN 1 THEN 250
        WHEN 2 THEN 100
        WHEN 3 THEN 2
        WHEN 4 THEN 50
        ELSE 150
    END,

    CASE n.n
        WHEN 1 THEN 'g'
        WHEN 2 THEN 'ml'
        WHEN 3 THEN 'unité'
        WHEN 4 THEN 'g'
        ELSE 'g'
    END

FROM recipes r

CROSS JOIN (
    SELECT n
    FROM numbers
    WHERE n <= 5
) n

WHERE NOT EXISTS (
    SELECT 1
    FROM recipe_ingredients ri
    WHERE ri.recipe_id = r.id
      AND ri.ingredient_id =
          MOD(
              r.id * 7
              + n.n * 13,
              50
          ) + 1
);


-- ============================================================
-- 8. FAVORIS
-- ============================================================

INSERT INTO favorites (
    user_id,
    recipe_id
)

SELECT
    u.id,
    r.id

FROM users u

JOIN recipes r
    ON r.user_id <> u.id

WHERE MOD(
    u.id * 17 + r.id * 13,
    100
) < 4;


-- ============================================================
-- 9. NOTES
-- ============================================================

INSERT INTO ratings (
    user_id,
    recipe_id,
    score
)

SELECT
    u.id,
    r.id,

    MOD(
        u.id * 7 + r.id * 3,
        5
    ) + 1

FROM users u

JOIN recipes r
    ON r.user_id <> u.id

WHERE MOD(
    u.id * 11 + r.id * 7,
    100
) < 25;


-- ============================================================
-- 10. COMMENTAIRES
-- ============================================================

INSERT INTO comments (
    user_id,
    recipe_id,
    content
)

SELECT
    u.id,
    r.id,

    CASE MOD(
        u.id + r.id,
        10
    )

        WHEN 0 THEN 'Très bonne recette, je recommande !'
        WHEN 1 THEN 'Super recette, facile à préparer.'
        WHEN 2 THEN 'Toute la famille a adoré.'
        WHEN 3 THEN 'Je la referai certainement.'
        WHEN 4 THEN 'Très bon résultat, merci pour la recette.'
        WHEN 5 THEN 'Simple et efficace.'
        WHEN 6 THEN 'Excellent !'
        WHEN 7 THEN 'J''ai beaucoup aimé cette recette.'
        WHEN 8 THEN 'Une bonne découverte.'
        ELSE 'Très bon repas, recette validée !'

    END

FROM users u

JOIN recipes r
    ON r.user_id <> u.id

WHERE MOD(
    u.id * 13 + r.id * 5,
    100
) < 10;


-- ============================================================
-- 11. NETTOYAGE TABLE TEMPORAIRE
-- ============================================================

DROP TEMPORARY TABLE IF EXISTS numbers;


-- ============================================================
-- 12. VÉRIFICATIONS
-- ============================================================

SELECT
    'Utilisateurs' AS element,
    COUNT(*) AS nombre
FROM users

UNION ALL

SELECT
    'Catégories',
    COUNT(*)
FROM categories

UNION ALL

SELECT
    'Ingrédients',
    COUNT(*)
FROM ingredients

UNION ALL

SELECT
    'Recettes',
    COUNT(*)
FROM recipes

UNION ALL

SELECT
    'Ingrédients recettes',
    COUNT(*)
FROM recipe_ingredients

UNION ALL

SELECT
    'Favoris',
    COUNT(*)
FROM favorites

UNION ALL

SELECT
    'Notes',
    COUNT(*)
FROM ratings

UNION ALL

SELECT
    'Commentaires',
    COUNT(*)
FROM comments;


-- ============================================================
-- 13. RECETTES PAR UTILISATEUR
-- ============================================================

SELECT
    u.id,
    u.name,
    COUNT(r.id) AS nombre_recettes
FROM users u

LEFT JOIN recipes r
    ON r.user_id = u.id

GROUP BY
    u.id,
    u.name

ORDER BY
    u.id;