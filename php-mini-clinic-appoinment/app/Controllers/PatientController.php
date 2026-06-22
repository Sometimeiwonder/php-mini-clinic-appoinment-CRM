<?php

class PatientController
{
    private function repository(): PatientRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new PatientRepository($pdo);
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

        $patients = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);

        view('patients/index', compact('patients', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['name' => '', 'email' => '', 'phone' => '', 'gender' => 'male'];
        view('patients/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];

        if (!empty($errors)) {
            view('patients/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($data['values']);
            flash_set('success', 'Benh nhan da duoc tao thanh cong.');
            redirect('/patients');
        } catch (DuplicateRecordException $e) {
            $errors['email'] = 'Email nay da ton tai trong he thong.';
            view('patients/create', compact('errors', 'old'));
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            view('errors/500');
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $patient = $this->repository()->findById($id);

        if (!$patient) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $errors = [];
        $old = $patient;
        view('patients/edit', compact('errors', 'old'));
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];
        $old['id'] = $id;

        if (!empty($errors)) {
            view('patients/edit', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->update($id, $data['values']);
            flash_set('success', 'Benh nhan da duoc cap nhat thanh cong.');
            redirect('/patients');
        } catch (DuplicateRecordException $e) {
            $errors['email'] = 'Email nay da ton tai trong he thong.';
            view('patients/edit', compact('errors', 'old'));
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
            redirect('/patients');
        }

        try {
            $this->repository()->delete($id);
            flash_set('success', 'Benh nhan da duoc xoa thanh cong.');
        } catch (Exception $e) {
            error_log($e->getMessage());
            flash_set('error', 'Co loi xay ra khi xoa benh nhan.');
        }
        redirect('/patients');
    }

    private function validate(array $input): array
    {
        $values = [
            'name' => trim($input['name'] ?? ''),
            'email' => trim($input['email'] ?? ''),
            'phone' => trim($input['phone'] ?? ''),
            'gender' => trim($input['gender'] ?? 'male'),
        ];
        $errors = [];
        $allowedGenders = ['male', 'female', 'other'];

        if ($values['name'] === '') {
            $errors['name'] = 'Vui long nhap ho ten.';
        }
        if ($values['email'] === '') {
            $errors['email'] = 'Vui long nhap email.';
        } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email khong dung dinh dang.';
        }
        if (!in_array($values['gender'], $allowedGenders, true)) {
            $errors['gender'] = 'Gioi tinh khong hop le.';
        }

        return ['values' => $values, 'errors' => $errors];
    }
}
