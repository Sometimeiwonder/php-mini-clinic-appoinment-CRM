<?php
/**
 * Seed 200 bệnh nhân + 200 lịch hẹn để test pagination/index
 * Chạy: php database/seed_data.php
 */

$config = require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/Core/Database.php';

$pdo = Database::connect($config);

$firstNames = ['Nguyen', 'Tran', 'Le', 'Pham', 'Hoang', 'Vo', 'Dinh', 'Ngo', 'Duong', 'Ly'];
$lastNames = ['Van A', 'Thi B', 'Van C', 'Thi D', 'Van E', 'Thi F', 'Van G', 'Thi H', 'Van I', 'Thi J', 'Van K', 'Thi L', 'Van M', 'Thi N', 'Van O'];
$genders = ['male', 'female', 'other'];
$statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
$notes = ['Kham tong quat', 'Tai kham', 'Kham da lieu', 'Kham mat', 'Kham rang', 'Kham than kinh', 'Kham noi tong quat', 'Kham co xuong khop', 'Kham tai mui hong', 'Kham da liu'];

echo "Seeding 200 patients...\n";
$patients = [];
$stmt = $pdo->prepare("INSERT IGNORE INTO patients (name, email, phone, gender) VALUES (?, ?, ?, ?)");
for ($i = 1; $i <= 200; $i++) {
    $firstName = $firstNames[array_rand($firstNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    $name = $firstName . ' ' . $lastName;
    $email = strtolower($firstName . $lastName) . $i . '@example.com';
    $phone = '090' . str_pad($i, 7, '0', STR_PAD_LEFT);
    $gender = $genders[array_rand($genders)];
    
    $stmt->execute([$name, $email, $phone, $gender]);
    $patients[] = ['name' => $name, 'email' => $email];
    echo "  Patient {$i}/200: {$name}\n";
}

echo "\nSeeding 200 appointments...\n";
$stmt = $pdo->prepare("INSERT IGNORE INTO appointments (appointment_code, patient_name, patient_email, appointment_date, status, note) VALUES (?, ?, ?, ?, ?, ?)");
for ($i = 1; $i <= 200; $i++) {
    $patient = $patients[array_rand($patients)];
    $code = 'APT-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
    $date = date('Y-m-d', strtotime("+{$i} days"));
    $status = $statuses[array_rand($statuses)];
    $note = $notes[array_rand($notes)];
    
    $stmt->execute([$code, $patient['name'], $patient['email'], $date, $status, $note]);
    echo "  Appointment {$i}/200: {$code}\n";
}

echo "\nDone! Seeded 200 patients + 200 appointments.\n";
