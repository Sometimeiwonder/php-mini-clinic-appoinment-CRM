<nav class="navbar">
    <strong>Mini Clinic</strong>
    <a href="/dashboard">Dashboard</a>
    <a href="/patients">Patients</a>
    <a href="/patients/create">Create Patient</a>
    <a href="/appointments">Appointments</a>
    <a href="/appointments/create">Create Appointment</a>
    <a href="/health">Health</a>
    <?php if (!empty($_SESSION['user_id'])): ?>
        <span style="margin-left:auto; color:#94a3b8;"><?= e($_SESSION['user_name'] ?? '') ?></span>
        <form method="post" action="/logout" style="display:inline;">
            <?= csrf_field() ?>
            <button type="submit" class="link" style="color:#f87171; background:none; border:none; cursor:pointer;">Logout</button>
        </form>
    <?php endif; ?>
</nav>
