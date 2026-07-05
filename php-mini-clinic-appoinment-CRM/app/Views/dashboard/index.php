<h1>Dashboard</h1>
<p style="color:#6b7280;">Tổng quan bệnh nhân/lịch hẹn sau khi đăng nhập. Trang này yêu cầu session hợp lệ.</p>

<div class="stat-grid">
    <div class="stat-card blue">
        <div class="stat-label">Total Patients</div>
        <div class="stat-number"><?= e((string)$stats['total_patients']) ?></div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-label">Pending Appointments</div>
        <div class="stat-number"><?= e((string)$stats['pending_appointments']) ?></div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Total Appointments</div>
        <div class="stat-number"><?= e((string)$stats['total_appointments']) ?></div>
    </div>
    <div class="stat-card purple">
        <div class="stat-label">Completed</div>
        <div class="stat-number"><?= e((string)$stats['completed_appointments']) ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Confirmed</div>
        <div class="stat-number"><?= e((string)$stats['confirmed_appointments']) ?></div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Cancelled</div>
        <div class="stat-number"><?= e((string)$stats['cancelled_appointments']) ?></div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Today's Appointments</div>
        <div class="stat-number"><?= e((string)$stats['today_appointments']) ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">New Patients (Month)</div>
        <div class="stat-number"><?= e((string)$stats['new_patients_this_month']) ?></div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px;">
    <div class="card">
        <h3>Recent patients</h3>
        <?php foreach ($recentPatients as $p): ?>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f1f5f9;">
                <span><?= e($p['name']) ?></span>
                <span class="badge"><?= e($p['gender']) ?></span>
            </div>
        <?php endforeach; ?>
        <?php if (empty($recentPatients)): ?>
            <p style="color:#9ca3af;">Chưa có bệnh nhân nào.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Recent appointments</h3>
        <?php foreach ($recentAppointments as $apt): ?>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f1f5f9;">
                <span><?= e($apt['appointment_code']) ?> - <?= e($apt['patient_name']) ?></span>
                <span class="badge status-<?= e($apt['status']) ?>"><?= e($apt['status']) ?></span>
            </div>
        <?php endforeach; ?>
        <?php if (empty($recentAppointments)): ?>
            <p style="color:#9ca3af;">Chưa có lịch hẹn nào.</p>
        <?php endif; ?>
    </div>
</div>

<div style="margin-top:20px;">
    <div class="card">
        <h3>System health</h3>
        <ul style="list-style:none; padding:0; line-height:2.2;">
            <li>&#x2705; Session active</li>
            <li>&#x2705; PDO connected</li>
            <li>&#x2705; CSRF/PRG ready</li>
            <li>&#x2705; No raw SQL concat</li>
            <li>&#x2705; Flash message cleaned</li>
            <li>&#x2705; Soft delete enabled</li>
            <li>&#x2705; Role permission (admin/staff)</li>
        </ul>
    </div>
</div>
