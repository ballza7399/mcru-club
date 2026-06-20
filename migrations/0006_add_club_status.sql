-- Migration: Add status column to clubs table for approval flow
-- -------------------------------------------------------------
ALTER TABLE `clubs` 
ADD COLUMN `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending' COMMENT 'สถานะของชมรม: pending=รอการอนุมัติ, approved=อนุมัติแล้ว, rejected=ปฏิเสธการจัดตั้ง' AFTER `president_id`;

UPDATE `clubs` SET `status` = 'approved';
