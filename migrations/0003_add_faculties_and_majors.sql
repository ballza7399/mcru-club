-- Migration: 0003_add_faculties_and_majors
-- Description: Create faculties and majors tables. Seed initial academic data.

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Create faculties table
CREATE TABLE IF NOT EXISTS `faculties` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสคณะ (Primary Key)',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL UNIQUE COMMENT 'ชื่อคณะ',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลคณะ' ROW_FORMAT = Dynamic;

-- Seed default faculties
INSERT INTO `faculties` (`id`, `name`) VALUES
(1, 'คณะวิทยาศาสตร์และเทคโนโลยี'),
(2, 'คณะวิทยาการจัดการ'),
(3, 'คณะครุศาสตร์'),
(4, 'คณะมนุษยศาสตร์และสังคมศาสตร์'),
(5, 'คณะเทคโนโลยีอุตสาหกรรม'),
(6, 'วิทยาลัยมวยไทยศึกษาและการแพทย์แผนไทย')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 2. Create majors table
CREATE TABLE IF NOT EXISTS `majors` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสนักศึกษา (Primary Key)',
  `faculty_id` int NOT NULL COMMENT 'FK อ้างอิง faculties.id',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ชื่อสาขาวิชา',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_faculty_major`(`faculty_id` ASC, `name` ASC) USING BTREE,
  CONSTRAINT `fk_major_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'ข้อมูลสาขาวิชา' ROW_FORMAT = Dynamic;

-- Seed default majors
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
(6, 'การแพทย์แผนไทย')
ON DUPLICATE KEY UPDATE name=VALUES(name);

SET FOREIGN_KEY_CHECKS = 1;
