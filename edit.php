<?php
include 'header.php';
include 'db.php';

$allowed = ['department','faculty','course','room','equipment','timetable','allocation_log'];
$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if(!in_array($table, $allowed) || $id===''){
    echo "<div class='alert alert-danger'>Invalid request.</div>";
    include 'footer.php';
    exit;
}

// get columns
$cols = [];
$res = $conn->query("SHOW COLUMNS FROM `$table`");
while($r = $res->fetch_assoc()) $cols[] = $r;
$pk = $cols[0]['Field'];

// fetch existing row
$stmt = $conn->prepare("SELECT * FROM `$table` WHERE `$pk` = ?");
$stmt->bind_param('s', $id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows===0){
    echo "<div class='alert alert-warning'>Record not found.</div>";
    include 'footer.php';
    exit;
}
$row = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $pairs = [];
    $values = [];
    foreach($cols as $c){
        $f = $c['Field'];
        if($f === $pk) continue;
        $pairs[] = "`$f` = ?";
        $values[] = $_POST[$f] ?? null;
    }
    $sql = "UPDATE `$table` SET " . implode(',', $pairs) . " WHERE `$pk` = ?";
    $values[] = $id; // pk at end
    $stmt2 = $conn->prepare($sql);
    if(!$stmt2){
        echo "<div class='alert alert-danger'>Prepare failed: " . $conn->error . "</div>";
    } else {
        $types = str_repeat('s', count($values));
        $stmt2->bind_param($types, ...$values);
        if($stmt2->execute()){
            echo "<div class='alert alert-success'>Updated. <a href='view.php?table={$table}'>Back</a></div>";
            include 'footer.php'; exit;
        } else {
            echo "<div class='alert alert-danger'>Update failed: " . $stmt2->error . "</div>";
        }
    }
}

?>

<h3 class="mb-3">Edit <?php echo htmlspecialchars($table); ?> (<?php echo htmlspecialchars($pk . ' = ' . $id); ?>)</h3>

<form method="post" class="mb-4">
  <?php foreach($cols as $c):
        $f = $c['Field'];
        $val = $row[$f] ?? '';
  ?>
    <div class="mb-3">
      <label class="form-label"><?php echo htmlspecialchars($f); ?></label>
      <?php if($f === $pk): ?>
        <input class="form-control" type="text" value="<?php echo htmlspecialchars($val); ?>" disabled>
        <div class="small-muted">Primary key (not editable)</div>
      <?php else: ?>
        <input name="<?php echo htmlspecialchars($f); ?>" class="form-control" value="<?php echo htmlspecialchars($val); ?>">
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
  <button class="btn btn-primary">Save</button>
  <a class="btn btn-secondary" href="view.php?table=<?php echo urlencode($table); ?>">Cancel</a>
</form>

<?php include 'footer.php'; ?>
