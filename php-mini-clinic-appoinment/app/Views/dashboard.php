<?php ob_start(); ?>
<h1>Dashboard - Mini Clinic Appointment</h1>
<p>He thong quan ly benh nhan va lich hen kham benh.</p>

<div class="dashboard-grid">
    <div class="dashboard-card">
        <h3>Database</h3>
        <p>MySQL + PDO</p>
    </div>
    <div class="dashboard-card">
        <h3>Repository</h3>
        <p>PatientRepository & AppointmentRepository</p>
    </div>
    <div class="dashboard-card">
        <h3>Patient CRUD</h3>
        <p><a href="/patients">List</a> | <a href="/patients/create">Create</a></p>
    </div>
    <div class="dashboard-card">
        <h3>Appointment CRUD</h3>
        <p><a href="/appointments">List</a> | <a href="/appointments/create">Create</a></p>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/layout.php';
