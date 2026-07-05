USE web_php_clinic;

-- Add remember_me columns if not exist
SET @exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'web_php_clinic'
    AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'remember_token'
);

SET @sql = IF(@exists = 0,
    'ALTER TABLE users ADD COLUMN remember_token VARCHAR(64) NULL AFTER status, ADD COLUMN remember_expiry DATETIME NULL AFTER remember_token',
    'SELECT "Columns already exist"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
