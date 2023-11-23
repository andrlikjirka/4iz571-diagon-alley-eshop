create table categories
(
    category_id int auto_increment
        primary key,
    name        varchar(255)      not null,
    parent_id   int               null,
    showed      tinyint default 0 not null,
    constraint categories_categories_category_id_fk
        foreign key (parent_id) references categories (category_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table products
(
    product_id  int auto_increment
        primary key,
    name        varchar(255)                          not null,
    description text                                  null,
    created     timestamp default current_timestamp() not null,
    updated     timestamp                             null on update current_timestamp(),
    stock       int       default 0                   not null,
    category_id int                                   not null,
    showed      tinyint   default 0                   not null,
    deleted     tinyint   default 0                   not null,
    constraint products_categories_category_id_fk
        foreign key (category_id) references categories (category_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table product_photos
(
    product_photo_id int auto_increment
        primary key,
    path             varchar(255) not null,
    product_id       int          not null,
    constraint product_photos_products_product_id_fk
        foreign key (product_id) references products (product_id)
            on update cascade
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
        foreign key (resource_id) references resources (resource_id)
            on update cascade on delete cascade,
    constraint permissions_roles_role_id_fk
        foreign key (role_id) references roles (role_id)
            on update cascade on delete cascade
)
    collate = utf8mb4_czech_ci;

create table users
(
    user_id     int auto_increment
        primary key,
    name        varchar(255)  not null,
    email       varchar(255)                null,
    facebook_id varchar(255)                null,
    role_id     int                         not null,
    password    varchar(255)                null,
    blocked     tinyint default 0           not null,
    deleted     tinyint default 0           not null,
    constraint users_roles_role_id_fk
        foreign key (role_id) references roles (role_id)
            on update cascade
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
        foreign key (user_id) references users (user_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table carts
(
    cart_id       int auto_increment
        primary key,
    user_id       int                                   not null,
    last_modified timestamp default current_timestamp() not null,
    constraint carts_users_user_id_fk
        foreign key (user_id) references users (user_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table cart_items
(
    cart_item_id int auto_increment
        primary key,
    cart_id      int           not null,
    product_id   int           not null,
    quantity     int default 1 not null,
    constraint cart_items_carts_cart_id_fk
        foreign key (cart_id) references carts (cart_id)
            on update cascade on delete cascade,
    constraint cart_items_products_product_id_fk
        foreign key (product_id) references products (product_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table favourite_products
(
    favourite_product_id int auto_increment
        primary key,
    product_id           int not null,
    user_id              int not null,
    constraint favourite_products_products_product_id_fk
        foreign key (product_id) references products (product_id)
            on update cascade,
    constraint favourite_products_users_user_id_fk
        foreign key (user_id) references users (user_id)
            on update cascade
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
        foreign key (user_id) references users (user_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table orders
(
    order_id  int auto_increment
        primary key,
    adress_id int                                                                                            not null,
    status    enum ('received', 'processing', 'ready', 'delivered', 'cancelled') default 'received'          not null,
    created   timestamp                                                          default current_timestamp() not null,
    user_id   int                                                                                            not null,
    shipping  enum ('vyzvednuti', 'zasilkovna', 'posta', 'ppl')                                              not null,
    payment   enum ('cash', 'card', 'bank_transfer')                                                         not null,
    constraint orders_addresses_address_id_fk
        foreign key (adress_id) references addresses (address_id)
            on update cascade,
    constraint orders_users_user_id_fk
        foreign key (user_id) references users (user_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table order_items
(
    order_item_id int auto_increment
        primary key,
    quantity      int default 1 not null,
    product_id    int           not null,
    order_id      int           not null,
    constraint order_items_orders_order_id_fk
        foreign key (order_id) references orders (order_id),
    constraint order_items_products_product_id_fk
        foreign key (product_id) references products (product_id)
            on update cascade
)
    collate = utf8mb4_czech_ci;

create table reviews
(
    review_id  int auto_increment
        primary key,
    text       text null,
    stars      int  not null,
    user_id    int  null,
    product_id int  not null,
    constraint reviews_products_product_id_fk
        foreign key (product_id) references products (product_id)
            on update cascade,
    constraint reviews_users_user_id_fk
        foreign key (user_id) references users (user_id)
            on update cascade on delete set null,
    constraint check_stars
        check (`stars` >= 1 and `stars` <= 5)
)
    collate = utf8mb4_czech_ci;

