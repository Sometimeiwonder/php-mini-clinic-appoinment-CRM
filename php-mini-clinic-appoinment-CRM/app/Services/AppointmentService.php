<?php

class AppointmentService
{
    public function __construct(private AppointmentRepository $repo) {}

    public function getAppointmentList(array $query): array
    {
        $keyword = trim($query['q'] ?? '');
        $status = trim($query['status'] ?? '');
        $dateFrom = trim($query['date_from'] ?? '');
        $dateTo = trim($query['date_to'] ?? '');
        $page = max(1, (int)($query['page'] ?? 1));
        $perPage = 10;
        $sort = $query['sort'] ?? 'created_at';
        $direction = $query['direction'] ?? 'desc';

        $allowedStatuses = ['pending', 'confirmed', 'completed', 'cancelled', ''];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        if ($dateFrom !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
            $dateFrom = '';
        }
        if ($dateTo !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
            $dateTo = '';
        }

        $totalItems = $this->repo->countAll($keyword, $status, $dateFrom, $dateTo);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        return [
            'appointments' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $direction, $status, $dateFrom, $dateTo),
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'sort' => $sort,
            'direction' => $direction,
            'status' => $status,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];
    }

    private function validateAppointmentData(array $input): array
    {
        $errors = [];
        $appointment_code = trim($input['appointment_code'] ?? '');
        $patient_name = trim($input['patient_name'] ?? '');
        $patient_email = trim($input['patient_email'] ?? '');
        $appointment_date = trim($input['appointment_date'] ?? '');
        $status = trim($input['status'] ?? 'pending');
        $note = trim($input['note'] ?? '');

        if ($appointment_code === '') $errors['appointment_code'] = 'Mã lịch hẹn không được để trống.';
        if ($patient_name === '') $errors['patient_name'] = 'Tên bệnh nhân không được để trống.';
        if ($patient_email !== '' && !filter_var($patient_email, FILTER_VALIDATE_EMAIL)) {
            $errors['patient_email'] = 'Email không đúng định dạng.';
        }
        if ($appointment_date === '') $errors['appointment_date'] = 'Ngày hẹn không được để trống.';
        if (!in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'], true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }
        return compact('errors') + ['values' => compact('appointment_code', 'patient_name', 'patient_email', 'appointment_date', 'status', 'note')];
    }

    public function createAppointment(array $input): array
    {
        $validation = $this->validateAppointmentData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->create($validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return [
                'success' => false,
                'errors' => ['appointment_code' => 'Mã lịch hẹn này đã tồn tại.']
            ];
        }
    }

    public function updateAppointment(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Lịch hẹn không tồn tại.']];
        }
        $validation = $this->validateAppointmentData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }
        $this->repo->update($id, $validation['values']);
        return ['success' => true, 'errors' => []];
    }

    public function deleteAppointment(int $id): array
    {
        if ($id <= 0) return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        $this->repo->delete($id);
        return ['success' => true, 'errors' => []];
    }
}
