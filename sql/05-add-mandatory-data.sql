use SwissCookingDB;

insert into roles (name) values
    ('user'),
    ('admin');

insert into users (name, email, password_hash, id_role) values
    ('Admin', 'admin.dev@right.com', '$2y$12$.YAqEsb8xcPuU3tLnR461e1Mupqgtf89AaaGTnKhtmMwVMxyv.NNm', 2);
   
