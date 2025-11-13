<?php
include 'db.php';

$allowed = ['department','faculty','course','room','equipment','timetable','allocation_log'];
$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if(!in_array($table, $allowed) || $id===''){
    die("Invalid request.");
}

// detect pk
$res = $conn->query("SHOW COLUMNS FROM `$table`");
$cols = [];
while($r = $res->fetch_assoc()) $cols[] = $r;
$pk = $cols[0]['Field'];

$stmt = $conn->prepare("DELETE FROM `$table` WHERE `$pk` = ?");
$stmt->bind_param('s', $id);
if($stmt->execute()){
    header("Location: view.php?table={$table}");
    exit;
} else {
    die("Delete failed: ".$stmt->error);
}
