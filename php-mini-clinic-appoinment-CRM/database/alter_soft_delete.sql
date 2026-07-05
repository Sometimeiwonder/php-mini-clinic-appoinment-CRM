ALTER TABLE patients ADD COLUMN deleted_at DATETIME NULL AFTER updated_at;
ALTER TABLE patients ADD INDEX idx_patients_deleted_at (deleted_at);

ALTER TABLE appointments ADD COLUMN deleted_at DATETIME NULL AFTER updated_at;
ALTER TABLE appointments ADD INDEX idx_appointments_deleted_at (deleted_at);
