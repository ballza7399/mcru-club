-- Migration: 0002_add_roles_and_permissions
-- Description: Normalize roles, add permissions, club_members, announcements, events, and gallery tables. Migrate existing users and club presidents data.

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Create roles table
CREATE TABLE IF NOT EXISTS `roles` (
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

-- Seed default roles if not exists
INSERT INTO `roles` (`id`, `role_key`, `role_name`, `scope`, `club_id`) VALUES 
(1, 'admin', 'ผู้ดูแลระบบหลัก', 'system', NULL),
(2, 'student', 'นักศึกษาทั่วไป', 'system', NULL),
(3, 'president', 'ประธานชมรม', 'club', NULL),
(4, 'officer', 'กรรมการชมรม', 'club', NULL),
(5, 'member', 'สมาชิกชมรม', 'club', NULL)
ON DUPLICATE KEY UPDATE role_name=VALUES(role_name);

-- 2. Alter users table to add role_id
ALTER TABLE `users` ADD COLUMN `role_id` int NOT NULL DEFAULT 2 COMMENT 'FK อ้างถึง roles.id (บทบาทหลักในระบบ)' AFTER `phone`;

-- Update users role_id based on old role enum
UPDATE `users` SET `role_id` = 1 WHERE `role` = 'admin';
UPDATE `users` SET `role_id` = 2 WHERE `role` IN ('student', 'president');

-- Add constraint to users.role_id
ALTER TABLE `users` ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- 3. Create club_members table
CREATE TABLE IF NOT EXISTS `club_members` (
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

-- Migrate existing club presidents to club_members
INSERT INTO `club_members` (`club_id`, `user_id`, `role_id`)
SELECT `id`, `president_id`, 3 FROM `clubs` WHERE `president_id` IS NOT NULL
ON DUPLICATE KEY UPDATE role_id = 3;

-- 4. Drop the old ENUM column `role` from `users`
ALTER TABLE `users` DROP COLUMN `role`;

-- 5. Create permissions table
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสิทธิ์ (Primary Key)',
  `perm_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL UNIQUE COMMENT 'คีย์อ้างอิงสิทธิ์ เช่น manage_clubs, post_news',
  `perm_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อสิทธิ์ภาษาไทย',
  `scope` enum('system','club') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'system' COMMENT 'ระดับสิทธิ์: system=ระบบหลัก, club=แอดมินชมรม',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บสิทธิ์การใช้งานระบบ' ROW_FORMAT = Dynamic;

-- Seed default permissions
INSERT INTO `permissions` (`id`, `perm_key`, `perm_name`, `scope`) VALUES
(1, 'manage_clubs', 'จัดการชมรมทั้งหมด (สร้าง/แก้ไข/ลบ)', 'system'),
(2, 'manage_users', 'จัดการบทบาทผู้ใช้งานระบบ', 'system'),
(3, 'manage_system_news', 'จัดการข่าวประชาสัมพันธ์มหาวิทยาลัย', 'system'),
(4, 'manage_system_events', 'จัดการกิจกรรมระดับมหาวิทยาลัย', 'system'),
(5, 'manage_club_info', 'แก้ไขข้อมูลชมรม/โลโก้/QR', 'club'),
(6, 'manage_club_members', 'อนุมัติ/ปฏิเสธและคัดสมาชิกชมรมออก', 'club'),
(7, 'manage_club_roles', 'จัดการตำแหน่งและระดับสิทธิ์ภายในชมรม', 'club'),
(8, 'post_club_news', 'เขียนข่าวประชาสัมพันธ์ชมรม', 'club'),
(9, 'manage_club_events', 'จัดการปฏิทินและกำหนดการชมรม', 'club'),
(10, 'manage_club_gallery', 'อัปโหลดและลบรูปภาพกิจกรรม', 'club')
ON DUPLICATE KEY UPDATE perm_name=VALUES(perm_name), scope=VALUES(scope);

-- 6. Create role_permissions table
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int NOT NULL COMMENT 'FK อ้างถึง roles.id',
  `permission_id` int NOT NULL COMMENT 'FK อ้างถึง permissions.id',
  PRIMARY KEY (`role_id`, `permission_id`) USING BTREE,
  INDEX `permission_id`(`permission_id` ASC) USING BTREE,
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางความสัมพันธ์บทบาทและสิทธิ์' ROW_FORMAT = Dynamic;

-- Link permissions to roles
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), -- Admin System Perms
(3, 5), (3, 6), (3, 7), (3, 8), (3, 9), (3, 10), -- President Club Perms
(4, 8), (4, 9), (4, 10) -- Officer Club Perms
ON DUPLICATE KEY UPDATE role_id = role_id;

-- 7. Create announcements, events, and gallery tables for landing page
CREATE TABLE IF NOT EXISTS `announcements` (
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

INSERT INTO `announcements` (`id`, `title`, `content`, `thumbnail`, `club_id`, `author_id`) VALUES 
(1, 'เปิดรับสมัครสมาชิกชมรม ประจำปีการศึกษา 2569 แล้ววันนี้!', 'ขอเชิญชวนนักศึกษามหาวิทยาลัยราชภัฏหมู่บ้านจอมบึงทุกชั้นปี สมัครเข้าร่วมชมรมต่าง ๆ ประจำภาคเรียนที่ 1/2569 เพื่อพัฒนาทักษะชีวิต ความเป็นผู้นำ และการทำงานร่วมกับผู้อื่น สามารถสมัครได้ผ่านระบบออนไลน์ MCRU Clubs ตั้งแต่วันนี้เป็นต้นไป', NULL, NULL, 1),
(2, 'เตรียมพบกับกิจกรรม Smart Farm Day โชว์นวัตกรรม ESP32', 'ชมรมนวัตกรรม IoT และสมาร์ทฟาร์ม เตรียมจัดแสดงผลงานนวัตกรรมระบบควบคุมฟาร์มเลี้ยงสัตว์และการให้น้ำพืชแบบอัตโนมัติ ในวันที่ 25 มิถุนายนนี้ ณ โถงอาคารวิทยาศาสตร์และเทคโนโลยี ยินดีต้อนรับนักศึกษาทุกคนเข้าเยี่ยมชม', NULL, 1, 3)
ON DUPLICATE KEY UPDATE title = title;

CREATE TABLE IF NOT EXISTS `events` (
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

INSERT INTO `events` (`id`, `club_id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `location`) VALUES
(1, NULL, 'วันสถาปนามหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง', 'พิธีทำบุญตักบาตรและการแข่งขันกีฬาเชื่อมความสัมพันธ์', '2026-06-22', '08:00:00', '16:00:00', 'หอประชุมใหญ่ MCRU'),
(2, 1, 'อบรมเชิงปฏิบัติการ IoT & ESP32 เบื้องต้น', 'ปูพื้นฐานการเขียนโปรแกรมควบคุมบอร์ด ESP32 และการรับค่าจากเซนเซอร์ความชื้น', '2026-06-25', '13:00:00', '16:30:00', 'ห้องปฏิบัติการคอมพิวเตอร์ อาคาร 3 ชั้น 2')
ON DUPLICATE KEY UPDATE title = title;

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสรูปภาพ (Primary Key)',
  `club_id` int NULL DEFAULT NULL COMMENT 'FK อ้างถึง clubs.id (NULL = กิจกรรมกลาง)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'คำอธิบายรูปภาพ/ชื่อกิจกรรม',
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'พาธรูปภาพกิจกรรม',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `club_id`(`club_id` ASC) USING BTREE,
  CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ตารางเก็บข้อมูลแกลเลอรีภาพกิจกรรม' ROW_FORMAT = Dynamic;

INSERT INTO `gallery` (`id`, `club_id`, `title`, `image_path`) VALUES
(1, 1, 'บรรยากาศการทดสอบระบบรดน้ำอัตโนมัติในโรงเรือน', 'uploads/gallery_demo1.jpg'),
(2, NULL, 'ภาพรวมคณะทำงานนักศึกษาและแอดมินผู้ดูแลระบบ', 'uploads/gallery_demo2.jpg')
ON DUPLICATE KEY UPDATE title = title;

SET FOREIGN_KEY_CHECKS = 1;
