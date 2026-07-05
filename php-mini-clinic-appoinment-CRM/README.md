# Mini Clinic Appointment CRM

Ứng dụng quản lý lịch hẹn khám bệnh với kiến trúc MVC, bảo mật theo Lab06 Final.

## Cách chạy

```bash
php -S localhost:8000 -t public
```

## Tạo database

```bash
mysql -u root < database/schema.sql
mysql -u root < database/seed.sql
```

## Tài khoản demo

| Email | Password | Role |
|-------|----------|------|
| admin@clinic.com | 123456 | admin |
| reception@clinic.com | 123456 | staff |

## Danh sách route

| Method | URL | Mô tả |
|--------|-----|-------|
| GET | /login | Form login |
| POST | /login | Xử lý login |
| POST | /logout | Đăng xuất |
| GET | /dashboard | Trang tổng quan |
| GET | /patients | Danh sách bệnh nhân |
| GET | /patients/create | Form thêm bệnh nhân |
| POST | /patients/store | Tạo bệnh nhân |
| GET | /patients/edit?id=1 | Form sửa bệnh nhân |
| POST | /patients/update | Cập nhật bệnh nhân |
| POST | /patients/delete | Xóa bệnh nhân |
| GET | /appointments | Danh sách lịch hẹn |
| GET | /appointments/create | Form thêm lịch hẹn |
| POST | /appointments/store | Tạo lịch hẹn |
| GET | /appointments/edit?id=1 | Form sửa lịch hẹn |
| POST | /appointments/update | Cập nhật lịch hẹn |
| POST | /appointments/delete | Xóa lịch hẹn |
| GET | /health | JSON health check |

## Cấu trúc project

```
php-mini-clinic-appoinment-CRM/
├── public/
│   └── index.php
├── config/
│   ├── app.php
│   └── database.php
├── app/
│   ├── Core/
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── helpers.php
│   │   └── DuplicateRecordException.php
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── PatientController.php
│   │   └── AppointmentController.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── PatientService.php
│   │   └── AppointmentService.php
│   ├── Repositories/
│   │   ├── UserRepository.php
│   │   ├── PatientRepository.php
│   │   └── AppointmentRepository.php
│   └── Views/
│       ├── layouts/main.php
│       ├── partials/nav.php
│       ├── partials/flash.php
│       ├── auth/login.php
│       ├── dashboard/index.php
│       ├── patients/index.php, create.php, edit.php
│       ├── appointments/index.php, create.php, edit.php
│       └── errors/403.php, 404.php, 405.php, 500.php
├── database/
│   ├── schema.sql
│   └── seed.sql
└── storage/logs/
```

## Kỹ thuật bảo mật

- Front Controller + Router
- Prepared statements (PDO)
- PRG Pattern
- Honeypot + Rate limit
- Session regenerate ID
- Cookie flags (HttpOnly, SameSite)
- Escape output bằng `e()`
- Sort whitelist
- Duplicate key handling
- Production safe error
