alter table orders
    add galleon_total_price int default 0 not null;

alter table orders
    add sickle_total_price int default 0 not null;

alter table orders
    add knut_total_price int default 0 not null;