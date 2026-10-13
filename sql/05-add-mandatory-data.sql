use SwissCookingDB;

ALTER TABLE roles AUTO_INCREMENT = 0;

insert into roles (name) values
    ('super_admin'),
    ('admin'),
    ('user');

insert into users (name, email, password_hash, id_role) values
    ('AdminSuper', 'admin.super@right.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 0),
    ('Admin', 'admin.dev@right.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 1);

INSERT INTO categories (name, description) VALUES
    ("Non classé", "Catégorie par défaut pour les recettes non classées.");
