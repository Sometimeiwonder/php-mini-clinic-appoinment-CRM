<?php
/**
 * Seed 200 patients + 200 appointments
 * Run: php database/seed_data.php
 */

require __DIR__ . '/../app/Core/Database.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = (new Database($config))->getConnection();

$firstNames = ['Nguyen', 'Tran', 'Le', 'Pham', 'Hoang', 'Vo', 'Dinh', 'Dang', 'Bui', 'Do', 'Ngo', 'Duong', 'Ly'];
$lastNamesMale = ['Van A', 'Van B', 'Van C', 'Van D', 'Van E', 'Van F', 'Van G', 'Van H', 'Van I', 'Van K'];
$lastNamesFemale = ['Thi A', 'Thi B', 'Thi C', 'Thi D', 'Thi E', 'Thi F', 'Thi G', 'Thi H', 'Thi I', 'Thi K'];
$statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
$notes = [
    'Kham tong quat', 'Tai kham', 'Kham da lieu', 'Kham mat', 'Kham rang',
    'Kham than kinh', 'Kham noi tong quat', 'Kham co xuong khop', 'Kham tai mui hong',
    'Kham nhi', 'Kham san phu khoa', 'Kham tim mach', 'Kham than', 'Kham gan', 'Kham mat'
];

$genderMap = [
    'Van A' => 'male', 'Van B' => 'male', 'Van C' => 'male', 'Van D' => 'male',
    'Van E' => 'male', 'Van F' => 'male', 'Van G' => 'male', 'Van H' => 'male',
    'Van I' => 'male', 'Van K' => 'male',
    'Thi A' => 'female', 'Thi B' => 'female', 'Thi C' => 'female', 'Thi D' => 'female',
    'Thi E' => 'female', 'Thi F' => 'female', 'Thi G' => 'female', 'Thi H' => 'female',
    'Thi I' => 'female', 'Thi K' => 'female',
];

echo "Seeding patients...\n";
$patients = [];
for ($i = 1; $i <= 200; $i++) {
    $firstName = $firstNames[array_rand($firstNames)];
    $isMale = array_rand($lastNamesMale) !== false && (array_rand(['m','f']) === 'm');
    $lastName = $isMale
        ? $lastNamesMale[array_rand($lastNamesMale)]
        : $lastNamesFemale[array_rand($lastNamesFemale)];
    $name = $firstName . ' ' . $lastName;
    $email = strtolower(str_replace(' ', '', $name)) . $i . '@example.com';
    $phone = '09' . str_pad((string) $i, 8, '0', STR_PAD_LEFT);
    $gender = $isMale ? 'male' : 'female';

    $stmt = $pdo->prepare("INSERT INTO patients (name, email, phone, gender) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $gender]);
    $patients[] = ['name' => $name, 'email' => $email];
}
echo "Inserted " . count($patients) . " patients.\n";

echo "Seeding appointments...\n";
for ($i = 1; $i <= 200; $i++) {
    $patient = $patients[array_rand($patients)];
    $code = 'APT-2026-' . str_pad((string) ($i + 17), 4, '0', STR_PAD_LEFT);
    $date = '2026-' . str_pad((string) rand(7, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad((string) rand(1, 28), 2, '0', STR_PAD_LEFT);
    $status = $statuses[array_rand($statuses)];
    $note = $notes[array_rand($notes)];

    $stmt = $pdo->prepare("INSERT INTO appointments (appointment_code, patient_name, patient_email, appointment_date, status, note) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$code, $patient['name'], $patient['email'], $date, $status, $note]);
}
echo "Inserted 200 appointments.\n";

echo "Done!\n";
