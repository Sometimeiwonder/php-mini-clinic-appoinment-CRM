<?php

class AppointmentController
{
    private function repository(): AppointmentRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new AppointmentRepository($pdo);
    }

    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';
        $offset = ($page - 1) * $perPage;

        $repo = $this->repository();
        $total = $repo->countAll($q);
        $totalPages = max(1, (int) ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        $appointments = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);

        view('appointments/index', compact('appointments', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['appointment_code' => '', 'patient_name' => '', 'patient_email' => '', 'appointment_date' => '', 'status' => 'pending', 'note' => ''];
        view('appointments/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) {
            view('appointments/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($data['values']);
            flash_set('success', 'Lich hen da duoc tao thanh cong.');
            redirect('/appointments');
        } catch (DuplicateRecordException $e) {
            $errors['appointment_code'] = 'Ma lich hen nay da ton tai.';
            view('appointments/create', compact('errors', 'old'));
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500');
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $appointment = $this->repository()->findById($id);

        if (!$appointment) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $errors = [];
        $old = $appointment;
        view('appointments/edit', compact('errors', 'old'));
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];
        $old['id'] = $id;

        if (!empty($errors)) {
            view('appointments/edit', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->update($id, $data['values']);
            flash_set('success', 'Lich hen da duoc cap nhat thanh cong.');
            redirect('/appointments');
        } catch (DuplicateRecordException $e) {
            $errors['appointment_code'] = 'Ma lich hen nay da ton tai.';
            view('appointments/edit', compact('errors', 'old'));
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500');
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            flash_set('error', 'ID khong hop le.');
            redirect('/appointments');
        }

        try {
            $this->repository()->delete($id);
            flash_set('success', 'Lich hen da duoc xoa thanh cong.');
        } catch (Exception $e) {
            error_log($e->getMessage());
            flash_set('error', 'Co loi xay ra khi xoa lich hen.');
        }
        redirect('/appointments');
    }

    private function validate(array $input): array
    {
        $values = [
            'appointment_code' => trim($input['appointment_code'] ?? ''),
            'patient_name' => trim($input['patient_name'] ?? ''),
            'patient_email' => trim($input['patient_email'] ?? ''),
            'appointment_date' => trim($input['appointment_date'] ?? ''),
            'status' => trim($input['status'] ?? 'pending'),
            'note' => trim($input['note'] ?? ''),
        ];
        $errors = [];
        $allowedStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        if ($values['appointment_code'] === '') {
            $errors['appointment_code'] = 'Vui long nhap ma lich hen.';
        }
        if ($values['patient_name'] === '') {
            $errors['patient_name'] = 'Vui long nhap ten benh nhan.';
        }
        if ($values['patient_email'] !== '' && !filter_var($values['patient_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['patient_email'] = 'Email benh nhan khong dung dinh dang.';
        }
        if ($values['appointment_date'] === '') {
            $errors['appointment_date'] = 'Vui long nhap ngay hen.';
        }
        if (!in_array($values['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Trang thai khong hop le.';
        }

        return ['values' => $values, 'errors' => $errors];
    }
}
