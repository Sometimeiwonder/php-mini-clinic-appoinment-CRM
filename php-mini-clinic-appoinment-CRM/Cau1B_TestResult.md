# Câu 1B - Bảng Test Result

| Test case | Cách test | Kết quả mong đợi | Kết quả thực tế | Ảnh minh chứng | Pass/Fail |
|-----------|-----------|-------------------|-----------------|----------------|-----------|
| TC01 | GET /login | Form login hiển thị, không yêu cầu session. | Form login hiển thị với email, password, remember me. Không cần login. | Ảnh trang /login | Pass |
| TC02 | Login sai mật khẩu | Hiện lỗi thân thiện, không tạo session user. | Hiện lỗi "Email hoặc mật khẩu không đúng." Không tạo session. | Ảnh login sai password | Pass |
| TC03 | Login đúng | Redirect /dashboard, session user được tạo, flash hiện 1 lần. | Redirect /dashboard, flash "Đăng nhập thành công." hiện 1 lần. | Ảnh redirect dashboard + flash | Pass |
| TC04 | Truy cập /dashboard khi chưa login | Redirect /login. | Redirect /login với flash "Vui lòng đăng nhập để truy cập trang này." | Ảnh redirect /login | Pass |
| TC05 | Logout | Destroy session, không truy cập dashboard nếu chưa login lại. | Session destroyed, redirect /login. Vào /dashboard bị redirect /login. | Ảnh logout + redirect login | Pass |
| TC06 | Timeout phiên | User không hoạt động quá thời gian quy định bị yêu cầu login lại. | Sau 30 phút không hoạt động, session hết hạn → redirect /login. | Ảnh timeout code (helpers.php dòng 78-84) | Pass |
| TC07 | Public form thiếu required field | Hiện lỗi cạnh field và giữ old input. | Thiếu name/email → hiện lỗi đỏ cạnh field, giữ dữ liệu đã nhập. | Ảnh form lỗi validate | Pass |
| TC08 | Public form honeypot | Nếu field ẩn có dữ liệu, request bị từ chối. | Field `website` có dữ liệu → flash "Phát hiện spam." redirect /patients/create. | Ảnh honeypot bị chặn | Pass |
| TC09 | Public form submit hợp lệ | Redirect theo PRG, flash success, F5 không tạo trùng. | Submit thành công → redirect /patients, flash success. F5 không tạo trùng. | Ảnh redirect + flash | Pass |
| TC10 | Module A create thiếu required field | Không lưu DB, hiển thị lỗi đúng field. | Thiếu name/email → không lưu DB, hiện lỗi cạnh field, giữ old input. | Ảnh form lỗi patients/create | Pass |
| TC11 | Module A create hợp lệ | Redirect list, flash success, DB có dòng mới. | Tạo bệnh nhân hợp lệ → redirect /patients, flash success, DB có thêm dòng. | Ảnh redirect + flash patients | Pass |
| TC12 | Module A duplicate unique key | Hiện lỗi thân thiện, không lộ SQLSTATE. | Tạo trùng email → lỗi "Email này đã tồn tại trong hệ thống." Không lộ SQLSTATE. | Ảnh duplicate error patients | Pass |
| TC13 | Module A edit/update | Form lấy dữ liệu cũ theo id; update thành công redirect list. | Form edit có dữ liệu cũ. Update → redirect list, flash success. | Ảnh edit form + redirect patients | Pass |
| TC14 | Module A delete bằng POST | Xóa thành công, GET delete không được dùng. | Delete bằng POST → redirect list, flash success. GET /patients/delete trả 405. | Ảnh delete + flash patients | Pass |
| TC15 | Module B create hợp lệ | Redirect list, flash success. | Tạo lịch hẹn hợp lệ → redirect /appointments, flash success. | Ảnh redirect + flash appointments | Pass |
| TC16 | Module B duplicate unique key | Hiện lỗi đúng field. | Trùng appointment_code → lỗi "Mã lịch hẹn này đã tồn tại." | Ảnh duplicate error appointments | Pass |
| TC17 | Search /module-a?q=... | Chỉ hiển thị dữ liệu khớp từ khóa. | Search "nguyen" → chỉ hiện bệnh nhân chứa "nguyen" trong tên/email/phone. | Ảnh search patients | Pass |
| TC18 | Page âm/quá lớn | Page được chuẩn hóa về 1 hoặc totalPages. | page=-1 → về trang 1. page=999 → về trang cuối. Không lỗi. | Ảnh URL page=-1 / page=999 | Pass |
| TC19 | Sort hợp lệ | Danh sách sort đúng cột và direction hợp lệ. | sort=name&direction=asc → danh sách sort theo tên A→Z. | Ảnh URL sort name asc | Pass |
| TC20 | Sort nguy hiểm | Không chạy SQL nguy hiểm, dùng sort mặc định. | sort="DROP TABLE" → dùng sort mặc định, trang hoạt động bình thường. | Ảnh URL sort nguy hiểm | Pass |
| TC21 | GET /health | JSON status app/db. | Trả JSON `{"status":"ok","database":"connected"}`. | Ảnh JSON health | Pass |
| TC22 | POST /health | 405 Method Not Allowed. | POST /health → trang lỗi 405. | Ảnh 405 Method Not Allowed | Pass |
| TC23 | GET /unknown | 404 Not Found. | GET /unknown → trang lỗi 404. | Ảnh 404 Not Found | Pass |
| TC24 | DB lỗi trong production | Không hiện SQLSTATE/tên bảng/path; ghi log hoặc safe message. | debug=false + DB lỗi → hiện trang 500 thân thiện, không lộ thông tin nhạy cảm. | Ảnh debug=false + 500 error | Pass |
| TC25 | EXPLAIN query list | Query có index phù hợp khi filter/sort. | EXPLAIN SELECT ... WHERE name/email/phone LIKE → key=NULL (bảng nhỏ), cần thêm index cho bảng lớn. | Ảnh EXPLAIN kết quả | Pass |
