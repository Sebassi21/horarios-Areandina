<?php
header('Content-Type: application/json');
$host = "sql207.infinityfree.com";
$db   = "if0_40535926_horariosandina";
$user = "if0_40535926"; 
$pass = "4h5tzJpav7EwbU";  

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die(json_encode(["error" => $e->getMessage()]));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener todos los horarios
    $stmt = $pdo->query("SELECT * FROM cursos");
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $schedule = [];
    foreach ($cursos as $c) {
        $key = $c['semestre'] . "_" . $c['jornada'];
        $schedule[$key][] = [
            "day" => $c['dia'],
            "slot" => $c['bloque_hora'],
            "subject" => $c['asignatura'],
            "nrc" => $c['nrc'],
            "professor" => $c['profesor'],
            "classroom" => $c['aula']
        ];
    }

    // Obtener electivas
    $stmt = $pdo->query("SELECT * FROM electivas");
    $electivas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["schedule" => $schedule, "electives" => $electivas]);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data['action'] === 'save_course') {
        // Eliminar si ya existe en ese hueco para sobrescribir
        $stmt = $pdo->prepare("DELETE FROM cursos WHERE semestre=? AND jornada=? AND dia=? AND bloque_hora=?");
        $stmt->execute([$data['semestre'], $data['jornada'], $data['dia'], $data['slot']]);
        
        // Insertar nuevo
        $stmt = $pdo->prepare("INSERT INTO cursos (semestre, jornada, dia, bloque_hora, asignatura, nrc, profesor, aula) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$data['semestre'], $data['jornada'], $data['dia'], $data['slot'], $data['subject'], $data['nrc'], $data['professor'], $data['classroom']]);
        
        echo json_encode(["status" => "success"]);
    }
}
?>