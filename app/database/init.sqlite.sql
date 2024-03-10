DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(20) NOT NULL,
  birth_date DATE NOT NULL,
  gender VARCHAR(1) NOT NULL CHECK (gender IN ('Masculino', 'Femenino')),
  role VARCHAR(1) NOT NULL CHECK (role IN ('Director/a', 'Coordinador/a', 'Secretario/a')),
  prefix VARCHAR(20),
  id_card INTEGER NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(16) UNIQUE,
  email VARCHAR(255) UNIQUE,
  address TEXT,
  avatar VARCHAR(255) UNIQUE,
  registered DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_active BOOL DEFAULT true,

  UNIQUE(first_name, last_name)
);

DROP TABLE IF EXISTS departments;
CREATE TABLE departments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(30) NOT NULL UNIQUE,
  registered DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_active BOOL DEFAULT true
);

DROP TABLE IF EXISTS department_assignments;
CREATE TABLE department_assignments (
  user_id INTEGER NOT NULL,
  department_id INTEGER NOT NULL,
  registered DATETIME DEFAULT CURRENT_TIMESTAMP,

  UNIQUE (user_id, department_id)
);
