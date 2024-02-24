DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(20) NOT NULL,
  speciality VARCHAR(20) NOT NULL,
  prefix VARCHAR(20),
  id_card INTEGER NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  avatar VARCHAR(255) UNIQUE,

  UNIQUE(first_name, last_name)
);
