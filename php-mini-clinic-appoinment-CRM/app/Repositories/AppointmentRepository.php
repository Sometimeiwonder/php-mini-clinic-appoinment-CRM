<?php

class AppointmentRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = '', string $status = '', string $dateFrom = '', string $dateTo = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM appointments WHERE deleted_at IS NULL";
        $conditions = [];
        $params = [];

        if ($keyword !== '') {
            $conditions[] = "(appointment_code LIKE :kw1 OR patient_name LIKE :kw2 OR patient_email LIKE :kw3)";
            $like = '%' . $keyword . '%';
            $params['kw1'] = $like;
            $params['kw2'] = $like;
            $params['kw3'] = $like;
        }
        if ($status !== '') {
            $conditions[] = "status = :status";
            $params['status'] = $status;
        }
        if ($dateFrom !== '') {
            $conditions[] = "appointment_date >= :date_from";
            $params['date_from'] = $dateFrom;
        }
        if ($dateTo !== '') {
            $conditions[] = "appointment_date <= :date_to";
            $params['date_to'] = $dateTo;
        }
        if (!empty($conditions)) {
            $sql .= " AND " . implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort = 'created_at', string $direction = 'desc', string $status = '', string $dateFrom = '', string $dateTo = ''): array
    {
        $allowedSorts = ['id', 'appointment_code', 'patient_name', 'patient_email', 'appointment_date', 'status', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), $allowedDirections, true)) {
            $direction = 'desc';
        }

        $sql = "SELECT id, appointment_code, patient_name, patient_email, appointment_date, status, created_at
                FROM appointments WHERE deleted_at IS NULL";
        $conditions = [];
        $params = [];

        if ($keyword !== '') {
            $conditions[] = "(appointment_code LIKE :kw1 OR patient_name LIKE :kw2 OR patient_email LIKE :kw3)";
            $like = '%' . $keyword . '%';
            $params['kw1'] = $like;
            $params['kw2'] = $like;
            $params['kw3'] = $like;
        }
        if ($status !== '') {
            $conditions[] = "status = :status";
            $params['status'] = $status;
        }
        if ($dateFrom !== '') {
            $conditions[] = "appointment_date >= :date_from";
            $params['date_from'] = $dateFrom;
        }
        if ($dateTo !== '') {
            $conditions[] = "appointment_date <= :date_to";
            $params['date_to'] = $dateTo;
        }
        if (!empty($conditions)) {
            $sql .= " AND " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE id = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO appointments (appointment_code, patient_name, patient_email, appointment_date, status, note)
                VALUES (:appointment_code, :patient_name, :patient_email, :appointment_date, :status, :note)";
        $stmt = $this->db->prepare($sql);
        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate appointment code.');
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = "UPDATE appointments SET appointment_code=:appointment_code, patient_name=:patient_name,
                patient_email=:patient_email, appointment_date=:appointment_date,
                status=:status, note=:note, updated_at=NOW() WHERE id=:id";
        return $this->db->prepare($sql)->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE appointments SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL");
        return $stmt->execute(['id' => $id]);
    }
}
