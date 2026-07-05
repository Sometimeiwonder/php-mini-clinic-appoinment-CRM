<?php

class PatientService
{
    public function __construct(private PatientRepository $repo) {}

    public function getPatientList(array $query): array
    {
        $keyword = trim($query['q'] ?? '');
        $page = max(1, (int)($query['page'] ?? 1));
        $perPage = 10;
        $sort = $query['sort'] ?? 'created_at';
        $direction = $query['direction'] ?? 'desc';
        $totalItems = $this->repo->countAll($keyword);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        return [
            'patients' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $direction),
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'sort' => $sort,
            'direction' => $direction,
        ];
    }

    private function validatePatientData(array $input): array
    {
        $errors = [];
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $gender = trim($input['gender'] ?? 'male');

        if ($name === '') $errors['name'] = 'Tên bệnh nhân không được để trống.';
        if ($email === '') $errors['email'] = 'Email không được để trống.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }
        if (!in_array($gender, ['male', 'female', 'other'], true)) {
            $errors['gender'] = 'Giới tính không hợp lệ.';
        }
        return compact('errors') + ['values' => compact('name', 'email', 'phone', 'gender')];
    }

    public function createPatient(array $input): array
    {
        $validation = $this->validatePatientData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->create($validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return [
                'success' => false,
                'errors' => ['email' => 'Email này đã tồn tại trong hệ thống.']
            ];
        }
    }

    public function updatePatient(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Bệnh nhân không tồn tại.']];
        }
        $validation = $this->validatePatientData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }
        $this->repo->update($id, $validation['values']);
        return ['success' => true, 'errors' => []];
    }

    public function deletePatient(int $id): array
    {
        if ($id <= 0) return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        $this->repo->delete($id);
        return ['success' => true, 'errors' => []];
    }
}
