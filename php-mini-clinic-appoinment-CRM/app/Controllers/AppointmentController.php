<?php

class AppointmentController
{
    private AppointmentService $service;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = Database::connect($config);
        $this->service = new AppointmentService(new AppointmentRepository($pdo));
    }

    public function index(): void
    {
        require_login();
        $data = $this->service->getAppointmentList($_GET);
        render('appointments/index', ['title' => 'Appointment Management'] + $data);
    }

    public function create(): void
    {
        require_login();
        render('appointments/create', ['title' => 'Create Appointment', 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        require_login();
        verify_csrf();

        if (!empty($_POST['website'])) {
            flash('error', 'Phát hiện spam. Vui lòng thử lại.');
            redirect('/appointments/create');
        }

        check_rate_limit(5, '/appointments/create');

        $result = $this->service->createAppointment($_POST);
        if (!$result['success']) {
            render('appointments/create', [
                'title' => 'Create Appointment',
                'errors' => $result['errors'],
                'old' => $_POST,
            ]);
            return;
        }
        flash('success', 'Lịch hẹn đã được tạo thành công.');
        redirect('/appointments');
    }

    public function edit(): void
    {
        require_login();
        $id = (int) ($_GET['id'] ?? 0);
        $repo = new AppointmentRepository(Database::connect(require __DIR__ . '/../../config/database.php'));
        $appointment = $repo->findById($id);

        if (!$appointment) {
            http_response_code(404);
            render('errors/404', ['title' => '404 Not Found']);
            return;
        }

        render('appointments/edit', [
            'title' => 'Edit Appointment',
            'errors' => [],
            'old' => $appointment,
        ]);
    }

    public function update(): void
    {
        require_login();
        verify_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->service->updateAppointment($id, $_POST);
        if (!$result['success']) {
            render('appointments/edit', [
                'title' => 'Edit Appointment',
                'errors' => $result['errors'],
                'old' => array_merge($_POST, ['id' => $id]),
            ]);
            return;
        }
        flash('success', 'Lịch hẹn đã được cập nhật thành công.');
        redirect('/appointments');
    }

    public function delete(): void
    {
        require_admin();
        verify_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->service->deleteAppointment($id);
        if ($result['success']) {
            flash('success', 'Lịch hẹn đã được xóa thành công.');
        } else {
            flash('error', $result['errors']['general'] ?? 'Có lỗi xảy ra khi xóa.');
        }
        redirect('/appointments');
    }
}
