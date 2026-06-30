-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2026 at 06:26 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id_absen` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `id_matkul` int DEFAULT NULL,
  `tanggal_absen` datetime DEFAULT NULL,
  `foto_pash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('hadir','tidak_hadir','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pertemuan` enum('1','2','3','4','5','6','7','8 (UTS)','9','10','11','12','13','14','15','16 (UAS)') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absen`
--

INSERT INTO `absen` (`id_absen`, `id_mahasiswa`, `id_matkul`, `tanggal_absen`, `foto_pash`, `status`, `pertemuan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-06-27 08:00:00', 'uploads/absen/citra_web.jpg', 'hadir', '1', '2026-06-27 23:08:12', '2026-06-27 23:08:12'),
(2, 2, 2, '2026-06-27 10:00:00', 'uploads/absen/dedi_db.jpg', 'hadir', '3', '2026-06-27 23:08:12', '2026-06-27 23:08:12'),
(3, 3, 1, '2026-06-27 08:00:00', 'uploads/absen/eka_web.jpg', 'tidak_hadir', '1', '2026-06-27 23:08:12', '2026-06-27 23:08:12'),
(4, 4, 3, '2026-06-28 13:00:00', 'uploads/absen/fajar_jarkom.jpg', 'pending', '4', '2026-06-27 23:08:12', '2026-06-27 23:08:12'),
(5, 5, 4, '2026-06-28 08:00:00', 'uploads/absen/gita_sisop.jpg', 'hadir', '1', '2026-06-27 23:08:12', '2026-06-27 23:08:12'),
(6, 1, 1, '2026-06-29 09:57:55', 'uploads/absen/bo2LhII0BDdWWHTjZqn5hrpSfBhj52Jz19C3unry.jpg', 'hadir', '2', '2026-06-29 09:57:55', '2026-06-29 09:58:33'),
(7, 1, 1, '2026-06-30 04:56:25', 'uploads/absen/tdCidbWEoHs97Tby7OVLCqfgwJVVb5KFOFAhcAGY.jpg', 'hadir', '4', '2026-06-30 04:56:25', '2026-06-30 04:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `foto_profil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_dosen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `id_user`, `foto_profil`, `nama_dosen`, `email`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Dr. Budi Hartono', 'budi.hartono@univ.ac.id', '081234567890', '2026-06-27 23:07:16', '2026-06-27 23:07:16'),
(2, 2, NULL, 'Prof. Siti Rahayu', 'siti.rahayu@univ.ac.id', '081234567891', '2026-06-27 23:07:16', '2026-06-27 23:07:16'),
(3, 6, NULL, 'Dr. Agus Salim', 'agus.salim@univ.ac.id', '081234567892', '2026-06-27 23:07:16', '2026-06-27 23:07:16'),
(4, 7, NULL, 'Prof. Dewi Sartika', 'dewi.sartika@univ.ac.id', '081234567893', '2026-06-27 23:07:16', '2026-06-27 23:07:16'),
(5, 8, NULL, 'Dr. Rudi Hermawan', 'rudi.hermawan@univ.ac.id', '081234567894', '2026-06-27 23:07:16', '2026-06-27 23:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `khs`
--

CREATE TABLE `khs` (
  `id_khs` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `id_matkul` int DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `grade` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khs`
--

INSERT INTO `khs` (`id_khs`, `id_mahasiswa`, `id_matkul`, `nilai_akhir`, `grade`, `ipk`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 85.00, 'A', 4.00, '2026-06-27 23:08:42', '2026-06-27 23:08:42'),
(2, 2, 3, 75.00, 'B', 3.00, '2026-06-27 23:08:42', '2026-06-27 23:08:42'),
(3, 3, 1, 90.00, 'A', 4.00, '2026-06-27 23:08:42', '2026-06-27 23:08:42'),
(4, 4, 4, 65.00, 'C', 2.00, '2026-06-27 23:08:42', '2026-06-27 23:08:42'),
(5, 5, 2, 80.00, 'B+', 3.00, '2026-06-27 23:08:42', '2026-06-27 23:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id_krs` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `id_matkul` int DEFAULT NULL,
  `semester` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id_krs`, `id_mahasiswa`, `id_matkul`, `semester`) VALUES
(1, 1, 1, 4),
(2, 1, 2, 4),
(3, 2, 3, 2),
(4, 3, 1, 6),
(5, 4, 4, 4),
(6, 1, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `nama_mahasiswa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prodi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `semester` int DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_profil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nama_mahasiswa`, `kelas`, `prodi`, `semester`, `email`, `no_hp`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 3, 'Citra Dewi', 'A', 'Teknik Informatika', 4, 'citra@student.ac.id', '081234567892', NULL, '2026-06-27 23:11:33', '2026-06-27 23:11:33'),
(2, 4, 'Dedi Kurniawan', 'B', 'Sistem Informasi', 2, 'dedi@student.ac.id', '081234567893', NULL, '2026-06-27 23:11:33', '2026-06-27 23:11:33'),
(3, 5, 'Eka Putri', 'A', 'Teknik Informatika', 6, 'eka@student.ac.id', '081234567894', NULL, '2026-06-27 23:11:33', '2026-06-27 23:11:33'),
(4, 9, 'Fajar Ramadhan', 'B', 'Sistem Informasi', 4, 'fajar@student.ac.id', '081234567895', NULL, '2026-06-27 23:11:33', '2026-06-27 23:11:33'),
(5, 10, 'Gita Lestari', 'A', 'Teknik Informatika', 2, 'gita@student.ac.id', '081234567896', NULL, '2026-06-27 23:11:33', '2026-06-27 23:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `id_matkul` int NOT NULL,
  `id_dosen` int DEFAULT NULL,
  `kode_matkul` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_matkul` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sks` int DEFAULT NULL,
  `semester` int DEFAULT NULL,
  `waktu_matkul` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matakuliah`
--

INSERT INTO `matakuliah` (`id_matkul`, `id_dosen`, `kode_matkul`, `nama_matkul`, `sks`, `semester`, `waktu_matkul`, `created_at`, `updated_at`) VALUES
(1, 1, 'MK101', 'Pemrograman Web', 3, 3, 'Senin 08:00-10:00', '2026-06-27 23:12:59', '2026-06-29 07:53:13'),
(2, 4, 'MK102', 'Basis Data', 3, 2, 'Selasa 10:00-12:00', '2026-06-27 23:12:59', '2026-06-27 23:12:59'),
(3, 2, 'MK201', 'Jaringan Komputer', 3, 3, 'Rabu 13:00-15:00', '2026-06-27 23:12:59', '2026-06-27 23:12:59'),
(4, 3, 'MK202', 'Sistem Operasi', 3, 4, 'Kamis 08:00-10:00', '2026-06-27 23:12:59', '2026-06-27 23:12:59'),
(5, 5, 'MK103', 'Algoritma dan Struktur Data', 2, 2, 'Jumat 10:00-12:00', '2026-06-27 23:12:59', '2026-06-27 23:12:59'),
(6, 1, 'MK102', 'BASIS DATA', 3, 3, 'SENIN 10.00-11.40', '2026-06-29 07:53:03', '2026-06-29 07:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id_materi` int NOT NULL,
  `id_matkul` int DEFAULT NULL,
  `id_dosen` int DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deksripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id_materi`, `id_matkul`, `id_dosen`, `file_path`, `deksripsi`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'uploads/web_1.pdf', 'Modul 1: Pengenalan HTML & CSS', '2026-06-27 23:32:40', '2026-06-27 23:32:40'),
(2, 1, 1, 'uploads/web_2.pdf', 'Modul 2: JavaScript Dasar', '2026-06-27 23:32:40', '2026-06-27 23:32:40'),
(3, 2, 1, 'uploads/db_1.pdf', 'Modul Basis Data: ERD & Normalisasi', '2026-06-27 23:32:40', '2026-06-27 23:32:40'),
(4, 3, 2, 'uploads/jarkom_1.pdf', 'Modul Jaringan: Model OSI Layer', '2026-06-27 23:32:40', '2026-06-27 23:32:40'),
(5, 4, 2, 'uploads/sisop_1.pdf', 'Modul Sistem Operasi: Manajemen Proses', '2026-06-27 23:32:40', '2026-06-27 23:32:40'),
(6, 1, 1, 'uploads/materi/AjMkeQmCbcxIYk1VaiepJtivLpD4aPqRIalKVkb8.pdf', 'apa aja boleh', '2026-06-29 10:50:37', '2026-06-29 10:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id_up_tugas` int NOT NULL,
  `id_tugas` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `link_pengumpulan_tugas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_tugas` int NOT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumpulan_tugas`
--

INSERT INTO `pengumpulan_tugas` (`id_up_tugas`, `id_tugas`, `id_mahasiswa`, `link_pengumpulan_tugas`, `nilai_tugas`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'uploads/jawaban_web1_citra.pdf', 85, 'Bagus, layout rapi', '2026-06-27 23:48:23', '2026-06-27 23:48:23'),
(2, 2, 2, 'uploads/jawaban_web2_dedi.pdf', 70, 'Perbaiki validasi input', '2026-06-27 23:48:23', '2026-06-27 23:48:23'),
(3, 3, 3, 'uploads/jawaban_db_eka.pdf', 90, 'ERD sangat baik', '2026-06-27 23:48:23', '2026-06-27 23:48:23'),
(4, 4, 4, 'uploads/jawaban_jarkom_fajar.pdf', 75, 'Cukup lengkap', '2026-06-27 23:48:23', '2026-06-27 23:48:23'),
(5, 5, 5, 'uploads/jawaban_sisop_gita.pdf', 80, 'Baik, tambahkan referensi', '2026-06-27 23:48:23', '2026-06-27 23:48:23'),
(6, 2, 1, 'https://drive.google.com/drive/folders/1HfgRTh7oUt6phd4wiqZixdnaDTlYVxeK?usp=drive_link', 0, '', '2026-06-29 10:20:49', '2026-06-29 10:20:49'),
(7, 7, 1, 'https://drive.google.com/drive/folders/1HfgRTh7oUt6phd4wiqZixdnaDTlYVxeK?usp=drive_link', 90, 'kebanyakan pakai gpt', '2026-06-29 10:36:42', '2026-06-29 10:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_uas`
--

CREATE TABLE `pengumpulan_uas` (
  `id_up_uas` int NOT NULL,
  `id_uas` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `link_pengumpulan_uas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kritik_dan_saran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uas` int NOT NULL,
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumpulan_uas`
--

INSERT INTO `pengumpulan_uas` (`id_up_uas`, `id_uas`, `id_mahasiswa`, `link_pengumpulan_uas`, `kritik_dan_saran`, `nilai_uas`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'uploads/jawaban_uas_web_citra.pdf', 'Soal cukup jelas', 82, 'Baik', '2026-06-27 23:50:01', '2026-06-27 23:50:01'),
(2, 2, 2, 'uploads/jawaban_uas_db_dedi.pdf', 'Waktu kurang', 68, 'Perbaiki', '2026-06-27 23:50:01', '2026-06-27 23:50:01'),
(3, 3, 3, 'uploads/jawaban_uas_jarkom_eka.pdf', 'Materi banyak', 92, 'Sangat baik', '2026-06-27 23:50:01', '2026-06-27 23:50:01'),
(4, 4, 4, 'uploads/jawaban_uas_sisop_fajar.pdf', 'Sulit dipahami', 70, 'Cukup', '2026-06-27 23:50:01', '2026-06-27 23:50:01'),
(5, 5, 5, 'uploads/jawaban_uas_algo_gita.pdf', 'Bagus', 88, 'Baik', '2026-06-27 23:50:01', '2026-06-27 23:50:01'),
(6, 6, 1, 'https://drive.google.com/drive/folders/1HfgRTh7oUt6phd4wiqZixdnaDTlYVxeK?usp=drive_link', 'cihuyyy', 0, '', '2026-06-29 14:31:17', '2026-06-29 14:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_uts`
--

CREATE TABLE `pengumpulan_uts` (
  `id_up_uts` int NOT NULL,
  `id_uts` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `link_pengumpulan_uts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uts` int NOT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumpulan_uts`
--

INSERT INTO `pengumpulan_uts` (`id_up_uts`, `id_uts`, `id_mahasiswa`, `link_pengumpulan_uts`, `nilai_uts`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'uploads/jawaban_uts_web_citra.pdf', 80, 'Perhatikan konsep', '2026-06-27 23:51:34', '2026-06-27 23:51:34'),
(2, 2, 2, 'uploads/jawaban_uts_db_dedi.pdf', 75, 'Cukup', '2026-06-27 23:51:34', '2026-06-27 23:51:34'),
(3, 3, 3, 'uploads/jawaban_uts_jarkom_eka.pdf', 95, 'Excellent!', '2026-06-27 23:51:34', '2026-06-27 23:51:34'),
(4, 4, 4, 'uploads/jawaban_uts_sisop_fajar.pdf', 65, 'Perlu belajar lagi', '2026-06-27 23:51:34', '2026-06-27 23:51:34'),
(5, 5, 5, 'uploads/jawaban_uts_algo_gita.pdf', 85, 'Bagus', '2026-06-27 23:51:34', '2026-06-27 23:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int NOT NULL,
  `id_matkul` int NOT NULL,
  `id_dosen` int NOT NULL,
  `judul` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deadline` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soal_tugas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `id_matkul`, `id_dosen`, `judul`, `deskripsi`, `deadline`, `soal_tugas`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Tugas 1 Web', 'Buat halaman profil sederhana', '2026-07-15 23:59:59', 'uploads/soal_web1.pdf', '2026-06-27 23:42:26', '2026-06-27 23:42:26'),
(2, 1, 1, 'Tugas 2 Web', 'Implementasi form login', '2026-07-30 23:59:59', 'uploads/soal_web2.pdf', '2026-06-27 23:42:26', '2026-06-27 23:42:26'),
(4, 3, 2, 'Tugas Jaringan', 'Analisis protokol TCP/IP', '2026-08-10 23:59:59', 'uploads/soal_jarkom1.pdf', '2026-06-27 23:42:26', '2026-06-27 23:42:26'),
(5, 4, 2, 'Tugas Sistem Operasi', 'Simulasi penjadwalan proses', '2026-08-15 23:59:59', 'uploads/soal_sisop1.pdf', '2026-06-27 23:42:26', '2026-06-27 23:42:26'),
(6, 6, 1, 'tugas P5', 'membuat web', '2026-06-29T17:32', 'uploads/tugas/dWJuDQSwnwXJEbCUVAZtkATgMC0RckysJfsxwH6h.pdf', '2026-06-29 10:32:22', '2026-06-29 10:32:22'),
(7, 1, 1, 'Tugas p6', 'silahkan anda membuat web sesuai dengan kreativitas anda', '2026-06-30T17:36', 'uploads/tugas/OBwKZ0RtVwVBPLDu8EfddnBtKVu9c7ciLD9gpGKY.pdf', '2026-06-29 10:36:15', '2026-06-29 10:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `uas`
--

CREATE TABLE `uas` (
  `id_uas` int NOT NULL,
  `id_matkul` int NOT NULL,
  `id_dosen` int NOT NULL,
  `deadline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soal_uas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uas`
--

INSERT INTO `uas` (`id_uas`, `id_matkul`, `id_dosen`, `deadline`, `soal_uas`, `created_at`, `updated_at`) VALUES
(2, 2, 1, '2026-08-22 23:59:59', 'uploads/uas_db.pdf', '2026-06-27 23:45:03', '2026-06-27 23:45:03'),
(3, 3, 2, '2026-08-25 23:59:59', 'uploads/uas_jarkom.pdf', '2026-06-27 23:45:03', '2026-06-27 23:45:03'),
(4, 4, 2, '2026-08-28 23:59:59', 'uploads/uas_sisop.pdf', '2026-06-27 23:45:03', '2026-06-27 23:45:03');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nidn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `npm` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nidn`, `npm`, `password`) VALUES
(1, '123456', '', 'password123'),
(2, '654321', '', 'password456'),
(3, '', '23067000101', 'passmhs1'),
(4, '', '23067000102', 'passmhs2'),
(5, '', '23067000103', 'passmhs3'),
(124, '', '23067000100', '1234567'),
(125, '21234567', '', '234167');

-- --------------------------------------------------------

--
-- Table structure for table `uts`
--

CREATE TABLE `uts` (
  `id_uts` int NOT NULL,
  `id_matkul` int NOT NULL,
  `id_dosen` int NOT NULL,
  `deadline` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soal_uts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uts`
--

INSERT INTO `uts` (`id_uts`, `id_matkul`, `id_dosen`, `deadline`, `soal_uts`, `created_at`, `updated_at`) VALUES
(3, 3, 2, '2026-07-25 23:59:59', 'uploads/uts_jarkom.pdf', '2026-06-27 23:44:00', '2026-06-27 23:44:00'),
(4, 4, 2, '2026-07-28 23:59:59', 'uploads/uts_sisop.pdf', '2026-06-27 23:44:00', '2026-06-27 23:44:00'),
(5, 5, 1, '2026-08-01 23:59:59', 'uploads/uts_algo.pdf', '2026-06-27 23:44:00', '2026-06-27 23:44:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_matkul` (`id_matkul`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `khs`
--
ALTER TABLE `khs`
  ADD PRIMARY KEY (`id_khs`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`,`id_matkul`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id_krs`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`,`id_matkul`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`id_matkul`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `id_matkul` (`id_matkul`,`id_dosen`);

--
-- Indexes for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id_up_tugas`),
  ADD KEY `id_tugas` (`id_tugas`,`id_mahasiswa`);

--
-- Indexes for table `pengumpulan_uas`
--
ALTER TABLE `pengumpulan_uas`
  ADD PRIMARY KEY (`id_up_uas`),
  ADD KEY `id_uas` (`id_uas`,`id_mahasiswa`);

--
-- Indexes for table `pengumpulan_uts`
--
ALTER TABLE `pengumpulan_uts`
  ADD PRIMARY KEY (`id_up_uts`),
  ADD KEY `id_uts` (`id_uts`,`id_mahasiswa`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `id_matkul` (`id_matkul`,`id_dosen`);

--
-- Indexes for table `uas`
--
ALTER TABLE `uas`
  ADD PRIMARY KEY (`id_uas`),
  ADD KEY `id_matkul` (`id_matkul`,`id_dosen`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `uts`
--
ALTER TABLE `uts`
  ADD PRIMARY KEY (`id_uts`),
  ADD KEY `id_matkul` (`id_matkul`,`id_dosen`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id_absen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id_dosen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `khs`
--
ALTER TABLE `khs`
  MODIFY `id_khs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id_krs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `id_matkul` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id_up_tugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengumpulan_uas`
--
ALTER TABLE `pengumpulan_uas`
  MODIFY `id_up_uas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengumpulan_uts`
--
ALTER TABLE `pengumpulan_uts`
  MODIFY `id_up_uts` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `uas`
--
ALTER TABLE `uas`
  MODIFY `id_uas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `uts`
--
ALTER TABLE `uts`
  MODIFY `id_uts` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
