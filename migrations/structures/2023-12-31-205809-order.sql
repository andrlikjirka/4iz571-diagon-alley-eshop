alter table addresses
    add name varchar(255) not null after address_id;

create table order_status
(
    order_status_id int auto_increment,
    name            varchar(255) not null,
    constraint order_status_pk
        primary key (order_status_id)
);

alter table order_status
    collate = utf8mb4_czech_ci;

alter table order_status
    modify name varchar(255) collate utf8mb4_czech_ci not null;

alter table orders
drop column status;

alter table orders
drop column user_name;

alter table orders
    add order_status_id int default 1 not null after created;

alter table orders
    add constraint orders_order_status_order_status_id_fk
        foreign key (order_status_id) references order_status (order_status_id)
            on update cascade;

alter table orders
    modify shipping enum ('vyzvednuti', 'bradavice', 'sova') not null;

alter table orders
    modify payment enum ('hotovost', 'banka', 'karta') not null;