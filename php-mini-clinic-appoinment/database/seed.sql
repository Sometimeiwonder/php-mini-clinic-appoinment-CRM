USE web_php_clinic;

INSERT INTO users (name, email, password_hash, role)
VALUES
('Admin User', 'admin@clinic.com', '$2y$10$examplehashadmin', 'admin'),
('Reception Staff', 'reception@clinic.com', '$2y$10$examplehashstaff', 'staff');

INSERT INTO patients (name, email, phone, gender)
VALUES
('Nguyen Van A', 'nguyena@example.com', '0901000001', 'male'),
('Tran Thi B', 'tranthib@example.com', '0901000002', 'female'),
('Le Van C', 'levanc@example.com', '0901000003', 'male'),
('Pham Thi D', 'phamthid@example.com', '0901000004', 'female'),
('Hoang Van E', 'hoangvane@example.com', '0901000005', 'male'),
('Vo Thi F', 'vothif@example.com', '0901000006', 'female'),
('Nguyen Van G', 'nguyeng@example.com', '0901000007', 'male'),
('Tran Thi H', 'trantranh@example.com', '0901000008', 'female'),
('Le Van I', 'levani@example.com', '0901000009', 'male'),
('Pham Thi J', 'phamthij@example.com', '0901000010', 'female'),
('Hoang Van K', 'hoangvank@example.com', '0901000011', 'male'),
('Vo Thi L', 'vothil@example.com', '0901000012', 'female'),
('Nguyen Van M', 'nguyenm@example.com', '0901000013', 'male'),
('Tran Thi N', 'tranthin@example.com', '0901000014', 'female'),
('Le Van O', 'levano@example.com', '0901000015', 'male'),
('Pham Thi P', 'phamthip@example.com', '0901000016', 'female'),
('Hoang Van Q', 'hoangvanq@example.com', '0901000017', 'male'),
('Vo Thi R', 'vothir@example.com', '0901000018', 'female'),
('Nguyen Van S', 'nguyens@example.com', '0901000019', 'male'),
('Tran Thi T', 'tranthit@example.com', '0901000020', 'female');

INSERT INTO appointments (appointment_code, patient_name, patient_email, appointment_date, status, note)
VALUES
('APT-2026-0001', 'Nguyen Van A', 'nguyena@example.com', '2026-06-25', 'pending', 'Kham tong quat'),
('APT-2026-0002', 'Tran Thi B', 'tranthib@example.com', '2026-06-25', 'confirmed', 'Tai kham'),
('APT-2026-0003', 'Le Van C', 'levanc@example.com', '2026-06-26', 'pending', 'Kham da lieu'),
('APT-2026-0004', 'Pham Thi D', 'phamthid@example.com', '2026-06-26', 'cancelled', 'Huy lich'),
('APT-2026-0005', 'Hoang Van E', 'hoangvane@example.com', '2026-06-27', 'confirmed', 'Kham mat'),
('APT-2026-0006', 'Vo Thi F', 'vothif@example.com', '2026-06-27', 'pending', 'Kham rang'),
('APT-2026-0007', 'Nguyen Van G', 'nguyeng@example.com', '2026-06-28', 'completed', 'Kham than kinh'),
('APT-2026-0008', 'Tran Thi H', 'trantranh@example.com', '2026-06-28', 'pending', 'Kham noi tong quat'),
('APT-2026-0009', 'Le Van I', 'levani@example.com', '2026-06-29', 'confirmed', 'Kham co xuong khop'),
('APT-2026-0010', 'Pham Thi J', 'phamthij@example.com', '2026-06-29', 'pending', 'Kham tai mui hong'),
('APT-2026-0011', 'Hoang Van K', 'hoangvank@example.com', '2026-06-30', 'pending', 'Kham mat'),
('APT-2026-0012', 'Vo Thi L', 'vothil@example.com', '2026-06-30', 'confirmed', 'Kham da lieu'),
('APT-2026-0013', 'Nguyen Van M', 'nguyenm@example.com', '2026-07-01', 'pending', 'Kham rang'),
('APT-2026-0014', 'Tran Thi N', 'tranthin@example.com', '2026-07-01', 'completed', 'Tai kham'),
('APT-2026-0015', 'Le Van O', 'levano@example.com', '2026-07-02', 'pending', 'Kham noi tong quat'),
('APT-2026-0016', 'Pham Thi P', 'phamthip@example.com', '2026-07-02', 'confirmed', 'Kham than kinh'),
('APT-2026-0017', 'Hoang Van Q', 'hoangvanq@example.com', '2026-07-03', 'pending', 'Kham co xuong khop');
