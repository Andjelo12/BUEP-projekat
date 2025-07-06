<?php
require_once 'config.php'; // ovde se podrazumeva da je $pdo instanca PDO
require_once 'functions_def.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['action'])) {
    $email = $_POST['email'];
    $action = $_POST['action'];

    if ($action === 'block') {
        $sql = "UPDATE tokens SET blocked = 1 WHERE email = :email";
    } elseif ($action === 'unblock') {
        $sql = "UPDATE tokens SET blocked = 0 WHERE email = :email";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nevažeća akcija.']);
        exit;
    }

    try {
        $stmt = $GLOBALS['pdo']->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Greška pri izvršavanju upita.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'PDO greška: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nedostaju podaci.']);
}

?>
