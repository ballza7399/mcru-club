-- Migration: 0004_add_database_comments
-- Description: Add COMMENT to all tables and columns in the database.

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Table: migrations
ALTER TABLE `migrations` COMMENT = 'ตารางประวัติการอัปเกรดระดับฐานข้อมูล';
ALTER TABLE `migrations` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสประวัติ (Primary Key)';
ALTER TABLE `migrations` MODIFY COLUMN `migration_name` varchar(255) NOT NULL COMMENT 'ชื่อไฟล์ SQL ที่เคยรันสำเร็จ';
ALTER TABLE `migrations` MODIFY COLUMN `run_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ประมวลผลสำเร็จ';

-- 2. Table: users
ALTER TABLE `users` COMMENT = 'ตารางเก็บข้อมูลผู้ใช้งานทุกประเภทในระบบ';
ALTER TABLE `users` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้งาน (Primary Key)';
ALTER TABLE `users` MODIFY COLUMN `student_id` varchar(20) NOT NULL COMMENT 'รหัสนักศึกษา ใช้ในการเข้าสู่ระบบ (Unique)';
ALTER TABLE `users` MODIFY COLUMN `email` varchar(100) DEFAULT NULL COMMENT 'ที่อยู่อีเมลติดต่อของผู้ใช้งาน';
ALTER TABLE `users` MODIFY COLUMN `password` varchar(255) NOT NULL COMMENT 'รหัสผ่านสำหรับเข้าสู่ระบบ (Plain Text)';
ALTER TABLE `users` MODIFY COLUMN `name` varchar(100) NOT NULL COMMENT 'ชื่อและนามสกุลจริงของผู้ใช้งาน';
ALTER TABLE `users` MODIFY COLUMN `faculty` varchar(100) DEFAULT NULL COMMENT 'คณะที่ผู้ใช้งานสังกัด';
ALTER TABLE `users` MODIFY COLUMN `major` varchar(100) DEFAULT NULL COMMENT 'สาขาวิชาที่ผู้ใช้งานศึกษาอยู่';
ALTER TABLE `users` MODIFY COLUMN `phone` varchar(20) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์ติดต่อสำหรับติดต่อกลับ';
ALTER TABLE `users` MODIFY COLUMN `role_id` int NOT NULL DEFAULT 2 COMMENT 'รหัสบทบาทหลักระดับระบบ อ้างอิง roles.id';

-- 3. Table: clubs
ALTER TABLE `clubs` COMMENT = 'ข้อมูลชมรมต่างๆ ในระบบ';
ALTER TABLE `clubs` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงชมรม (Primary Key)';
ALTER TABLE `clubs` MODIFY COLUMN `club_name` varchar(100) NOT NULL COMMENT 'ชื่อชมรม';
ALTER TABLE `clubs` MODIFY COLUMN `description` text COMMENT 'รายละเอียด/คำอธิบายชมรม';
ALTER TABLE `clubs` MODIFY COLUMN `club_logo` varchar(255) DEFAULT NULL COMMENT 'พาธไฟล์หรือ URL รูปภาพโลโก้ชมรม';
ALTER TABLE `clubs` MODIFY COLUMN `qr_code` varchar(255) DEFAULT NULL COMMENT 'พาธไฟล์หรือ URL รูปภาพคิวอาร์โค้ดสำหรับสมัครเข้าชมรม';
ALTER TABLE `clubs` MODIFY COLUMN `max_members` int DEFAULT 50 COMMENT 'จำนวนสมาชิกสูงสุดที่สามารถรับได้ในชมรม';
ALTER TABLE `clubs` MODIFY COLUMN `president_id` int DEFAULT NULL COMMENT 'รหัสผู้ใช้ที่เป็นประธานชมรม อ้างอิง users.id';

-- 4. Table: applications
ALTER TABLE `applications` COMMENT = 'คำขอสมัครเข้าชมรมของนักศึกษา';
ALTER TABLE `applications` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคำขอสมัคร (Primary Key)';
ALTER TABLE `applications` MODIFY COLUMN `user_id` int DEFAULT NULL COMMENT 'รหัสผู้ยื่นสมัคร อ้างอิง users.id';
ALTER TABLE `applications` MODIFY COLUMN `club_id` int DEFAULT NULL COMMENT 'รหัสชมรมที่ยื่นสมัคร อ้างอิง clubs.id';
ALTER TABLE `applications` MODIFY COLUMN `status` enum('pending','approved','rejected') DEFAULT 'pending' COMMENT 'สถานะคำขอ: pending=รอพิจารณา, approved=อนุมัติแล้ว, rejected=ปฏิเสธ';

-- 5. Table: roles
ALTER TABLE `roles` COMMENT = 'ตารางเก็บข้อมูลบทบาทและตำแหน่งต่าง ๆ';
ALTER TABLE `roles` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสบทบาท/ตำแหน่ง (Primary Key)';
ALTER TABLE `roles` MODIFY COLUMN `role_key` varchar(50) NOT NULL COMMENT 'คีย์ระบุบทบาทของระบบ เช่น admin, student, president, officer';
ALTER TABLE `roles` MODIFY COLUMN `role_name` varchar(100) NOT NULL COMMENT 'ชื่อบทบาทหรือตำแหน่งภาษาไทย';
ALTER TABLE `roles` MODIFY COLUMN `scope` enum('system','club') NOT NULL DEFAULT 'system' COMMENT 'ขอบเขตการทำงาน: system=ระดับระบบทั่วไป, club=ระดับเฉพาะชมรม';
ALTER TABLE `roles` MODIFY COLUMN `club_id` int NULL DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นเจ้าของตำแหน่งนี้ อ้างอิง clubs.id (NULL สำหรับตำแหน่งทั่วไป)';

-- 6. Table: club_members
ALTER TABLE `club_members` COMMENT = 'รายชื่อสมาชิกในชมรมและตำแหน่งหน้าที่';
ALTER TABLE `club_members` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิกชมรม (Primary Key)';
ALTER TABLE `club_members` MODIFY COLUMN `club_id` int NOT NULL COMMENT 'รหัสชมรม อ้างถึง clubs.id';
ALTER TABLE `club_members` MODIFY COLUMN `user_id` int NOT NULL COMMENT 'รหัสผู้ใช้งาน อ้างถึง users.id';
ALTER TABLE `club_members` MODIFY COLUMN `role_id` int NULL DEFAULT 5 COMMENT 'รหัสตำแหน่งหน้าที่ภายในชมรม อ้างถึง roles.id';
ALTER TABLE `club_members` MODIFY COLUMN `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ได้รับการอนุมัติเข้าร่วมชมรม';

-- 7. Table: permissions
ALTER TABLE `permissions` COMMENT = 'ตารางเก็บสิทธิ์การใช้งานระบบย่อย';
ALTER TABLE `permissions` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสิทธิ์ (Primary Key)';
ALTER TABLE `permissions` MODIFY COLUMN `perm_key` varchar(100) NOT NULL COMMENT 'คีย์อ้างอิงสิทธิ์ เช่น manage_clubs, post_news';
ALTER TABLE `permissions` MODIFY COLUMN `perm_name` varchar(100) NOT NULL COMMENT 'ชื่อสิทธิ์การใช้งานภาษาไทย';
ALTER TABLE `permissions` MODIFY COLUMN `scope` enum('system','club') NOT NULL DEFAULT 'system' COMMENT 'ขอบเขตสิทธิ์การใช้งาน: system=ระดับระบบทั่วไป, club=ระดับเฉพาะชมรม';

-- 8. Table: role_permissions
ALTER TABLE `role_permissions` COMMENT = 'ตารางเชื่อมโยงบทบาทและสิทธิ์การเข้าใช้งาน';
ALTER TABLE `role_permissions` MODIFY COLUMN `role_id` int NOT NULL COMMENT 'รหัสบทบาท/ตำแหน่ง อ้างอิง roles.id';
ALTER TABLE `role_permissions` MODIFY COLUMN `permission_id` int NOT NULL COMMENT 'รหัสสิทธิ์การใช้งาน อ้างอิง permissions.id';

-- 9. Table: announcements
ALTER TABLE `announcements` COMMENT = 'ข่าวประชาสัมพันธ์ของระบบและของชมรม';
ALTER TABLE `announcements` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสข่าวสารประชาสัมพันธ์ (Primary Key)';
ALTER TABLE `announcements` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'หัวข้อข่าวสาร';
ALTER TABLE `announcements` MODIFY COLUMN `content` text NOT NULL COMMENT 'เนื้อหาข่าวสารประชาสัมพันธ์ย่อย';
ALTER TABLE `announcements` MODIFY COLUMN `thumbnail` varchar(255) DEFAULT NULL COMMENT 'พาธรูปภาพปกข่าวประชาสัมพันธ์';
ALTER TABLE `announcements` MODIFY COLUMN `club_id` int DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นเจ้าของข่าวสาร อ้างอิง clubs.id (NULL หากเป็นข่าวสารกลาง)';
ALTER TABLE `announcements` MODIFY COLUMN `author_id` int NOT NULL COMMENT 'รหัสผู้ใช้งานที่เขียนข่าวประชาสัมพันธ์ อ้างอิง users.id';
ALTER TABLE `announcements` MODIFY COLUMN `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่สร้างข่าวสาร';
ALTER TABLE `announcements` MODIFY COLUMN `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันเวลาที่แก้ไขข่าวสารล่าสุด';

-- 10. Table: events
ALTER TABLE `events` COMMENT = 'ปฏิทินกิจกรรมและกำหนดการต่าง ๆ';
ALTER TABLE `events` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสกิจกรรมในปฏิทิน (Primary Key)';
ALTER TABLE `events` MODIFY COLUMN `club_id` int DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นผู้จัดกิจกรรม อ้างอิง clubs.id (NULL หากเป็นกิจกรรมกลาง)';
ALTER TABLE `events` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'หัวข้อกิจกรรมหรือกำหนดการ';
ALTER TABLE `events` MODIFY COLUMN `description` text COMMENT 'รายละเอียดกิจกรรมเพิ่มเติม';
ALTER TABLE `events` MODIFY COLUMN `event_date` date NOT NULL COMMENT 'วันที่จัดกิจกรรม';
ALTER TABLE `events` MODIFY COLUMN `start_time` time DEFAULT NULL COMMENT 'เวลาเริ่มต้นดำเนินกิจกรรม';
ALTER TABLE `events` MODIFY COLUMN `end_time` time DEFAULT NULL COMMENT 'เวลาสิ้นสุดดำเนินกิจกรรม';
ALTER TABLE `events` MODIFY COLUMN `location` varchar(255) DEFAULT NULL COMMENT 'สถานที่จัดงานหรือดำเนินกิจกรรม';
ALTER TABLE `events` MODIFY COLUMN `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่บันทึกข้อมูลกิจกรรม';

-- 11. Table: gallery
ALTER TABLE `gallery` COMMENT = 'คลังแกลเลอรีรูปภาพกิจกรรมต่าง ๆ';
ALTER TABLE `gallery` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสรูปภาพในแกลเลอรี (Primary Key)';
ALTER TABLE `gallery` MODIFY COLUMN `club_id` int DEFAULT NULL COMMENT 'รหัสชมรมที่เป็นเจ้าของภาพกิจกรรม อ้างอิง clubs.id (NULL หากเป็นกิจกรรมกลาง)';
ALTER TABLE `gallery` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'คำอธิบายรูปภาพหรือหัวข้อกิจกรรม';
ALTER TABLE `gallery` MODIFY COLUMN `image_path` varchar(255) NOT NULL COMMENT 'พาธไฟล์รูปภาพกิจกรรมในระบบ';
ALTER TABLE `gallery` MODIFY COLUMN `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่อัปโหลดรูปภาพ';

-- 12. Table: faculties
ALTER TABLE `faculties` COMMENT = 'ข้อมูลคณะย่อยของมหาวิทยาลัย';
ALTER TABLE `faculties` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคณะ (Primary Key)';
ALTER TABLE `faculties` MODIFY COLUMN `name` varchar(150) NOT NULL COMMENT 'ชื่อคณะ';

-- 13. Table: majors
ALTER TABLE `majors` COMMENT = 'ข้อมูลสาขาวิชาที่สังกัดในคณะ';
ALTER TABLE `majors` MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสาขาวิชา (Primary Key)';
ALTER TABLE `majors` MODIFY COLUMN `faculty_id` int NOT NULL COMMENT 'รหัสคณะที่สาขาวิชานี้สังกัด อ้างอิง faculties.id';
ALTER TABLE `majors` MODIFY COLUMN `name` varchar(150) NOT NULL COMMENT 'ชื่อสาขาวิชา';

SET FOREIGN_KEY_CHECKS = 1;
