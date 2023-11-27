alter table order_items
    add galleon_price int default 0 not null;

alter table order_items
    add sickle_price int default 0 not null;

alter table order_items
    add knut_price int default 0 not null;

