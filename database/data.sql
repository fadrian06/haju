INSERT INTO users VALUES
(1, 'Franyer', 'Adrián', 'Sánchez', 'Guillén', 1002326400, 'Masculino', 28072391, '$2y$10$/YNzvLehinsBUlX0pyubH.F00qTihY5YAlBpRi9vB5txy8WqUAOfK', '+58 416-5335826', 'franyeradriansanchez@gmail.com', 'El Pinar', 'assets/img/avatars/28072391.jpg', true, '2025-02-14 13:02:14', 1, 3, null),
(2, 'Jenifer', null, 'Lázaro', null, 938995200, 'Femenino', 29794519, '$2y$10$EV0g0srILxmTfk6FjTr3LejmeaHDFBHAaTmdCvN.d/D0E9YKzi4Nu', '+58 424-7435104', 'jeniner_99@gmail.com', 'El Pinar', 'assets/img/avatars/29794519.jpg', true, '2025-02-14 13:02:15', 2, 3, 1),
(3, 'Daniel', null, 'Mancilla', null, 975110400, 'Masculino', 27668711, '$2y$10$1ZEtf2GlO0ZaR9zSlxhU7.jGVJv7a2catm4d4Hcy5kpCqz4akJlnq', '+58 424-7532164', 'daniel@gmail.com', 'Río Frío', 'assets/img/avatars/27711944.jpg', true, '2025-02-14 13:02:16', 3, 3, 2);

INSERT INTO patients VALUES
(1, 'Juan', null, 'Arias', null, 923184000, 'Masculino', 31514346, '2025-02-14 13:14:59', 2),
(2, 'Andreína', null, 'Sánchez', null, 968889600, 'Femenino', 26345006, '2025-02-14 13:14:57', 3),
(3, 'Carlos', null, 'Contreras', null, 1026691200, 'Masculino', 29304506, '2025-02-14 13:14:56', 3),
(4, 'Eliliana', null, 'Salas', null, 1286668800, 'Femenino', 33453364, '2025-02-14 13:14:55', 3),
(5, 'Yenni', null, 'Sánchez', null, 977702400, 'Femenino', 28543550, '2025-02-14 13:14:54', 2);

INSERT INTO doctors VALUES
(1, 'Marley', null, 'Mesa', 'Lázaro', 172886400, 'Femenino', 19345063, '2025-02-14 13:14:53', 2),
(2, 'Maro', null, 'Velazques', null, 640137600, 'Masculino', 18202068, '2025-02-14 13:14:52', 3),
(3, 'José', null, 'Hernández', null, 475632000, 'Masculino', 17503783, '2025-02-14 13:14:51', 2);

INSERT INTO department_assignments VALUES
(1, '2025-02-14 13:14:51', 2, 22),
(2, '2025-02-14 13:14:50', 2, 21),
(3, '2025-02-14 13:14:49', 3, 22);

INSERT INTO hospitalizations VALUES
(1, 21, '2024-10-12', '2024-10-25', 'Curación', 'El paciente se recuperó exitósamente, debe reposar durante al menos 3 meses', '2025-02-14 13:14:49', 1, 1),
(2, 21, '2025-01-01', null, 'Mejoría', 'El paciente responde adecuadamente a los medicamentos', '2025-02-14 13:14:48', 2, 2);

INSERT INTO consultations VALUES
(1, 'P', '2025-02-14 13:14:48', 1, 47, 21, 1),
(2, 'S', '2025-01-14 13:19:48', 1, 47, 21, 1),
(3, 'P', '2025-01-29 13:19:49', 2, 46, 21, 2),
(4, 'P', '2024-06-06 13:19:49', 2, 46, 21, 2),
(5, 'P', '2024-08-08 13:19:49', 2, 46, 21, 2),
(6, 'P', '2025-02-10 13:19:49', 3, 47, 21, 3),
(7, 'P', '2025-02-11 13:19:49', 4, 47, 21, 3),
(8, 'S', '2025-02-12 13:19:49', 4, 47, 21, 3),
(9, 'P', '2025-02-13 13:19:49', 5, 47, 21, 3);
