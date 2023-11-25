alter table roles
    add constraint unique_role_name
        unique (name);

