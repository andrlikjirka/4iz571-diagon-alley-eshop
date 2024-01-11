alter table orders
    add email varchar(255) not null after user_id;

alter table orders
    add name varchar(255) not null after user_id;
