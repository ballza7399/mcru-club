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

 Date: 20/06/2026 18:44:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for announcements
-- ----------------------------
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสข่าวสารประชาสัมพันธ์ (Primary Key)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'หัวข้อข่าวสาร',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'เนื้อหาข่าวสารประชาสัมพันธ์ย่อย',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'พาธรูปภาพปกข่าวประชาสัมพันธ์',
  `club_id` int NULL DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นเจ้าของข่าวสาร อ้างอิง clubs.id (NULL หากเป็นข่าวสารกลาง)',
  `author_id` int NOT NULL COMMENT 'รหัสผู้ใช้งานที่เขียนข่าวประชาสัมพันธ์ อ้างอิง users.id',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่สร้างข่าวสาร',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันเวลาที่แก้ไขข่าวสารล่าสุด',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  INDEX `author_id`(`author_id` ASC) USING BTREE,
  CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข่าวประชาสัมพันธ์ของระบบและของชมรม' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of announcements
-- ----------------------------
INSERT INTO `announcements` VALUES (1, 'เปิดรับสมัครสมาชิกชมรม ประจำปีการศึกษา 2569 แล้ววันนี้!', '<p>ขอเชิญชวนนักศึกษามหาวิทยาลัยราชภัฏหมู่บ้านจอมบึงทุกชั้นปี สมัครเข้าร่วมชมรมต่าง ๆ ประจำภาคเรียนที่ 1/2569 เพื่อพัฒนาทักษะชีวิต ความเป็นผู้นำ และการทำงานร่วมกับผู้อื่น สามารถสมัครได้ผ่านระบบออนไลน์ MCRU Clubs ตั้งแต่วันนี้เป็นต้นไป</p>', NULL, NULL, 1, '2026-06-19 22:24:33', '2026-06-20 11:38:45');
INSERT INTO `announcements` VALUES (2, 'เตรียมพบกับกิจกรรม Smart Farm Day โชว์นวัตกรรม ESP32', 'ชมรมนวัตกรรม IoT และสมาร์ทฟาร์ม เตรียมจัดแสดงผลงานนวัตกรรมระบบควบคุมฟาร์มเลี้ยงสัตว์และการให้น้ำพืชแบบอัตโนมัติ ในวันที่ 25 มิถุนายนนี้ ณ โถงอาคารวิทยาศาสตร์และเทคโนโลยี ยินดีต้อนรับนักศึกษาทุกคนเข้าเยี่ยมชม', NULL, 1, 3, '2026-06-19 22:24:33', '2026-06-19 22:24:33');

-- ----------------------------
-- Table structure for applications
-- ----------------------------
DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคำขอสมัคร (Primary Key)',
  `user_id` int NULL DEFAULT NULL COMMENT 'รหัสผู้ยื่นสมัคร อ้างอิง users.id',
  `club_id` int NULL DEFAULT NULL COMMENT 'รหัสชมรมที่ยื่นสมัคร อ้างอิง clubs.id',
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
-- Table structure for club_members
-- ----------------------------
DROP TABLE IF EXISTS `club_members`;
CREATE TABLE `club_members`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิกชมรม (Primary Key)',
  `club_id` int NOT NULL COMMENT 'รหัสชมรม อ้างถึง clubs.id',
  `user_id` int NOT NULL COMMENT 'รหัสผู้ใช้งาน อ้างถึง users.id',
  `role_id` int NULL DEFAULT 5 COMMENT 'รหัสตำแหน่งหน้าที่ภายในชมรม อ้างถึง roles.id',
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ได้รับการอนุมัติเข้าร่วมชมรม',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_club_user`(`club_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `role_id`(`role_id` ASC) USING BTREE,
  CONSTRAINT `club_members_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `club_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `club_members_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'รายชื่อสมาชิกในชมรมและตำแหน่งหน้าที่' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of club_members
-- ----------------------------
INSERT INTO `club_members` VALUES (1, 1, 3, 3, '2026-06-19 22:24:33');

-- ----------------------------
-- Table structure for clubs
-- ----------------------------
DROP TABLE IF EXISTS `clubs`;
CREATE TABLE `clubs`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงชมรม (Primary Key)',
  `club_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อชมรม',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT 'รายละเอียด/คำอธิบายชมรม',
  `club_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'พาธไฟล์หรือ URL รูปภาพโลโก้ชมรม',
  `qr_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'พาธไฟล์หรือ URL รูปภาพคิวอาร์โค้ดสำหรับสมัครเข้าชมรม',
  `max_members` int NULL DEFAULT 50 COMMENT 'จำนวนสมาชิกสูงสุดที่สามารถรับได้ในชมรม',
  `president_id` int NULL DEFAULT NULL COMMENT 'รหัสผู้ใช้ที่เป็นประธานชมรม อ้างอิง users.id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `president_id`(`president_id` ASC) USING BTREE,
  CONSTRAINT `clubs_ibfk_1` FOREIGN KEY (`president_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลชมรมต่างๆ ในระบบ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clubs
-- ----------------------------
INSERT INTO `clubs` VALUES (1, 'ชมรมนวัตกรรม IoT และสมาร์ทฟาร์ม', 'มุ่งเน้นการสร้างสรรค์นวัตกรรม Internet of Things (IoT) การเขียนโปรแกรมควบคุมไมโครคอนโทรลเลอร์ (เช่น ESP32) และการใช้งานเซนเซอร์ต่างๆ เพื่อนำมาประยุกต์ใช้กับระบบสมาร์ทฟาร์มอัตโนมัติ', NULL, NULL, 30, 3);
INSERT INTO `clubs` VALUES (2, 'ชมรมพัฒนาเว็บแอปพลิเคชัน', 'พื้นที่สำหรับผู้ที่สนใจการเขียนโปรแกรมฝั่ง Frontend และ Backend (PHP, MySQL, Bootstrap) เพื่อสร้างระบบและแอปพลิเคชันบนเว็บไซต์ที่ใช้งานได้จริง', NULL, NULL, 40, NULL);
INSERT INTO `clubs` VALUES (3, 'ชมรมช่างซ่อมคอมพิวเตอร์และฮาร์ดแวร์', 'เรียนรู้และฝึกปฏิบัติจริงเกี่ยวกับการประกอบเครื่องคอมพิวเตอร์ การแก้ไขปัญหาฮาร์ดแวร์ (เช่น อาการจอดำ, เสียง Beep Code) การดูแลรักษาปรินเตอร์ และการวางระบบเครือข่าย', NULL, NULL, 20, NULL);
INSERT INTO `clubs` VALUES (4, 'ชมรมคนรักอนิเมะและซีรีส์จีน (Donghua)', 'ศูนย์รวมคนรักศิลปะและวัฒนธรรมความบันเทิงจากจีน พูดคุยแลกเปลี่ยนเรื่องราวอนิเมะจีน (ตงฮวา) ซีรีส์แนวกำลังภายใน นิยายแปล และวัฒนธรรมร่วมสมัย', NULL, NULL, 50, NULL);
INSERT INTO `clubs` VALUES (5, 'ชมรมคนรักสัตว์เลี้ยงขนาดเล็ก', 'แลกเปลี่ยนความรู้ในการดูแลและเพาะเลี้ยงสัตว์เลี้ยงขนาดเล็ก แนะนำวิธีการให้อาหาร การจัดการที่อยู่อาศัย และการสังเกตพฤติกรรมสัตว์อย่างถูกต้อง', NULL, NULL, 25, NULL);
INSERT INTO `clubs` VALUES (6, 'ทดสอบ', 'ทดสอบ', '', '', 50, 4);

-- ----------------------------
-- Table structure for events
-- ----------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสกิจกรรมในปฏิทิน (Primary Key)',
  `club_id` int NULL DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นผู้จัดกิจกรรม อ้างอิง clubs.id (NULL หากเป็นกิจกรรมกลาง)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'หัวข้อกิจกรรมหรือกำหนดการ',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT 'รายละเอียดกิจกรรมเพิ่มเติม',
  `event_date` date NOT NULL COMMENT 'วันที่จัดกิจกรรม',
  `start_time` time NULL DEFAULT NULL COMMENT 'เวลาเริ่มต้นดำเนินกิจกรรม',
  `end_time` time NULL DEFAULT NULL COMMENT 'เวลาสิ้นสุดดำเนินกิจกรรม',
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'สถานที่จัดงานหรือดำเนินกิจกรรม',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่บันทึกข้อมูลกิจกรรม',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ปฏิทินกิจกรรมและกำหนดการต่าง ๆ' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of events
-- ----------------------------
INSERT INTO `events` VALUES (1, NULL, 'วันสถาปนามหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง', 'พิธีทำบุญตักบาตรและการแข่งขันกีฬาเชื่อมความสัมพันธ์', '2026-06-22', '08:00:00', '16:00:00', 'หอประชุมใหญ่ MCRU', '2026-06-19 22:24:33');
INSERT INTO `events` VALUES (2, 1, 'อบรมเชิงปฏิบัติการ IoT & ESP32 เบื้องต้น', 'ปูพื้นฐานการเขียนโปรแกรมควบคุมบอร์ด ESP32 และการรับค่าจากเซนเซอร์ความชื้น', '2026-06-25', '13:00:00', '16:30:00', 'ห้องปฏิบัติการคอมพิวเตอร์ อาคาร 3 ชั้น 2', '2026-06-19 22:24:33');

-- ----------------------------
-- Table structure for faculties
-- ----------------------------
DROP TABLE IF EXISTS `faculties`;
CREATE TABLE `faculties`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคณะ (Primary Key)',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อคณะ',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลคณะย่อยของมหาวิทยาลัย' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of faculties
-- ----------------------------
INSERT INTO `faculties` VALUES (3, 'คณะครุศาสตร์');
INSERT INTO `faculties` VALUES (4, 'คณะมนุษยศาสตร์และสังคมศาสตร์');
INSERT INTO `faculties` VALUES (2, 'คณะวิทยาการจัดการ');
INSERT INTO `faculties` VALUES (1, 'คณะวิทยาศาสตร์และเทคโนโลยี');
INSERT INTO `faculties` VALUES (5, 'คณะเทคโนโลยีอุตสาหกรรม');
INSERT INTO `faculties` VALUES (6, 'วิทยาลัยมวยไทยศึกษาและการแพทย์แผนไทย');

-- ----------------------------
-- Table structure for gallery
-- ----------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสรูปภาพในแกลเลอรี (Primary Key)',
  `club_id` int NULL DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นเจ้าของภาพกิจกรรม อ้างอิง clubs.id (NULL หากเป็นกิจกรรมกลาง)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'คำอธิบายรูปภาพหรือหัวข้อกิจกรรม',
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'พาธไฟล์รูปภาพกิจกรรมในระบบ',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่อัปโหลดรูปภาพ',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'คลังแกลเลอรีรูปภาพกิจกรรมต่าง ๆ' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of gallery
-- ----------------------------
INSERT INTO `gallery` VALUES (1, 1, 'บรรยากาศการทดสอบระบบรดน้ำอัตโนมัติในโรงเรือน', 'uploads/gallery_demo1.jpg', '2026-06-19 22:24:33');
INSERT INTO `gallery` VALUES (2, NULL, 'ภาพรวมคณะทำงานนักศึกษาและแอดมินผู้ดูแลระบบ', 'uploads/gallery_demo2.jpg', '2026-06-19 22:24:33');

-- ----------------------------
-- Table structure for majors
-- ----------------------------
DROP TABLE IF EXISTS `majors`;
CREATE TABLE `majors`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสาขาวิชา (Primary Key)',
  `faculty_id` int NOT NULL COMMENT 'รหัสคณะที่สาขาวิชานี้สังกัด อ้างอิง faculties.id',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อสาขาวิชา',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_faculty_major`(`faculty_id` ASC, `name` ASC) USING BTREE,
  CONSTRAINT `fk_major_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 59 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลสาขาวิชาที่สังกัดในคณะ' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of majors
-- ----------------------------
INSERT INTO `majors` VALUES (5, 1, 'คณิตศาสตร์');
INSERT INTO `majors` VALUES (2, 1, 'วิทยาการคอมพิวเตอร์');
INSERT INTO `majors` VALUES (4, 1, 'วิทยาศาสตร์การกีฬา');
INSERT INTO `majors` VALUES (6, 1, 'วิทยาศาสตร์สิ่งแวดล้อม');
INSERT INTO `majors` VALUES (3, 1, 'สาธารณสุขศาสตร์');
INSERT INTO `majors` VALUES (1, 1, 'เทคโนโลยีสารสนเทศ');
INSERT INTO `majors` VALUES (8, 2, 'การจัดการ');
INSERT INTO `majors` VALUES (10, 2, 'การตลาด');
INSERT INTO `majors` VALUES (11, 2, 'การบริหารทรัพยากรมนุษย์');
INSERT INTO `majors` VALUES (9, 2, 'การบัญชี');
INSERT INTO `majors` VALUES (7, 2, 'คอมพิวเตอร์ธุรกิจ');
INSERT INTO `majors` VALUES (12, 2, 'นิเทศศาสตร์');
INSERT INTO `majors` VALUES (13, 3, 'การศึกษาปฐมวัย');
INSERT INTO `majors` VALUES (17, 3, 'คณิตศาสตร์');
INSERT INTO `majors` VALUES (18, 3, 'พลศึกษา');
INSERT INTO `majors` VALUES (15, 3, 'ภาษาอังกฤษ');
INSERT INTO `majors` VALUES (14, 3, 'ภาษาไทย');
INSERT INTO `majors` VALUES (16, 3, 'วิทยาศาสตร์ทั่วไป');
INSERT INTO `majors` VALUES (20, 4, 'การพัฒนาชุมชน');
INSERT INTO `majors` VALUES (21, 4, 'นิติศาสตร์');
INSERT INTO `majors` VALUES (22, 4, 'ภาษาอังกฤษธุรกิจ');
INSERT INTO `majors` VALUES (19, 4, 'รัฐประศาสนศาสตร์');
INSERT INTO `majors` VALUES (23, 4, 'ศิลปกรรม');
INSERT INTO `majors` VALUES (27, 5, 'การจัดการโลจิสติกส์');
INSERT INTO `majors` VALUES (25, 5, 'เทคโนโลยีวิศวกรรมเครื่องกล');
INSERT INTO `majors` VALUES (24, 5, 'เทคโนโลยีวิศวกรรมไฟฟ้า');
INSERT INTO `majors` VALUES (26, 5, 'เทคโนโลยีอุตสาหการ');
INSERT INTO `majors` VALUES (29, 6, 'การแพทย์แผนไทย');
INSERT INTO `majors` VALUES (28, 6, 'มวยไทยศึกษา');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสประวัติ (Primary Key)',
  `migration_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อไฟล์ SQL ที่เคยรันสำเร็จ',
  `run_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ประมวลผลสำเร็จ',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `migration_name`(`migration_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางประวัติการอัปเกรดระดับฐานข้อมูล' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_init.sql', '2026-06-19 22:27:00');
INSERT INTO `migrations` VALUES (2, '0002_add_roles_and_permissions.sql', '2026-06-19 22:27:15');
INSERT INTO `migrations` VALUES (3, '0003_add_faculties_and_majors.sql', '2026-06-19 22:27:15');
INSERT INTO `migrations` VALUES (4, '0004_add_database_comments.sql', '2026-06-19 22:28:04');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสิทธิ์ (Primary Key)',
  `perm_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'คีย์อ้างอิงสิทธิ์ เช่น manage_clubs, post_news',
  `perm_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อสิทธิ์การใช้งานภาษาไทย',
  `scope` enum('system','club') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'system' COMMENT 'ขอบเขตสิทธิ์การใช้งาน: system=ระดับระบบทั่วไป, club=ระดับเฉพาะชมรม',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `perm_key`(`perm_key` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บสิทธิ์การใช้งานระบบย่อย' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'manage_clubs', 'จัดการชมรมทั้งหมด (สร้าง/แก้ไข/ลบ)', 'system');
INSERT INTO `permissions` VALUES (2, 'manage_users', 'จัดการบทบาทผู้ใช้งานระบบ', 'system');
INSERT INTO `permissions` VALUES (3, 'manage_system_news', 'จัดการข่าวประชาสัมพันธ์มหาวิทยาลัย', 'system');
INSERT INTO `permissions` VALUES (4, 'manage_system_events', 'จัดการกิจกรรมระดับมหาวิทยาลัย', 'system');
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
CREATE TABLE `role_permissions`  (
  `role_id` int NOT NULL COMMENT 'รหัสบทบาท/ตำแหน่ง อ้างอิง roles.id',
  `permission_id` int NOT NULL COMMENT 'รหัสสิทธิ์การใช้งาน อ้างอิง permissions.id',
  PRIMARY KEY (`role_id`, `permission_id`) USING BTREE,
  INDEX `permission_id`(`permission_id` ASC) USING BTREE,
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเชื่อมโยงบทบาทและสิทธิ์การเข้าใช้งาน' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
INSERT INTO `role_permissions` VALUES (1, 1);
INSERT INTO `role_permissions` VALUES (1, 2);
INSERT INTO `role_permissions` VALUES (1, 3);
INSERT INTO `role_permissions` VALUES (1, 4);
INSERT INTO `role_permissions` VALUES (3, 5);
INSERT INTO `role_permissions` VALUES (3, 6);
INSERT INTO `role_permissions` VALUES (3, 7);
INSERT INTO `role_permissions` VALUES (3, 8);
INSERT INTO `role_permissions` VALUES (4, 8);
INSERT INTO `role_permissions` VALUES (3, 9);
INSERT INTO `role_permissions` VALUES (4, 9);
INSERT INTO `role_permissions` VALUES (3, 10);
INSERT INTO `role_permissions` VALUES (4, 10);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสบทบาท/ตำแหน่ง (Primary Key)',
  `role_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'คีย์ระบุบทบาทของระบบ เช่น admin, student, president, officer',
  `role_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อบทบาทหรือตำแหน่งภาษาไทย',
  `scope` enum('system','club') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'system' COMMENT 'ขอบเขตการทำงาน: system=ระดับระบบทั่วไป, club=ระดับเฉพาะชมรม',
  `club_id` int NULL DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นเจ้าของตำแหน่งนี้ อ้างอิง clubs.id (NULL สำหรับตำแหน่งทั่วไป)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_role_key_club`(`role_key` ASC, `club_id` ASC) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บข้อมูลบทบาทและตำแหน่งต่าง ๆ' ROW_FORMAT = DYNAMIC;

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
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้งาน (Primary Key)',
  `student_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'รหัสนักศึกษา ใช้ในการเข้าสู่ระบบ (Unique)',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'ที่อยู่อีเมลติดต่อของผู้ใช้งาน',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'รหัสผ่านสำหรับเข้าสู่ระบบ (Plain Text)',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อและนามสกุลจริงของผู้ใช้งาน',
  `faculty` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'คณะที่ผู้ใช้งานสังกัด',
  `major` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'สาขาวิชาที่ผู้ใช้งานศึกษาอยู่',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'เบอร์โทรศัพท์ติดต่อสำหรับติดต่อกลับ',
  `role_id` int NOT NULL DEFAULT 2 COMMENT 'รหัสบทบาทหลักระดับระบบ อ้างอิง roles.id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_id`(`student_id` ASC) USING BTREE,
  INDEX `fk_users_role`(`role_id` ASC) USING BTREE,
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บข้อมูลผู้ใช้งานทุกประเภทในระบบ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'admin@mbcr.ac.th', '$2y$12$TQTyNjOwwdLIA3UgfwqK3.gCHrEU3TyG4lWv4kibJhnMh8nIGNG2y', 'ผู้ดูแลระบบ', '-', '-', '-', 1);
INSERT INTO `users` VALUES (2, '660001', 'student@mbcr.ac.th', '$2y$12$kKs31ChFeWhRbLZUaNgft.3vUYqeMjmMFqzRxDma0rl7m/ANPBhUK', 'สมชาย ใจดี', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'เทคโนโลยีสารสนเทศ (IT)', '0812345678', 2);
INSERT INTO `users` VALUES (3, '660002', 'president@mbcr.ac.th', '$2y$12$/ZuyIVHsGc0o31moNlF1wOThMugiaRqKYZKDhrqAUvKeKtrQqD2dy', 'ประธาน ชมรมที่1', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'วิทยาการคอมพิวเตอร์ (CS)', '0899999999', 2);
INSERT INTO `users` VALUES (4, '644245001', 'aaaxcvg@gmail.com', '$2y$12$maz8CSEDtaKWjvH34C17feLZ2W7JUKYxaoS7m8ZvZahfHIKnYF5c2', 'กิตติศักดิ์ ศักดิ์เมือง', 'คณะวิทยาศาสตร์และเทคโนโลยี', 'วิทยาการคอมพิวเตอร์ (CS)', '0929458830', 3);

SET FOREIGN_KEY_CHECKS = 1;
