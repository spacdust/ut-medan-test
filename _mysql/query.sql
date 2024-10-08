// buat db
CREATE DATABASE `ut-leads`;

// buat tabel
CREATE TABLE produk (
	id_produk INT NOT NULL AUTO_INCREMENT,
	nama_produk VARCHAR(255),
	PRIMARY KEY (id_produk) 
    );

CREATE TABLE sales (
	id_sales INT NOT NULL AUTO_INCREMENT,
	nama_sales VARCHAR(255),
	PRIMARY KEY (id_sales) 
    );


CREATE TABLE leads (
	id_leads INT NOT NULL AUTO_INCREMENT,
	tanggal DATE,
    id_sales INT,
    id_produk INT,
    no_wa VARCHAR(50),
    nama_lead VARCHAR(255),
    kota VARCHAR(50),
    id_user INT,
	PRIMARY KEY (id_leads),
    FOREIGN KEY (id_sales) REFERENCES sales(id_sales),
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
    );

// input produk
INSERT INTO produk (nama_produk) VALUES ("Cipta Residence 2");
INSERT INTO produk (nama_produk) VALUES ("The Rich");
INSERT INTO produk (nama_produk) VALUES ("Namorambe City");
INSERT INTO produk (nama_produk) VALUES ("Grand Banten");
INSERT INTO produk (nama_produk) VALUES ("Turi Mansion");
INSERT INTO produk (nama_produk) VALUES ("Cipta Residence 1");

// input sales
INSERT INTO sales (nama_sales) VALUES ("sales 1");
INSERT INTO sales (nama_sales) VALUES ("sales 2");
INSERT INTO sales (nama_sales) VALUES ("sales 3");