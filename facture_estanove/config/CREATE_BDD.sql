DROP DATABASE IF EXISTS gestion_factures;

----------------------------------
--- création de Base de donnée ---
----------------------------------

CREATE DATABASE gestion_factures;
USE gestion_factures;

-------------------------
--- création de table ---
-------------------------
CREATE TABLE CLIENTS
(
    id_client      INT PRIMARY KEY AUTO_INCREMENT,
    nom            VARCHAR(50) NOT NULL,
    prenom         VARCHAR(50) NOT NULL,
    sexe           ENUM('H', 'F') NOT NULL,
    date_naissance DATE        NOT NULL
);

CREATE TABLE FACTURE
(
    id_facture INT PRIMARY KEY AUTO_INCREMENT,
    montant    DECIMAL(10, 2) NOT NULL,
    produits   TEXT           NOT NULL,
    quantite   INT            NOT NULL,
    id_client  INT            NOT NULL,
    FOREIGN KEY (id_client) REFERENCES CLIENTS (id_client) ON DELETE CASCADE
);