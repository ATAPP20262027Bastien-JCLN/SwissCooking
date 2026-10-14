use SwissCookingDB;

ALTER TABLE roles AUTO_INCREMENT = 0;

insert into roles (name) values
    ('admin'),
    ('moderator'),
    ('user');

insert into users (name, email, password_hash, id_role) values
    ('Admin', 'admin.dev@right.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1),
    ('Moderator', 'moderator.dev@right.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 2);

INSERT INTO categories (name, description) VALUES
    ("Non classé", "Catégorie par défaut pour les recettes non classées.");
