/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : komite_app

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 26/07/2025 09:35:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pegawai
-- ----------------------------
DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jk` enum('Pria','Wanita') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jbtn` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jnj_jabatan` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kode_kelompok` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kode_resiko` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kode_emergency` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `departemen` char(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bidang` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `stts_wp` char(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `stts_kerja` char(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `npwp` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pendidikan` varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gapok` double NOT NULL,
  `tmp_lahir` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kota` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mulai_kerja` date NOT NULL,
  `ms_kerja` enum('<1','PT','FT>1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `indexins` char(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bpd` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rekening` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `stts_aktif` enum('AKTIF','CUTI','KELUAR','TENAGA LUAR','NON AKTIF') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `wajibmasuk` tinyint NOT NULL,
  `pengurang` double NOT NULL,
  `indek` tinyint NOT NULL,
  `mulai_kontrak` date NULL DEFAULT NULL,
  `cuti_diambil` int NOT NULL,
  `dankes` double NOT NULL,
  `photo` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_ktp` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`, `nik`) USING BTREE,
  INDEX `departemen`(`departemen` ASC) USING BTREE,
  INDEX `bidang`(`bidang` ASC) USING BTREE,
  INDEX `stts_wp`(`stts_wp` ASC) USING BTREE,
  INDEX `stts_kerja`(`stts_kerja` ASC) USING BTREE,
  INDEX `pendidikan`(`pendidikan` ASC) USING BTREE,
  INDEX `indexins`(`indexins` ASC) USING BTREE,
  INDEX `jnj_jabatan`(`jnj_jabatan` ASC) USING BTREE,
  INDEX `bpd`(`bpd` ASC) USING BTREE,
  INDEX `nama`(`nama` ASC) USING BTREE,
  INDEX `jbtn`(`jbtn` ASC) USING BTREE,
  INDEX `npwp`(`npwp` ASC) USING BTREE,
  INDEX `dankes`(`dankes` ASC) USING BTREE,
  INDEX `cuti_diambil`(`cuti_diambil` ASC) USING BTREE,
  INDEX `mulai_kontrak`(`mulai_kontrak` ASC) USING BTREE,
  INDEX `stts_aktif`(`stts_aktif` ASC) USING BTREE,
  INDEX `tmp_lahir`(`tmp_lahir` ASC) USING BTREE,
  INDEX `alamat`(`alamat` ASC) USING BTREE,
  INDEX `mulai_kerja`(`mulai_kerja` ASC) USING BTREE,
  INDEX `gapok`(`gapok` ASC) USING BTREE,
  INDEX `kota`(`kota` ASC) USING BTREE,
  INDEX `pengurang`(`pengurang` ASC) USING BTREE,
  INDEX `indek`(`indek` ASC) USING BTREE,
  INDEX `jk`(`jk` ASC) USING BTREE,
  INDEX `ms_kerja`(`ms_kerja` ASC) USING BTREE,
  INDEX `tgl_lahir`(`tgl_lahir` ASC) USING BTREE,
  INDEX `rekening`(`rekening` ASC) USING BTREE,
  INDEX `wajibmasuk`(`wajibmasuk` ASC) USING BTREE,
  INDEX `kode_emergency`(`kode_emergency` ASC) USING BTREE,
  INDEX `kode_kelompok`(`kode_kelompok` ASC) USING BTREE,
  INDEX `kode_resiko`(`kode_resiko` ASC) USING BTREE,
  INDEX `nik_2`(`nik` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 136667 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pegawai
-- ----------------------------
INSERT INTO `pegawai` VALUES (136, '1234567889', 'P', 'Pria', 'IT', '-', '-', '-', '-', 'IT', '-', '-', '-', '-', '-', 0, '-', '0000-00-00', '-', '-', '0000-00-00', '', '-', '-', '-', 'AKTIF', 0, 0, 0, '0000-00-00', 0, 0, '-', '-');
INSERT INTO `pegawai` VALUES (137, '1234567890', 'Ahmad Fauzi', 'Pria', 'Programmer', '', '', '', '', 'IT', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (138, '1234567891', 'Siti Nurhaliza', 'Pria', 'HR Manager', '', '', '', '', 'HR', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (139, '1234567892', 'Budi Santoso', 'Pria', 'Accountant', '', '', '', '', 'Fina', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (140, '1234567893', 'Dewi Sartika', 'Pria', 'Marketing Executive', '', '', '', '', 'Mark', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (141, '1234567894', 'Eko Prasetyo', 'Pria', 'KEPALA UNIT RAWAT INAP', '', '', '', '', 'Oper', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (142, '1234567895', 'Fitri Handayani', 'Pria', 'System Analyst', '', '', '', '', 'IT', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (143, '1234567896', 'Gunawan Wijaya', 'Pria', 'Finance Manager', '', '', '', '', 'Fina', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (144, '1234567897', 'Hani Kusuma', 'Pria', 'Recruiter', '', '', '', '', 'HR', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (145, '1234567898', 'Indra Permana', 'Pria', 'Sales Manager', '', '', '', '', 'Mark', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');
INSERT INTO `pegawai` VALUES (146, '1234567899', 'Joko Widodo', 'Pria', 'Supervisor', '', '', '', '', 'Oper', '', '', '', '', '', 0, '', '0000-00-00', '', '', '0000-00-00', '<1', '', '', '', 'AKTIF', 0, 0, 0, NULL, 0, 0, NULL, '');

-- ----------------------------
-- Table structure for tb_indikator
-- ----------------------------
DROP TABLE IF EXISTS `tb_indikator`;
CREATE TABLE `tb_indikator`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `violation_name` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `get_point` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_indikator
-- ----------------------------
INSERT INTO `tb_indikator` VALUES (1, 'AD-I', 'Ketepatan waktu datang (on time)', 1);
INSERT INTO `tb_indikator` VALUES (2, 'AD-II', 'Izin / cuti dengan prosedur yang benar', 1);
INSERT INTO `tb_indikator` VALUES (3, 'AD-III', 'Terlambat (>5 menit)', 1);
INSERT INTO `tb_indikator` VALUES (4, 'AD-IV', 'Sakit (dengan surat dokter)', 1);
INSERT INTO `tb_indikator` VALUES (5, 'AD-V', 'Alpha (tanpa keterangan)', 1);
INSERT INTO `tb_indikator` VALUES (6, 'AD-VIII', 'Kerja Sama (Mampu bekerja dalam tim dan menghargai rekan kerja)', 1);
INSERT INTO `tb_indikator` VALUES (7, 'AD-IX', 'Kepedulian terhadap Pasien (Ramah, empati, dan profesional dalam melayani pasien)', 1);
INSERT INTO `tb_indikator` VALUES (8, 'AD-X', 'Komunikasi (Komunikasi efektif dengan pasien, keluarga, dan sesama karyawan)', 1);
INSERT INTO `tb_indikator` VALUES (9, 'AD-XI', 'Etika dan Integritas (Jujur, menjaga privasi pasien, tidak menyalahgunakan wewenang)', 1);
INSERT INTO `tb_indikator` VALUES (10, 'AK-I', 'Rambut atau hijab rapi dan sesuai standar', 1);
INSERT INTO `tb_indikator` VALUES (11, 'AK-II', 'Kuku bersih dan pendek (tidak memakai kuteks)', 1);
INSERT INTO `tb_indikator` VALUES (12, 'AK-III', 'Seragam sesuai dengan jadwal', 1);
INSERT INTO `tb_indikator` VALUES (13, 'AK-IV', 'Menggunakan identitas diri (name tag/ID card)', 1);
INSERT INTO `tb_indikator` VALUES (14, 'AK-V', 'Sepatu hitam/navy', 1);
INSERT INTO `tb_indikator` VALUES (15, 'AK-VI', 'Tidak memakai aksesoris/perhiasan berlebihan ', 1);
INSERT INTO `tb_indikator` VALUES (16, 'AK-VII', 'Menggunakan kaos kaki cream/hitam', 1);
INSERT INTO `tb_indikator` VALUES (17, 'AK-VIII', 'Menunjukkan ekspresi wajah ramah saat berinteraksi', 1);
INSERT INTO `tb_indikator` VALUES (18, 'AK-IX', 'Memberikan salam kepada pasien dan keluarga secara aktif', 1);
INSERT INTO `tb_indikator` VALUES (19, 'HC-VIII', 'Tidak mengambil keuntungan pribadi dari situasi pelayanan (oportunisme)', 1);
INSERT INTO `tb_indikator` VALUES (20, 'HC-IX', 'Ada kebijakan jelas terkait larangan tindakan opportunis', 1);
INSERT INTO `tb_indikator` VALUES (21, 'HC-X', 'Tersedia mekanisme pelaporan tindakan opportunis', 1);
INSERT INTO `tb_indikator` VALUES (22, 'HC-XI', 'Tindakan disiplin diberikan kepada staf yang melakukan opportunis', 1);
INSERT INTO `tb_indikator` VALUES (23, 'HC-XII', 'Dokumentasi kasus perilaku opportunis tersedia dan terarsip dengan baik', 1);
INSERT INTO `tb_indikator` VALUES (24, 'HC-XIII', 'Pelatihan/sosialisasi anti-opportunis dilakukan secara rutin', 1);
INSERT INTO `tb_indikator` VALUES (25, 'HC-XIV', 'Survei Budaya Pelayanan Prima', 1);

-- ----------------------------
-- Table structure for tb_penilaian
-- ----------------------------
DROP TABLE IF EXISTS `tb_penilaian`;
CREATE TABLE `tb_penilaian`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `pegawai_id` int NOT NULL,
  `indikator_id` int NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `pegawai_id`(`pegawai_id` ASC) USING BTREE,
  INDEX `indikator_id`(`indikator_id` ASC) USING BTREE,
  CONSTRAINT `tb_penilaian_ibfk_2` FOREIGN KEY (`indikator_id`) REFERENCES `tb_indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_penilaian_ibfk_4` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_penilaian_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_penilaian
-- ----------------------------

-- ----------------------------
-- Table structure for tb_users
-- ----------------------------
DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE `tb_users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `pegawai_id` int NOT NULL,
  `username_nik` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `level` enum('Admin','User') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` int NOT NULL DEFAULT 1,
  `remember_me` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username_nik`(`username_nik` ASC) USING BTREE,
  INDEX `pegawai_id`(`pegawai_id` ASC) USING BTREE,
  CONSTRAINT `tb_users_ibfk_1` FOREIGN KEY (`username_nik`) REFERENCES `pegawai` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_users_ibfk_2` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_users
-- ----------------------------
INSERT INTO `tb_users` VALUES (1, 136, '1234567889', '$10$qGtSTKeASa.Z1PZDD7vAcu.IYSukWOmT.DptVS1xxle98ti85Tpdi', 'Admin', 1, 'PuRrF2IySPM5nDZ8NGvbwlYHkFB5O98sgJad7UGW0Q3JHVAjf4ymx7x6YsRcpu96');
INSERT INTO `tb_users` VALUES (2, 137, '1234567890', '$10$qGtSTKeASa.Z1PZDD7vAcu.IYSukWOmT.DptVS1xxle98ti85Tpdi', 'User', 1, 'PuRrF2IySPM5nDZ8NGvbwlYHkFB5O98sgJad7UGW0Q3JHVAjf4ymx7x6YsRcpu96');

-- ----------------------------
-- Table structure for tb_website
-- ----------------------------
DROP TABLE IF EXISTS `tb_website`;
CREATE TABLE `tb_website`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `school_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_website
-- ----------------------------
INSERT INTO `tb_website` VALUES (1, 'Komite SE Rayhan');

SET FOREIGN_KEY_CHECKS = 1;
