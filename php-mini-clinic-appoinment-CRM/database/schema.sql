CREATE DATABASE IF NOT EXISTS web_php_clinic
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE web_php_clinic;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  remember_token VARCHAR(64) NULL,
  remember_expiry DATETIME NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(30),
  gender ENUM('male', 'female', 'other') NOT NULL DEFAULT 'other',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  UNIQUE KEY unique_patient_email (email),
  INDEX idx_patients_created_at (created_at),
  INDEX idx_patients_name (name),
  INDEX idx_patients_phone (phone),
  INDEX idx_patients_deleted_at (deleted_at)
);

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_code VARCHAR(50) NOT NULL,
  patient_name VARCHAR(100) NOT NULL,
  patient_email VARCHAR(150),
  appointment_date DATE NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  UNIQUE KEY unique_appointment_code (appointment_code),
  INDEX idx_appointments_created_at (created_at),
  INDEX idx_appointments_status_created_at (status, created_at),
  INDEX idx_appointments_patient_email (patient_email),
  INDEX idx_appointments_appointment_date (appointment_date),
  INDEX idx_appointments_deleted_at (deleted_at)
);
