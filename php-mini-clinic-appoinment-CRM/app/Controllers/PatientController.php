<?php

class PatientController
{
    private PatientService $service;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = Database::connect($config);
        $this->service = new PatientService(new PatientRepository($pdo));
    }

    public function index(): void
    {
        require_login();
        $data = $this->service->getPatientList($_GET);
        render('patients/index', ['title' => 'Patient Management'] + $data);
    }

    public function create(): void
    {
        require_login();
        render('patients/create', ['title' => 'Create Patient', 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        require_login();
        verify_csrf();

        if (!empty($_POST['website'])) {
            flash('error', 'Phát hiện spam. Vui lòng thử lại.');
            redirect('/patients/create');
        }

        check_rate_limit(5, '/patients/create');

        $result = $this->service->createPatient($_POST);
        if (!$result['success']) {
            render('patients/create', [
                'title' => 'Create Patient',
                'errors' => $result['errors'],
                'old' => $_POST,
            ]);
            return;
        }
        flash('success', 'Bệnh nhân đã được tạo thành công.');
        redirect('/patients');
    }

    public function edit(): void
    {
        require_login();
        $id = (int) ($_GET['id'] ?? 0);
        $repo = new PatientRepository(Database::connect(require __DIR__ . '/../../config/database.php'));
        $patient = $repo->findById($id);

        if (!$patient) {
            http_response_code(404);
            render('errors/404', ['title' => '404 Not Found']);
            return;
        }

        render('patients/edit', [
            'title' => 'Edit Patient',
            'errors' => [],
            'old' => $patient,
        ]);
    }

    public function update(): void
    {
        require_login();
        verify_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->service->updatePatient($id, $_POST);
        if (!$result['success']) {
            render('patients/edit', [
                'title' => 'Edit Patient',
                'errors' => $result['errors'],
                'old' => array_merge($_POST, ['id' => $id]),
            ]);
            return;
        }
        flash('success', 'Bệnh nhân đã được cập nhật thành công.');
        redirect('/patients');
    }

    public function delete(): void
    {
        require_admin();
        verify_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->service->deletePatient($id);
        if ($result['success']) {
            flash('success', 'Bệnh nhân đã được xóa thành công.');
        } else {
            flash('error', $result['errors']['general'] ?? 'Có lỗi xảy ra khi xóa.');
        }
        redirect('/patients');
    }
}
