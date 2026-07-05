<?php

class DashboardController
{
    public function index(): void
    {
        require_login();
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = Database::connect($config);

        $stats = [];
        $stats['total_patients'] = (int) $pdo->query("SELECT COUNT(*) FROM patients WHERE deleted_at IS NULL")->fetchColumn();
        $stats['total_appointments'] = (int) $pdo->query("SELECT COUNT(*) FROM appointments WHERE deleted_at IS NULL")->fetchColumn();
        $stats['pending_appointments'] = (int) $pdo->query("SELECT COUNT(*) FROM appointments WHERE status='pending' AND deleted_at IS NULL")->fetchColumn();
        $stats['confirmed_appointments'] = (int) $pdo->query("SELECT COUNT(*) FROM appointments WHERE status='confirmed' AND deleted_at IS NULL")->fetchColumn();
        $stats['completed_appointments'] = (int) $pdo->query("SELECT COUNT(*) FROM appointments WHERE status='completed' AND deleted_at IS NULL")->fetchColumn();
        $stats['cancelled_appointments'] = (int) $pdo->query("SELECT COUNT(*) FROM appointments WHERE status='cancelled' AND deleted_at IS NULL")->fetchColumn();
        $stats['today_appointments'] = (int) $pdo->query("SELECT COUNT(*) FROM appointments WHERE appointment_date = CURDATE() AND deleted_at IS NULL")->fetchColumn();
        $stats['new_patients_this_month'] = (int) $pdo->query("SELECT COUNT(*) FROM patients WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND deleted_at IS NULL")->fetchColumn();

        $recentPatients = $pdo->query("SELECT id, name, email, gender, created_at FROM patients WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $recentAppointments = $pdo->query("SELECT id, appointment_code, patient_name, appointment_date, status, created_at FROM appointments WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5")->fetchAll();

        render('dashboard/index', [
            'title' => 'Dashboard',
            'user_name' => $_SESSION['user_name'] ?? 'User',
            'stats' => $stats,
            'recentPatients' => $recentPatients,
            'recentAppointments' => $recentAppointments,
        ]);
    }
}
