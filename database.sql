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
-- Records of applications
-- ----------------------------

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
  INDEX `president_id`(`president_id` ASC) USING BTREE,
  CONSTRAINT `clubs_ibfk_1` FOREIGN KEY (`president_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
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
  `role` enum('student','president','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'student' COMMENT 'บทบาทผู้ใช้งานในระบบ',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_id`(`student_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ผู้ใช้งานทุกประเภทในระบบ (นักศึกษา/ประธานชมรม/แอดมิน)' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'admin@mbcr.ac.th', 'admin123', 'ผู้ดูแลระบบ', '-', '-', '-', 'admin');
INSERT INTO `users` VALUES (2, '660001', 'student@mbcr.ac.th', '123456', 'สมชาย ใจดี', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'เทคโนโลยีสารสนเทศ (IT)', '0812345678', 'student');
INSERT INTO `users` VALUES (3, '660002', 'president@mbcr.ac.th', '123456', 'ประธาน ชมรมที่1', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'วิทยาการคอมพิวเตอร์ (CS)', '0899999999', 'president');

SET FOREIGN_KEY_CHECKS = 1;
