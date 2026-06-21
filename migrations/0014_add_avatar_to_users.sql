-- Migration: 0014_add_avatar_to_users
-- Description: Add avatar column to users table for profile picture feature

ALTER TABLE `users`
ADD COLUMN `avatar` VARCHAR(255) DEFAULT NULL COMMENT 'ชื่อไฟล์หรือพาธของรูปโปรไฟล์ผู้ใช้งาน';
