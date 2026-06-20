-- Add comments to notifications table and its columns
ALTER TABLE `notifications` 
  COMMENT = 'ตารางเก็บข้อมูลการแจ้งเตือนของนักศึกษาและผู้ดูแลระบบ';

ALTER TABLE `notifications`
  MODIFY COLUMN `id` INT AUTO_INCREMENT COMMENT 'รหัสไอดีการแจ้งเตือน',
  MODIFY COLUMN `user_id` INT NOT NULL COMMENT 'รหัสอ้างอิงผู้ใช้งานที่ได้รับการแจ้งเตือน',
  MODIFY COLUMN `title` VARCHAR(255) NOT NULL COMMENT 'หัวข้อการแจ้งเตือน',
  MODIFY COLUMN `message` TEXT NOT NULL COMMENT 'รายละเอียดเนื้อหาการแจ้งเตือน',
  MODIFY COLUMN `is_read` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'สถานะการอ่าน (0 = ยังไม่ได้อ่าน, 1 = อ่านแล้ว)',
  MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่เกิดการแจ้งเตือน';
