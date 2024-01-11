alter table categories
    add slug varchar(255) not null after name;

alter table products
    add slug varchar(255) not null after name;