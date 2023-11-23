CREATE DATABASE devengo;

use devengo;

CREATE TABLE
    unidades (
        id INT(5) AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(30) NOT NULL,
        descripcion VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    roles (
        id INT(2) AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(30) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    usuarios (
        id INT(10) AUTO_INCREMENT PRIMARY KEY,
        matricula VARCHAR(20) NOT NULL UNIQUE,
        nombre VARCHAR(50) NOT NULL,
        unidadID INT(5) NOT NULL,
        FOREIGN KEY(unidadID) REFERENCES unidades(id),
        rolID INT(2) NOT NULL,
        FOREIGN KEY(rolID) REFERENCES roles(id),
        contra VARCHAR(70) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    contratos (
        id INT(10) AUTO_INCREMENT PRIMARY KEY,
        proveedor VARCHAR(50) NOT NULL,
        clave VARCHAR(20) NOT NULL UNIQUE,
        descripcion VARCHAR(50) NOT NULL,
        mont_max DECIMAL(10, 3) NOT NULL,
        mont_min DECIMAL(10, 3) NOT NULL,
        fecha_in DATE,
        fecha_fin DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    devengos (
        id INT(10) AUTO_INCREMENT PRIMARY KEY,
        fecha DATE,
        descripcion VARCHAR(50) NOT NULL,
        monto DECIMAL(10, 3) NOT NULL,
        usuarioID INT(10) NOT NULL,
        FOREIGN KEY(usuarioID) REFERENCES usuarios(id),
        contratoID INT(10) NOT NULL,
        FOREIGN KEY(contratoID) REFERENCES contratos(id),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

INSERT INTO
    unidades (nombre, descripcion)
VALUES ("OOAD", "OOAD AGUASCALIENTES"), (
        "HGZ1",
        "HOSPITAL GENERAL DE ZONA 1"
    ), (
        "HGZ2",
        "HOSPITAL GENERAL DE ZONA 2"
    ), (
        "HGZ3",
        "HOSPITAL GENERAL DE ZONA 3"
    ), (
        "UMAA",
        "UNIDAD MEDICA DE ATENCION AMBULATORIA"
    ), (
        "UMF1",
        "UNIDAD DE MEDICINA FAMILIAR 1"
    ), (
        "UMF2",
        "UNIDAD DE MEDICINA FAMILIAR 2"
    ), (
        "UMF3",
        "UNIDAD DE MEDICINA FAMILIAR 3"
    ), (
        "UMF4",
        "UNIDAD DE MEDICINA FAMILIAR 4"
    ), (
        "UMF5",
        "UNIDAD DE MEDICINA FAMILIAR 5"
    ), (
        "UMF6",
        "UNIDAD DE MEDICINA FAMILIAR 6"
    ), (
        "UMF7",
        "UNIDAD DE MEDICINA FAMILIAR 7"
    ), (
        "UMF8",
        "UNIDAD DE MEDICINA FAMILIAR 8"
    ), (
        "UMF9",
        "UNIDAD DE MEDICINA FAMILIAR 9"
    ), (
        "UMF10",
        "UNIDAD DE MEDICINA FAMILIAR 10"
    ), (
        "UMF11",
        "UNIDAD DE MEDICINA FAMILIAR 11"
    ), (
        "UMF12",
        "UNIDAD DE MEDICINA FAMILIAR 12"
    ), (
        "ABASTO",
        "COORDINACION DE ABASTECIMIENTO"
    ), (
        "CSS",
        "CENTRO DE SEGURIDAD SOCIAL"
    ), (
        "GUARDERIA",
        "GUARDERIA ORDINARIA"
    ), (
        "CEDECAL",
        "CENTRO DE CAPACITACION"
    ), (
        "SUBNTE",
        "SUBDELEGACION METROPOLITANA NORTE"
    ), (
        "SUBSUR",
        "SUBDELEGACION METROPOLITANA SUR"
    );

INSERT INTO roles (nombre) VALUES ( "Administrador"), ( "Usuario");