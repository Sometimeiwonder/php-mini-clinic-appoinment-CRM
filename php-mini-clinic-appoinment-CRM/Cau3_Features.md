# Câu 3 - Bài làm thêm khuyến khích

## 1. CSRF Token cho tất cả form POST

**File thực hiện:** `app/Core/helpers.php`, `app/Controllers/PatientController.php`, `app/Controllers/AppointmentController.php`, `app/Controllers/AuthController.php`

**Chi tiết:**
- Hàm `csrf_token()`: Tạo token ngẫu nhiên 64 ký tự, lưu vào `$_SESSION['csrf_token']`
- Hàm `csrf_field()`: Hiển thị hidden field `<input name="_token" value="...">`
- Hàm `verify_csrf()`: So sánh `$_POST['_token']` với `$_SESSION['csrf_token']` bằng `hash_equals()`
- Áp dụng `verify_csrf()` trong tất cả POST methods: `store()`, `update()`, `delete()`, `handleLogin()`, `logout()`
- Tất cả form POST đều có `<?= csrf_field() ?>`

---

## 2. Role Permission: Admin được delete, Staff chỉ create/update

**File thực hiện:** `app/Core/helpers.php`, `app/Controllers/PatientController.php`, `app/Controllers/AppointmentController.php`, `app/Views/patients/index.php`, `app/Views/appointments/index.php`

**Chi tiết:**
- Hàm `require_admin()`: Kiểm tra `$_SESSION['user_role'] === 'admin'`, nếu không phải admin → trả 403
- `PatientController::delete()` và `AppointmentController::delete()`: Dùng `require_admin()` thay vì `require_login()`
- Views: Ẩn nút Delete nếu `$_SESSION['user_role'] !== 'admin'`:
  ```php
  <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
      <button>Delete</button>
  <?php endif; ?>
  ```

---

## 3. Soft Delete bằng deleted_at

**File thực hiện:** `database/schema.sql`, `database/alter_soft_delete.sql`, `app/Repositories/PatientRepository.php`, `app/Repositories/AppointmentRepository.php`

**Chi tiết:**
- Schema: Thêm cột `deleted_at DATETIME NULL` và index `idx_*_deleted_at` cho cả 2 bảng
- Repository `delete()`: Đổi `DELETE FROM` thành `UPDATE ... SET deleted_at = NOW()`
- Repository `countAll()`, `getPaginated()`, `findById()`: Thêm điều kiện `WHERE deleted_at IS NULL`
- Soft delete cho phép khôi phục dữ liệu trong tương lai

---

## 4. Filter theo Status/Date Range

**File thực hiện:** `app/Services/AppointmentService.php`, `app/Repositories/AppointmentRepository.php`, `app/Views/appointments/index.php`

**Chi tiết:**
- Service `getAppointmentList()`: Nhận thêm `$dateFrom`, `$dateTo` từ query params, validate format `YYYY-MM-DD`
- Repository `countAll()` và `getPaginated()`: Thêm điều kiện `appointment_date >= :date_from` và `appointment_date <= :date_to`
- View: Thêm 2 input date "Từ" và "Đến" trong toolbar filter
- Pagination links: Truyền thêm `date_from` và `date_to` để giữ filter khi chuyển trang

---

## 5. Dashboard thống kê

**File thực hiện:** `app/Controllers/DashboardController.php`, `app/Views/dashboard/index.php`

**Chi tiết:**
- Thống kê mới:
  - `total_patients`: Tổng bệnh nhân (không tính soft deleted)
  - `total_appointments`: Tổng lịch hẹn
  - `pending_appointments`: Lịch hẹn chờ xử lý
  - `confirmed_appointments`: Lịch hẹn đã xác nhận
  - `completed_appointments`: Lịch hẹn hoàn thành
  - `cancelled_appointments`: Lịch hẹn đã hủy
  - `today_appointments`: Lịch hẹn hôm nay
  - `new_patients_this_month`: Bệnh nhân mới trong tháng
- Hiển thị 8 stat cards thay vì 4
- Thêm "Recent appointments" list
- Cập nhật "System health" với soft delete và role permission

---

## 6. Logging DB error + Login failed

**File thực hiện:** `app/Core/helpers.php`, `app/Services/AuthService.php`

**Chi tiết:**
- Hàm `log_error()`: Ghi log lỗi DB và exception vào `storage/logs/app.log` với format: `[timestamp] [ip] [method] [uri] - message | Exception: ... | File: ...`
- Hàm `log_login_failed()`: Ghi log email đăng nhập thất bại vào `storage/logs/app.log`
- AuthService `login()`: Gọi `log_login_failed($email)` khi password sai
- Log bao gồm: timestamp, IP, method, URI, message

---

## 7. seed_data.php 200 dòng

**File thực hiện:** `database/seed_data.php`

**Chi tiết:**
- Tạo 200 bệnh nhân với tên ngẫu nhiên từ Vietnamese names
- Tạo 200 lịch hẹn với mã `APT-YYYY-NNNN`, status ngẫu nhiên
- Chạy bằng: `php database/seed_data.php`
- Dùng `INSERT IGNORE` để tránh duplicate khi chạy lại

---

## 8. Cải thiện UI

**File thực hiện:** `app/Views/dashboard/index.php`, `app/Views/appointments/index.php`

**Chi tiết:**
- Dashboard: 8 stat cards với màu sắc khác nhau, recent patients + appointments lists
- Appointments: Thêm filter date range với 2 input date picker
- Tất cả views giữ nguyên kiến trúc MVC và flow kỹ thuật

---

## Danh sách file đã sửa/thêm

| File | Thay đổi |
|------|----------|
| `app/Core/helpers.php` | Thêm `require_admin()`, `log_login_failed()`, thêm `verify_csrf()` vào controllers |
| `app/Controllers/PatientController.php` | Thêm `verify_csrf()`, `require_admin()` cho delete |
| `app/Controllers/AppointmentController.php` | Thêm `verify_csrf()`, `require_admin()` cho delete |
| `app/Controllers/AuthController.php` | Thêm `verify_csrf()` cho login/logout |
| `app/Repositories/PatientRepository.php` | Soft delete: `deleted_at IS NULL`, `UPDATE SET deleted_at` |
| `app/Repositories/AppointmentRepository.php` | Soft delete + date range filter |
| `app/Services/AppointmentService.php` | Thêm `$dateFrom`, `$dateTo` parameters |
| `app/Views/patients/index.php` | ẩn Delete button cho staff |
| `app/Views/appointments/index.php` | ẩn Delete button, thêm date range filter |
| `app/Views/dashboard/index.php` | 8 stat cards, recent appointments |
| `app/Services/AuthService.php` | Gọi `log_login_failed()` |
| `database/schema.sql` | Thêm `deleted_at` columns |
| `database/alter_soft_delete.sql` | ALTER TABLE cho DB hiện tại |
| `database/seed_data.php` | Tạo 200+200 dòng test |
