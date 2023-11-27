alter table orders
    add user_name varchar(255) not null after order_id;

alter table addresses
    add deleted tinyint default 0 not null;

