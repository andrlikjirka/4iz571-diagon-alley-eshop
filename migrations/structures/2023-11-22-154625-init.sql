create table products
(
    product_id  int auto_increment
        primary key,
    name        varchar(255)                          not null,
    description text                                  null,
    created     timestamp default current_timestamp() not null,
    updated     timestamp                             null on update current_timestamp(),
    stock       int       default 0                   not null,
    category_id int                                   not null
)
    collate = utf8mb4_czech_ci;

create table resources
(
    resource_id int auto_increment
        primary key,
    name        varchar(255) not null
)
    collate = utf8mb4_czech_ci;

create table roles
(
    role_id int auto_increment
        primary key,
    name    varchar(255) not null
)
    collate = utf8mb4_czech_ci;

create table permissions
(
    permission_id int auto_increment
        primary key,
    role_id       int                                    not null,
    resource_id   int                                    not null,
    action        varchar(255)                           not null,
    type          enum ('allow', 'deny') default 'allow' not null,
    constraint permissions_resources_resource_id_fk
        foreign key (resource_id) references eshop.resources (resource_id),
    constraint permissions_roles_role_id_fk
        foreign key (role_id) references eshop.roles (role_id)
)
    collate = utf8mb4_czech_ci;

create table users
(
    user_id     int auto_increment
        primary key,
    name        varchar(255) charset latin1 not null,
    email       varchar(255)                null,
    facebook_id varchar(255)                null,
    role_id     int                         not null,
    password    varchar(255)                null,
    constraint users_roles_role_id_fk
        foreign key (role_id) references eshop.roles (role_id)
)
    collate = utf8mb4_czech_ci;

create table addresses
(
    address_id int auto_increment
        primary key,
    street     varchar(255) not null,
    city       varchar(255) not null,
    zip        varchar(255) not null,
    user_id    int          not null,
    constraint addresses_users_user_id_fk
        foreign key (user_id) references eshop.users (user_id)
)
    collate = utf8mb4_czech_ci;

create table forgotten_passwords
(
    forgotten_password_id int auto_increment
        primary key,
    user_id               int                                   not null,
    code                  varchar(255)                          not null,
    created               timestamp default current_timestamp() not null,
    constraint forgotten_passwords_users_user_id_fk
        foreign key (user_id) references eshop.users (user_id)
)
    collate = utf8mb4_czech_ci;

create table orders
(
    order_id  int auto_increment
        primary key,
    adress_id int                                                                               not null,
    status    enum ('received', 'processing', 'ready', 'delivered') default 'received'          not null,
    created   timestamp                                             default current_timestamp() not null,
    user_id   int                                                                               not null,
    shipping  enum ('vyzvednuti', 'zasilkovna', 'posta', 'ppl')                                 not null,
    payment   enum ('cash', 'card', 'bank_transfer')                                            not null,
    constraint orders_addresses_address_id_fk
        foreign key (adress_id) references eshop.addresses (address_id),
    constraint orders_users_user_id_fk
        foreign key (user_id) references eshop.users (user_id)
)
    collate = utf8mb4_czech_ci;

