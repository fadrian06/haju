/*=======================================
=            STRONG ENTITIES            =
=======================================*/
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(20) NOT NULL UNIQUE,
  registered_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS instruction_levels;
CREATE TABLE instruction_levels (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(20) NOT NULL UNIQUE,
  abbreviation VARCHAR(5) NOT NULL UNIQUE,
  registered_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS departments;
CREATE TABLE departments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(20) NOT NULL UNIQUE,
  belongs_to_external_consultation BOOL DEFAULT false,
  is_active BOOL DEFAULT true,
  icon_file_path VARCHAR(255) NOT NULL UNIQUE,
  registered_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

/*=====================================
=            WEAK ENTITIES            =
=====================================*/
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  first_name VARCHAR(20) NOT NULL,
  second_name VARCHAR(20),
  first_last_name VARCHAR(20) NOT NULL,
  second_last_name VARCHAR(20),
  birth_date DATE NOT NULL,
  gender VARCHAR(20) NOT NULL CHECK (gender IN ('Masculino', 'Femenino')),
  id_card INTEGER NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(16) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  address TEXT NOT NULL,
  profile_image_path VARCHAR(255) NOT NULL UNIQUE,
  is_active BOOL DEFAULT true,
  registered_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  appointment_id INTEGER NOT NULL,
  instruction_level_id INTEGER NOT NULL,

  UNIQUE (first_name, second_name, first_last_name, second_last_name),
  FOREIGN KEY (appointment_id) REFERENCES appointments (id),
  FOREIGN KEY (instruction_level_id) REFERENCES instruction_levels (id)
);

/*======================================
=            PIVOT ENTITIES            =
======================================*/
DROP TABLE IF EXISTS department_assignments;
CREATE TABLE department_assignments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  registered_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  user_id INTEGER NOT NULL,
  department_id INTEGER NOT NULL,

  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (department_id) REFERENCES departments (id)
);

/*=============================================
=            PRE-INSTALLED RECORDS            =
=============================================*/
INSERT INTO appointments (id, name) VALUES
(1, 'Director/a'), (2, 'Coordinador/a'), (3, 'Secretario/a');

INSERT INTO instruction_levels (id, name, abbreviation) VALUES
(1, 'Doctor/a', 'Dr'),
(2, 'Ingeniero/a', 'Ing'),
(3, 'Técnico Superior Universitario', 'TSU'),
(4, 'Licenciado/a', 'Licdo');

INSERT INTO departments (id, name, belongs_to_external_consultation, icon_file_path)
VALUES /*(1, 'Pediatría', true),
(2, 'Ginecología', true),
(3, 'Alto Riesgo', true),
(4, 'Cirugía General', true),
(5, 'Nutrición Dietética', true),
(6, 'Consulta Personal', true),
(7, 'Higiene de Adulto', true),
(8, 'I.T.S', true),
(9, 'Salud Respiratoria', true),
(10, 'Consulta General', true),
(11, 'Operativo Médico Asistencial', true),
(12, 'Paez', false),
(13, 'Programa', false),
(14, 'Laboratorio', false),
(15, 'Epidemiología', false),
(16, 'Hechos Vitales', false),
(17, 'Servicios Sociales', false),
(18, 'Quirófano', false),
(19, 'Rayos X', false),
(20, 'Banco de Sangre', false),
(21, 'Emergencia', false),*/
(22, 'Estadística', false, 'assets/img/departments/web01-obs_turismo-SIT.svg')/*,
(23, 'Hospitalización', false)*/;
