CREATE DATABASE IF NOT EXISTS db_puskesmas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_puskesmas;

CREATE TABLE IF NOT EXISTS patients (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  medical_record_no VARCHAR(30) NOT NULL UNIQUE,
  nik VARCHAR(16) NULL,
  name VARCHAR(120) NOT NULL,
  gender ENUM('male','female','other','unknown') NOT NULL DEFAULT 'unknown',
  birth_date DATE NULL,
  phone VARCHAR(30) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS referrals (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  referral_no VARCHAR(40) NOT NULL UNIQUE,
  patient_id BIGINT UNSIGNED NOT NULL,
  complaint TEXT NOT NULL,
  destination VARCHAR(100) NOT NULL,
  systolic SMALLINT UNSIGNED NOT NULL,
  diastolic SMALLINT UNSIGNED NOT NULL,
  encounter_date DATETIME NOT NULL,
  status ENUM('draft','sent') NOT NULL DEFAULT 'draft',
  sent_at DATETIME NULL,
  last_response LONGTEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_referral_patient FOREIGN KEY (patient_id) REFERENCES patients(id)
) ENGINE=InnoDB;

INSERT INTO patients (medical_record_no, nik, name, gender, birth_date, phone)
VALUES ('PKM-0001','0000000000000001','Budi Santoso','male','1985-04-12','081200000001')
ON DUPLICATE KEY UPDATE name=VALUES(name);

CREATE TABLE IF NOT EXISTS received_fhir_resources (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  resource_type VARCHAR(40) NOT NULL,
  resource_id VARCHAR(100) NOT NULL,
  resource_json LONGTEXT NOT NULL,
  received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_received_resource (resource_type, resource_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS received_fhir_bundles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  bundle_identifier VARCHAR(100) NULL,
  sender VARCHAR(120) NULL,
  bundle_json LONGTEXT NOT NULL,
  received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
