-- Migration: Add status column to users table for deactivation
-- -----------------------------------------------------------
ALTER TABLE `users` 
ADD COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'สถานะบัญชีผู้ใช้งาน (active = ใช้งานได้ปกติ, disabled = ปิดใช้งาน/ระงับบัญชี)' AFTER `role_id`;
