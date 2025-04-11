-- Active: 1728802889979@@127.0.0.1@3306@akun
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'dosen', 'mahasiswa') NOT NULL
);

-- Tabel dosen (email & password disimpan juga)
CREATE TABLE dosen (
  user_id INT PRIMARY KEY,
  nik VARCHAR(20) UNIQUE NOT NULL,
  nama VARCHAR(100) NOT NULL,
  gelar VARCHAR(50),
  lulusan VARCHAR(100),
  telp VARCHAR(20),
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  user_input VARCHAR(100),
  tanggal_input DATE,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel mahasiswa (email & password disimpan juga)
CREATE TABLE mahasiswa (
  user_id INT PRIMARY KEY,
  nim VARCHAR(20) UNIQUE NOT NULL,
  nama VARCHAR(100) NOT NULL,
  tahun_masuk YEAR,
  alamat TEXT,
  telp VARCHAR(20),
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  user_input VARCHAR(100),
  tanggal_input DATE,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel mata kuliah
CREATE TABLE matakuliah (
kode_matkul VARCHAR(10) PRIMARY KEY,
nama_matkul VARCHAR(100) NOT NULL,
sks INT,
semester INT,
user_input VARCHAR(100),
tanggal_input DATE
);

-- Tabel KRS
CREATE TABLE krs (
id INT AUTO_INCREMENT PRIMARY KEY,
kode_matkul VARCHAR(10),
nik_dosen VARCHAR(20),
nim_mahasiswa VARCHAR(20),
hari_matkul VARCHAR(10),
ruangan VARCHAR(50),
user_input VARCHAR(100),
tanggal_input DATE,
FOREIGN KEY (kode_matkul) REFERENCES matakuliah(kode_matkul),
FOREIGN KEY (nik_dosen) REFERENCES dosen(nik),
FOREIGN KEY (nim_mahasiswa) REFERENCES mahasiswa(nim)
);

-- Insert ke tabel users
INSERT INTO users (email, password, role) VALUES
('agus@kampus.ac.id', MD5('agus123'), 'dosen'),
('nina@kampus.ac.id', MD5('nina123'), 'dosen'),
('widodo@kampus.ac.id', MD5('widodo123'), 'dosen'),
('dina@kampus.ac.id', MD5('dina123'), 'dosen'),
('yusuf@kampus.ac.id', MD5('yusuf123'), 'dosen');

-- Insert ke tabel dosen (user_id menyesuaikan ID auto-increment di atas, cek sesuai hasil insert_id)
-- Contoh di bawah: asumsi user_id mulai dari 1
INSERT INTO dosen (user_id, nik, nama, gelar, lulusan, telp, email, password, user_input, tanggal_input) VALUES
(2, 'D1001', 'Dr. Agus Salim', 'M.T.', 'ITB', '081234500001', 'agus@kampus.ac.id', MD5('agus123'), 'admin@kampus.ac.id', CURDATE()),
(3, 'D1002', 'Prof. Nina Lestari', 'Ph.D.', 'UI', '081234500002', 'nina@kampus.ac.id', MD5('nina123'), 'admin@kampus.ac.id', CURDATE()),
(4, 'D1003', 'Ir. Widodo Santoso', 'S.T., M.Eng.', 'UGM', '081234500003', 'widodo@kampus.ac.id', MD5('widodo123'), 'admin@kampus.ac.id', CURDATE()),
(5, 'D1004', 'Dina Kartika', 'M.Kom.', 'UNPAD', '081234500004', 'dina@kampus.ac.id', MD5('dina123'), 'admin@kampus.ac.id', CURDATE()),
(6, 'D1005', 'Yusuf Hidayat', 'S.T., M.T.', 'ITS', '081234500005', 'yusuf@kampus.ac.id', MD5('yusuf123'), 'admin@kampus.ac.id', CURDATE());

-- Insert ke tabel users
INSERT INTO users (email, password, role) VALUES
('mahasiswa01@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa02@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa03@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa04@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa05@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa06@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa07@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa08@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa09@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa10@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa11@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa12@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa13@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa14@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa15@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa16@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa17@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa18@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa19@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa20@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa21@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa22@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa23@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa24@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa25@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa26@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa27@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa28@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa29@gmail.com', MD5('pass123'), 'mahasiswa'),
('mahasiswa30@gmail.com', MD5('pass123'), 'mahasiswa');

-- Setelah insert, asumsikan ID user-nya berurutan dari 1 hingga 30.

-- Insert ke tabel mahasiswa
INSERT INTO mahasiswa (user_id, nim, nama, tahun_masuk, alamat, telp, email, password, user_input, tanggal_input) VALUES
(2, 'M002', 'Ahmad Setiawan', 2022, 'Jl. Melati No. 1', '0811111111', 'mahasiswa01@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(3, 'M003', 'Budi Hartono', 2023, 'Jl. Mawar No. 2', '0812222222', 'mahasiswa02@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(4, 'M004', 'Citra Ayu', 2021, 'Jl. Kenanga No. 3', '0813333333', 'mahasiswa03@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(5, 'M005', 'Deni Ramadhan', 2020, 'Jl. Anggrek No. 4', '0814444444', 'mahasiswa04@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(6, 'M006', 'Eka Lestari', 2023, 'Jl. Teratai No. 5', '0815555555', 'mahasiswa05@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(32, 'M007', 'Fikri Alamsyah', 2022, 'Jl. Mawar No. 6', '0816666666', 'mahasiswa06@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(8, 'M008', 'Gita Prameswari', 2021, 'Jl. Melati No. 7', '0817777777', 'mahasiswa07@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(9, 'M009', 'Hendri Saputra', 2020, 'Jl. Dahlia No. 8', '0818888888', 'mahasiswa08@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(10, 'M010', 'Indah Kurnia', 2023, 'Jl. Anggrek No. 9', '0819999999', 'mahasiswa09@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(11, 'M011', 'Joko Santoso', 2022, 'Jl. Kemuning No. 10', '0820000000', 'mahasiswa10@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(12, 'M012', 'Kiki Salsabila', 2021, 'Jl. Kenanga No. 11', '0821111111', 'mahasiswa11@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(13, 'M013', 'Lutfi Nugraha', 2020, 'Jl. Teratai No. 12', '0822222222', 'mahasiswa12@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(14, 'M014', 'Mega Utami', 2023, 'Jl. Melati No. 13', '0823333333', 'mahasiswa13@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(15, 'M015', 'Nanda Fitria', 2022, 'Jl. Dahlia No. 14', '0824444444', 'mahasiswa14@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(16, 'M016', 'Oka Wirawan', 2021, 'Jl. Mawar No. 15', '0825555555', 'mahasiswa15@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(17, 'M017', 'Putri Dwi', 2020, 'Jl. Anggrek No. 16', '0826666666', 'mahasiswa16@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(18, 'M018', 'Qori Azzahra', 2023, 'Jl. Kenanga No. 17', '0827777777', 'mahasiswa17@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(19, 'M019', 'Raka Permana', 2022, 'Jl. Dahlia No. 18', '0828888888', 'mahasiswa18@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(20, 'M020', 'Siti Mariam', 2021, 'Jl. Teratai No. 19', '0829999999', 'mahasiswa19@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(21, 'M021', 'Taufik Hidayat', 2020, 'Jl. Melati No. 20', '0830000000', 'mahasiswa20@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(22, 'M022', 'Udin Supriadi', 2023, 'Jl. Kemuning No. 21', '0831111111', 'mahasiswa21@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(23, 'M023', 'Vina Oktaviani', 2022, 'Jl. Anggrek No. 22', '0832222222', 'mahasiswa22@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(24, 'M024', 'Wawan Kurniawan', 2021, 'Jl. Mawar No. 23', '0833333333', 'mahasiswa23@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(25, 'M025', 'Xenia Larasati', 2020, 'Jl. Teratai No. 24', '0834444444', 'mahasiswa24@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(26, 'M026', 'Yoga Firmansyah', 2023, 'Jl. Dahlia No. 25', '0835555555', 'mahasiswa25@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(27, 'M027', 'Zahra Amalia', 2022, 'Jl. Melati No. 26', '0836666666', 'mahasiswa26@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(28, 'M028', 'Ade Gunawan', 2021, 'Jl. Anggrek No. 27', '0837777777', 'mahasiswa27@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(29, 'M029', 'Bella Savira', 2020, 'Jl. Kenanga No. 28', '0838888888', 'mahasiswa28@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(30, 'M030', 'Cahyo Bintoro', 2023, 'Jl. Teratai No. 29', '0839999999', 'mahasiswa29@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE()),
(31, 'M031', 'Dewi Sartika', 2022, 'Jl. Mawar No. 30', '0840000000', 'mahasiswa30@gmail.com', MD5('pass123'), 'admin@example.com', CURDATE());

INSERT INTO matakuliah (kode_matkul, nama_matkul, sks, semester, user_input, tanggal_input) VALUES
('MK001', 'Algoritma dan Pemrograman', 3, 1, 'admin', '2025-04-11'),
('MK002', 'Struktur Data', 3, 2, 'admin', '2025-04-11'),
('MK003', 'Basis Data', 3, 2, 'admin', '2025-04-11'),
('MK004', 'Pemrograman Web', 3, 3, 'admin', '2025-04-11'),
('MK005', 'Jaringan Komputer', 3, 4, 'admin', '2025-04-11'),
('MK006', 'Sistem Operasi', 3, 3, 'admin', '2025-04-11'),
('MK007', 'Kecerdasan Buatan', 3, 5, 'admin', '2025-04-11'),
('MK008', 'Pemrograman Mobile', 3, 5, 'admin', '2025-04-11'),
('MK009', 'Rekayasa Perangkat Lunak', 3, 4, 'admin', '2025-04-11'),
('MK010', 'Etika Profesi', 2, 6, 'admin', '2025-04-11');

-- Tambah akun admin ke tabel users
INSERT INTO users (email, password, role) VALUES
('admin@kampus.ac.id', MD5('admin123'), 'admin');

DELIMITER $$

CREATE TRIGGER hapus_krs_setelah_matkul
BEFORE DELETE ON matakuliah
FOR EACH ROW
BEGIN
  DELETE FROM krs WHERE kode_matkul = OLD.kode_matkul;
END$$

DELIMITER ;
