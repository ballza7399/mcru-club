/*
 Navicat Premium Dump SQL

 Source Server         : mcru-club
 Source Server Type    : MySQL
 Source Server Version : 80410 (8.4.10)
 Source Host           : 157.85.96.163:3306
 Source Schema         : mcru-club

 Target Server Type    : MySQL
 Target Server Version : 80410 (8.4.10)
 File Encoding         : 65001

 Date: 20/06/2026 00:50:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clubs
-- ----------------------------
DROP TABLE IF EXISTS `clubs`;
CREATE TABLE `clubs`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงชมรม (Primary Key)',
  `club_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อชมรม',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT 'รายละเอียด/คำอธิบายชมรม',
  `club_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'path หรือ URL รูปโลโก้ชมรม',
  `qr_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'path หรือ URL QR code สำหรับสมัครเข้าชมรม',
  `max_members` int NULL DEFAULT 50 COMMENT 'จำนวนสมาชิกสูงสุดที่รับได้',
  `president_id` int NULL DEFAULT NULL COMMENT 'FK อ้างถึง users.id ผู้เป็นประธานชมรมนี้ (NULL ถ้ายังไม่มีประธาน)',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `president_id`(`president_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลชมรมต่างๆ ในระบบ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clubs
-- ----------------------------
INSERT INTO `clubs` VALUES (1, 'ชมรมนวัตกรรม IoT และสมาร์ทฟาร์ม', 'มุ่งเน้นการสร้างสรรค์นวัตกรรม Internet of Things (IoT) การเขียนโปรแกรมควบคุมไมโครคอนโทรลเลอร์ (เช่น ESP32) และการใช้งานเซนเซอร์ต่างๆ เพื่อนำมาประยุกต์ใช้กับระบบสมาร์ทฟาร์มอัตโนมัติ', NULL, NULL, 30, 3);
INSERT INTO `clubs` VALUES (2, 'ชมรมพัฒนาเว็บแอปพลิเคชัน', 'พื้นที่สำหรับผู้ที่สนใจการเขียนโปรแกรมฝั่ง Frontend และ Backend (PHP, MySQL, Bootstrap) เพื่อสร้างระบบและแอปพลิเคชันบนเว็บไซต์ที่ใช้งานได้จริง', NULL, NULL, 40, NULL);
INSERT INTO `clubs` VALUES (3, 'ชมรมช่างซ่อมคอมพิวเตอร์และฮาร์ดแวร์', 'เรียนรู้และฝึกปฏิบัติจริงเกี่ยวกับการประกอบเครื่องคอมพิวเตอร์ การแก้ไขปัญหาฮาร์ดแวร์ (เช่น อาการจอดำ, เสียง Beep Code) การดูแลรักษาปรินเตอร์ และการวางระบบเครือข่าย', NULL, NULL, 20, NULL);
INSERT INTO `clubs` VALUES (4, 'ชมรมคนรักอนิเมะและซีรีส์จีน (Donghua)', 'ศูนย์รวมคนรักศิลปะและวัฒนธรรมความบันเทิงจากจีน พูดคุยแลกเปลี่ยนเรื่องราวอนิเมะจีน (ตงฮวา) ซีรีส์แนวกำลังภายใน นิยายแปล และวัฒนธรรมร่วมสมัย', NULL, NULL, 50, NULL);
INSERT INTO `clubs` VALUES (5, 'ชมรมคนรักสัตว์เลี้ยงขนาดเล็ก', 'แลกเปลี่ยนความรู้ในการดูแลและเพาะเลี้ยงสัตว์เลี้ยงขนาดเล็ก แนะนำวิธีการให้อาหาร การจัดการที่อยู่อาศัย และการสังเกตพฤติกรรมสัตว์อย่างถูกต้อง', NULL, NULL, 25, NULL);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงบทบาท (Primary Key)',
  `role_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'คีย์ระบบ เช่น admin, student, president, officer',
  `role_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อบทบาทภาษาไทย',
  `scope` enum('system','club') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'system' COMMENT 'ขอบเขตบทบาท: system=ระบบหลัก, club=แอดมิน/ตำแหน่งในชมรม',
  `club_id` int NULL DEFAULT NULL COMMENT 'FK อ้างอิง clubs.id (NULL หากเป็นตำแหน่งระบบหลัก)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_role_key_club`(`role_key` ASC, `club_id` ASC) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลบทบาทและตำแหน่งต่าง ๆ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'admin', 'ผู้ดูแลระบบหลัก', 'system', NULL);
INSERT INTO `roles` VALUES (2, 'student', 'นักศึกษาทั่วไป', 'system', NULL);
INSERT INTO `roles` VALUES (3, 'president', 'ประธานชมรม', 'club', NULL);
INSERT INTO `roles` VALUES (4, 'officer', 'กรรมการชมรม', 'club', NULL);
INSERT INTO `roles` VALUES (5, 'member', 'สมาชิกชมรม', 'club', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงผู้ใช้ (Primary Key)',
  `student_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'รหัสนักศึกษา ใช้เป็น username สำหรับ login (unique)',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'อีเมลผู้ใช้งาน',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'รหัสผ่าน (ปัจจุบันเป็น plain text - ควร hash ก่อนใช้งานจริง)',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อ-นามสกุล',
  `faculty` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'คณะที่สังกัด',
  `major` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'สาขาวิชา',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'เบอร์โทรศัพท์ติดต่อ',
  `role_id` int NOT NULL DEFAULT 2 COMMENT 'FK อ้างถึง roles.id (บทบาทหลักในระบบ)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_id`(`student_id` ASC) USING BTREE,
  INDEX `role_id`(`role_id` ASC) USING BTREE,
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ผู้ใช้งานทุกประเภทในระบบ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'admin@mbcr.ac.th', '$2y$12$d/berMaKOazGofbQB89Pzejz.w4M8gselo/eUHXusFFGZXVWdQFTW', 'ผู้ดูแลระบบ', '-', '-', '-', 1);
INSERT INTO `users` VALUES (2, '660001', 'student@mbcr.ac.th', '$2y$12$EnkFZ4Koo7zr0TrjL15NrOwbmYf1pgYIG5hhy/1y/4v/wHjhfC.am', 'สมชาย ใจดี', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'เทคโนโลยีสารสนเทศ (IT)', '0812345678', 2);
INSERT INTO `users` VALUES (3, '660002', 'president@mbcr.ac.th', '$2y$12$EnkFZ4Koo7zr0TrjL15NrOwbmYf1pgYIG5hhy/1y/4v/wHjhfC.am', 'ประธาน ชมรมที่1', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'วิทยาการคอมพิวเตอร์ (CS)', '0899999999', 2);

-- Adjust foreign key in clubs now that users table is created
ALTER TABLE `clubs` ADD CONSTRAINT `clubs_ibfk_1` FOREIGN KEY (`president_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

-- ----------------------------
-- Table structure for applications
-- ----------------------------
DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงคำขอ (Primary Key)',
  `user_id` int NULL DEFAULT NULL COMMENT 'FK ผู้สมัคร อ้างถึง users.id',
  `club_id` int NULL DEFAULT NULL COMMENT 'FK ชมรมที่สมัคร อ้างถึง clubs.id',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'pending' COMMENT 'สถานะคำขอ: pending=รอพิจารณา, approved=อนุมัติแล้ว, rejected=ปฏิเสธ',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'คำขอสมัครเข้าชมรมของนักศึกษา' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for club_members
-- ----------------------------
DROP TABLE IF EXISTS `club_members`;
CREATE TABLE `club_members` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิกชมรม (Primary Key)',
  `club_id` int NOT NULL COMMENT 'FK ชมรม อ้างถึง clubs.id',
  `user_id` int NOT NULL COMMENT 'FK ผู้ใช้งาน อ้างถึง users.id',
  `role_id` int NULL DEFAULT 5 COMMENT 'FK บทบาท/ตำแหน่งในชมรม อ้างถึง roles.id (Default คือ สมาชิกชมรม)',
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่เข้าร่วมชมรม',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_club_user`(`club_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `role_id`(`role_id` ASC) USING BTREE,
  CONSTRAINT `club_members_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `club_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `club_members_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'รายชื่อสมาชิกในชมรมและตำแหน่งหน้าที่' ROW_FORMAT = Dynamic;

-- Insert President of Club 1 into club_members
INSERT INTO `club_members` (`club_id`, `user_id`, `role_id`) VALUES (1, 3, 3);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสิทธิ์ (Primary Key)',
  `perm_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL UNIQUE COMMENT 'คีย์อ้างอิงสิทธิ์ เช่น manage_clubs, post_news',
  `perm_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อสิทธิ์ภาษาไทย',
  `scope` enum('system','club') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'system' COMMENT 'ระดับสิทธิ์: system=ระบบหลัก, club=แอดมินชมรม',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บสิทธิ์การใช้งานระบบ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
-- System permissions
INSERT INTO `permissions` VALUES (1, 'manage_clubs', 'จัดการชมรมทั้งหมด (สร้าง/แก้ไข/ลบ)', 'system');
INSERT INTO `permissions` VALUES (2, 'manage_users', 'จัดการบทบาทผู้ใช้งานระบบ', 'system');
INSERT INTO `permissions` VALUES (3, 'manage_system_news', 'จัดการข่าวประชาสัมพันธ์มหาวิทยาลัย', 'system');
INSERT INTO `permissions` VALUES (4, 'manage_system_events', 'จัดการกิจกรรมระดับมหาวิทยาลัย', 'system');

-- Club permissions
INSERT INTO `permissions` VALUES (5, 'manage_club_info', 'แก้ไขข้อมูลชมรม/โลโก้/QR', 'club');
INSERT INTO `permissions` VALUES (6, 'manage_club_members', 'อนุมัติ/ปฏิเสธและคัดสมาชิกชมรมออก', 'club');
INSERT INTO `permissions` VALUES (7, 'manage_club_roles', 'จัดการตำแหน่งและระดับสิทธิ์ภายในชมรม', 'club');
INSERT INTO `permissions` VALUES (8, 'post_club_news', 'เขียนข่าวประชาสัมพันธ์ชมรม', 'club');
INSERT INTO `permissions` VALUES (9, 'manage_club_events', 'จัดการปฏิทินและกำหนดการชมรม', 'club');
INSERT INTO `permissions` VALUES (10, 'manage_club_gallery', 'อัปโหลดและลบรูปภาพกิจกรรม', 'club');

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL COMMENT 'FK อ้างถึง roles.id',
  `permission_id` int NOT NULL COMMENT 'FK อ้างถึง permissions.id',
  PRIMARY KEY (`role_id`, `permission_id`) USING BTREE,
  INDEX `permission_id`(`permission_id` ASC) USING BTREE,
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางความสัมพันธ์บทบาทและสิทธิ์' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
-- System Admin permissions (All System Permissions)
INSERT INTO `role_permissions` VALUES (1, 1);
INSERT INTO `role_permissions` VALUES (1, 2);
INSERT INTO `role_permissions` VALUES (1, 3);
INSERT INTO `role_permissions` VALUES (1, 4);

-- Club President permissions (All Club Permissions)
INSERT INTO `role_permissions` VALUES (3, 5);
INSERT INTO `role_permissions` VALUES (3, 6);
INSERT INTO `role_permissions` VALUES (3, 7);
INSERT INTO `role_permissions` VALUES (3, 8);
INSERT INTO `role_permissions` VALUES (3, 9);
INSERT INTO `role_permissions` VALUES (3, 10);

-- Club Officer permissions (Some Club Permissions)
INSERT INTO `role_permissions` VALUES (4, 8);
INSERT INTO `role_permissions` VALUES (4, 9);
INSERT INTO `role_permissions` VALUES (4, 10);

-- ----------------------------
-- Table structure for announcements (PR News)
-- ----------------------------
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสข่าวสาร (Primary Key)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'หัวข้อข่าวประชาสัมพันธ์',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'เนื้อหาข่าวสาร',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'ภาพปกข่าว',
  `club_id` int NULL DEFAULT NULL COMMENT 'FK อ้างถึง clubs.id (NULL = ข่าวสารกลางของมหาวิทยาลัย)',
  `author_id` int NOT NULL COMMENT 'FK อ้างถึง users.id (ผู้เขียนข่าว)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  INDEX `author_id`(`author_id` ASC) USING BTREE,
  CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บข้อมูลข่าวสารประชาสัมพันธ์' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of announcements
-- ----------------------------
INSERT INTO `announcements` (`id`, `title`, `content`, `thumbnail`, `club_id`, `author_id`) VALUES 
(1, 'เปิดรับสมัครสมาชิกชมรม ประจำปีการศึกษา 2569 แล้ววันนี้!', 'ขอเชิญชวนนักศึกษามหาวิทยาลัยราชภัฏหมู่บ้านจอมบึงทุกชั้นปี สมัครเข้าร่วมชมรมต่าง ๆ ประจำภาคเรียนที่ 1/2569 เพื่อพัฒนาทักษะชีวิต ความเป็นผู้นำ และการทำงานร่วมกับผู้อื่น สามารถสมัครได้ผ่านระบบออนไลน์ MCRU Clubs ตั้งแต่วันนี้เป็นต้นไป', NULL, NULL, 1),
(2, 'เตรียมพบกับกิจกรรม Smart Farm Day โชว์นวัตกรรม ESP32', 'ชมรมนวัตกรรม IoT และสมาร์ทฟาร์ม เตรียมจัดแสดงผลงานนวัตกรรมระบบควบคุมฟาร์มเลี้ยงสัตว์และการให้น้ำพืชแบบอัตโนมัติ ในวันที่ 25 มิถุนายนนี้ ณ โถงอาคารวิทยาศาสตร์และเทคโนโลยี ยินดีต้อนรับนักศึกษาทุกคนเข้าเยี่ยมชม', NULL, 1, 3);

-- ----------------------------
-- Table structure for events (Calendar)
-- ----------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสกิจกรรม (Primary Key)',
  `club_id` int NULL DEFAULT NULL COMMENT 'FK อ้างถึง clubs.id (NULL = กิจกรรมกลางของสถาบัน)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อกิจกรรม/กำหนดการ',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT 'รายละเอียดการดำเนินกิจกรรม',
  `event_date` date NOT NULL COMMENT 'วันที่จัดกิจกรรม',
  `start_time` time NULL DEFAULT NULL COMMENT 'เวลาเริ่มต้น',
  `end_time` time NULL DEFAULT NULL COMMENT 'เวลาสิ้นสุด',
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'สถานที่จัดกิจกรรม',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บข้อมูลกิจกรรมและปฏิทินกำหนดการ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of events
-- ----------------------------
INSERT INTO `events` (`id`, `club_id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `location`) VALUES
(1, NULL, 'วันสถาปนามหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง', 'พิธีทำบุญตักบาตรและการแข่งขันกีฬาเชื่อมความสัมพันธ์', '2026-06-22', '08:00:00', '16:00:00', 'หอประชุมใหญ่ MCRU'),
(2, 1, 'อบรมเชิงปฏิบัติการ IoT & ESP32 เบื้องต้น', 'ปูพื้นฐานการเขียนโปรแกรมควบคุมบอร์ด ESP32 และการรับค่าจากเซนเซอร์ความชื้น', '2026-06-25', '13:00:00', '16:30:00', 'ห้องปฏิบัติการคอมพิวเตอร์ อาคาร 3 ชั้น 2');

-- ----------------------------
-- Table structure for gallery (Activity Gallery)
-- ----------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสรูปภาพ (Primary Key)',
  `club_id` int NULL DEFAULT NULL COMMENT 'FK อ้างถึง clubs.id (NULL = กิจกรรมกลาง)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'คำอธิบายรูปภาพ/ชื่อกิจกรรม',
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'พาธรูปภาพกิจกรรม',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บข้อมูลแกลเลอรีภาพกิจกรรม' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gallery
-- ----------------------------
INSERT INTO `gallery` (`id`, `club_id`, `title`, `image_path`) VALUES
(1, 1, 'บรรยากาศการทดสอบระบบรดน้ำอัตโนมัติในโรงเรือน', 'uploads/gallery_demo1.jpg'),
(2, NULL, 'ภาพรวมคณะทำงานนักศึกษาและแอดมินผู้ดูแลระบบ', 'uploads/gallery_demo2.jpg');

-- ----------------------------
-- Table structure for faculties
-- ----------------------------
DROP TABLE IF EXISTS `faculties`;
CREATE TABLE `faculties` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคณะ (Primary Key)',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL UNIQUE COMMENT 'ชื่อคณะ',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลคณะ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of faculties
-- ----------------------------
INSERT INTO `faculties` (`id`, `name`) VALUES
(1, 'คณะวิทยาศาสตร์และเทคโนโลยี'),
(2, 'คณะวิทยาการจัดการ'),
(3, 'คณะครุศาสตร์'),
(4, 'คณะมนุษยศาสตร์และสังคมศาสตร์'),
(5, 'คณะเทคโนโลยีอุตสาหกรรม'),
(6, 'วิทยาลัยมวยไทยศึกษาและการแพทย์แผนไทย');

-- ----------------------------
-- Table structure for majors
-- ----------------------------
DROP TABLE IF EXISTS `majors`;
CREATE TABLE `majors` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสนักศึกษา (Primary Key)',
  `faculty_id` int NOT NULL COMMENT 'FK อ้างอิง faculties.id',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อสาขาวิชา',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_faculty_major`(`faculty_id` ASC, `name` ASC) USING BTREE,
  CONSTRAINT `fk_major_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลสาขาวิชา' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of majors
-- ----------------------------
INSERT INTO `majors` (`faculty_id`, `name`) VALUES
(1, 'เทคโนโลยีสารสนเทศ (IT)'),
(1, 'วิทยาการคอมพิวเตอร์ (CS)'),
(1, 'สาธารณสุขศาสตร์'),
(1, 'วิทยาศาสตร์การกีฬา'),
(1, 'คณิตศาสตร์'),
(1, 'วิทยาศาสตร์สิ่งแวดล้อม'),
(2, 'คอมพิวเตอร์ธุรกิจ'),
(2, 'การจัดการ'),
(2, 'การบัญชี'),
(2, 'การตลาด'),
(2, 'การบริหารทรัพยากรมนุษย์'),
(2, 'นิเทศศาสตร์'),
(3, 'การศึกษาปฐมวัย'),
(3, 'ภาษาไทย'),
(3, 'ภาษาอังกฤษ'),
(3, 'วิทยาศาสตร์ทั่วไป'),
(3, 'คณิตศาสตร์'),
(3, 'พลศึกษา'),
(4, 'รัฐประศาสนศาสตร์'),
(4, 'การพัฒนาชุมชน'),
(4, 'นิติศาสตร์'),
(4, 'ภาษาอังกฤษธุรกิจ'),
(4, 'ศิลปกรรม'),
(5, 'เทคโนโลยีวิศวกรรมไฟฟ้า'),
(5, 'เทคโนโลยีวิศวกรรมเครื่องกล'),
(5, 'เทคโนโลยีอุตสาหการ'),
(5, 'การจัดการโลจิสติกส์'),
(6, 'มวยไทยศึกษา'),
(6, 'การแพทย์แผนไทย');

SET FOREIGN_KEY_CHECKS = 1;
