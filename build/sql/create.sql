CREATE database role_data;


CREATE TABLE roles (
  id SERIAL PRIMARY KEY ,
  name varchar(512) NOT NULL UNIQUE,
  description varchar(2048) 

);

CREATE TABLE members (
  id SERIAL PRIMARY KEY ,
  name varchar(512) NOT NULL UNIQUE
);

-- bond table between roles and members, allowing a many to many relationship
CREATE TABLE roles_members (
  role_id INTEGER NOT NULL,
  member_id INTEGER NOT NULL,
  PRIMARY KEY (role_id, member_id),
  CONSTRAINT rm_role_id_fkey FOREIGN KEY (role_id)
    REFERENCES roles (id),
  CONSTRAINT rm_member_id_fkey FOREIGN KEY (member_id)
    REFERENCES members (id)
);
