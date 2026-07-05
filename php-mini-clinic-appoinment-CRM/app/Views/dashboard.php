<h1>Dashboard - Phòng khám Mini</h1>
<p>Xin chào, <?= e($user_name ?? 'User') ?>! Hệ thống quản lý bệnh nhân và lịch hẹn khám bệnh.</p>

<div class="dashboard-grid">
    <div class="dashboard-card">
        <h3>Quản lý Bệnh nhân</h3>
        <p><a href="/patients">Danh sách</a> | <a href="/patients/create">Tạo mới</a></p>
    </div>
    <div class="dashboard-card">
        <h3>Quản lý Lịch hẹn</h3>
        <p><a href="/appointments">Danh sách</a> | <a href="/appointments/create">Tạo mới</a></p>
    </div>
    <div class="dashboard-card">
        <h3>Hệ thống</h3>
        <p><a href="/health">Kiểm tra sức khỏe</a></p>
    </div>
</div>
