CREATE DATABASE webperpus;

CREATE TABLE accounts(
	id_acc INT(125) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    acc_username VARCHAR(225) NOT NULL,
    acc_email VARCHAR(225) NOT NULL,
    acc_password VARCHAR(225) NOT NULL,
    acc_role VARCHAR(125) NOT NULL
);

CREATE TABLE buku(
	id_bku INT(125) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    bku_judul VARCHAR(225) NOT NULL,
    bku_nama_pengarang VARCHAR(250) NOT NULL,
    bku_nama_penerbit VARCHAR(250) NOT NULL,
    bku_katalog VARCHAR(125) UNIQUE KEY NOT NULL
);

CREATE TABLE pinjam(
	id_pjm INT(125) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    pjm_id_staf INT(125),
    pjm_id_anggota INT(125),
    pjm_id_buku INT(125),    
    pjm_waktu_pinjam TIME NOT NULL,
    FOREIGN KEY (pjm_id_staf)
        REFERENCES accounts(id_acc),
    FOREIGN KEY (pjm_id_anggota)
        REFERENCES accounts(id_acc),
    FOREIGN KEY (pjm_id_buku)
        REFERENCES buku(id_bku)
);

CREATE TABLE balik(
	id_blk INT(125) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    blk_id_staf INT(125),
    blk_id_pinjam INT(125), 
    blk_waktu_balik TIME NOT NULL,
    FOREIGN KEY (blk_id_staf)
        REFERENCES accounts(id_acc),
    FOREIGN KEY (blk_id_pinjam)
        REFERENCES pinjam(id_pjm)
);

CREATE TABLE history_pinjam(
	id_htp INT(125) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    htp_id_staf INT(125),
    htp_id_anggota INT(125),
    htp_id_buku INT(125),    
    htp_waktu_pinjam TIME NOT NULL,
    htp_waktu_history TIME NOT NULL,
    FOREIGN KEY (htp_id_staf)
        REFERENCES accounts(id_acc),
    FOREIGN KEY (htp_id_anggota)
        REFERENCES accounts(id_acc),
    FOREIGN KEY (htp_id_buku)
        REFERENCES buku(id_bku)
);

INSERT INTO 'accounts'('acc_username', 'acc_email', 'acc_password', 'acc_role')
VALUES 
('admin', 'admin@gmail.com', MD5('4dm1n'), 'admin'),
('staf', 'staf@gmail.com', MD5('st@f'), 'staf'),
('anggota', 'anggota@gmail.com', MD5('4ngg0t4'), 'anggota');