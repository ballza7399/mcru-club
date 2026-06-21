-- Migration: Add club establishment flow and student development staff role
-- ----------------------------------------------------------------------

ALTER TABLE `clubs` 
MODIFY COLUMN `status` ENUM('pending', 'approved', 'rejected', 'correcting') NOT NULL DEFAULT 'pending' COMMENT 'สถานะการอนุมัติชมรม: pending=รออนุมัติ, approved=อนุมัติแล้ว, rejected=ปฏิเสธการจัดตั้ง, correcting=ส่งกลับแก้ไขรายละเอียด';

ALTER TABLE `clubs`
ADD COLUMN `objectives` text NULL COMMENT 'วัตถุประสงค์ของการก่อตั้งชมรม เก็บในรูปแบบ JSON' AFTER `description`,
ADD COLUMN `advisor_name` varchar(100) NULL COMMENT 'ชื่อ-นามสกุลของอาจารย์ที่ปรึกษาชมรม' AFTER `objectives`,
ADD COLUMN `establishment_document` varchar(255) NULL COMMENT 'พาธไฟล์เอกสารเสนอขอก่อตั้งชมรม (.doc, .docx, .pdf)' AFTER `club_logo`,
ADD COLUMN `rejection_reason` text NULL COMMENT 'รายละเอียดการแก้ไขหรือเหตุผลกรณีส่งกลับแก้ไข/ปฏิเสธ' AFTER `status`,
ADD COLUMN `member_verification_status` ENUM('not_submitted', 'pending', 'approved', 'correcting') NOT NULL DEFAULT 'not_submitted' COMMENT 'สถานะการตรวจรายชื่อสมาชิก: not_submitted=ยังไม่ส่ง, pending=รอตรวจ, approved=ผ่านการตรวจ, correcting=ส่งกลับแก้ไขรายชื่อ' AFTER `rejection_reason`,
ADD COLUMN `member_verification_comment` text NULL COMMENT 'รายละเอียดการแก้ไขหรือหมายเหตุสำหรับรายชื่อสมาชิก' AFTER `member_verification_status`;

-- Add Student Development Staff Role
INSERT INTO `roles` (`role_key`, `role_name`, `scope`, `club_id`) VALUES ('staff', 'เจ้าหน้าที่กองพัฒนานักศึกษา', 'system', NULL);

-- Grant manage_clubs and other news permissions to staff
INSERT INTO `role_permissions` (`role_id`, `permission_id`) 
SELECT id, 1 FROM roles WHERE role_key = 'staff';

INSERT INTO `role_permissions` (`role_id`, `permission_id`) 
SELECT id, 3 FROM roles WHERE role_key = 'staff';

INSERT INTO `role_permissions` (`role_id`, `permission_id`) 
SELECT id, 4 FROM roles WHERE role_key = 'staff';
