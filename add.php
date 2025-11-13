<?php
include 'header.php';
include 'db.php';

$allowed = ['department','faculty','course','room','equipment','timetable','allocation_log'];
$table = $_GET['table'] ?? '';
if(!in_array($table, $allowed)){
    echo "<div class='alert alert-danger'>Invalid table.</div>";
    include 'footer.php';
    exit;
}

// get columns to build form
$cols = [];
$res = $conn->query("SHOW COLUMNS FROM `$table`");
while($r = $res->fetch_assoc()) $cols[] = $r;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // build insert SQL
    $fields = [];
    $placeholders = [];
    $types = '';
    $values = [];

    foreach($cols as $c){
        if(strpos($c['Extra'],'auto_increment') !== false) continue; // skip pk auto
        $f = $c['Field'];
        $fields[] = "`$f`";
        $placeholders[] = '?';
        $values[] = ($_POST[$f] ?? null);
    }
    // prepare statement
    $sql = "INSERT INTO `$table` (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "<div class='alert alert-danger'>Prepare failed: " . $conn->error . "</div>";
    } else {
        // bind params dynamically as strings
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        if($stmt->execute()){
            echo "<div class='alert alert-success'>Record added. <a href='view.php?table={$table}'>Go back</a></div>";
            include 'footer.php';
            exit;
        } else {
            echo "<div class='alert alert-danger'>Insert failed: " . $stmt->error . "</div>";
        }
    }
}

?>

<h3 class="mb-3">Add to <?php echo htmlspecialchars($table); ?></h3>
<form method="post" class="mb-4">
  <?php foreach($cols as $c):
        if(strpos($c['Extra'],'auto_increment') !== false) continue;
        $f = $c['Field'];
  ?>
    <div class="mb-3">
      <label class="form-label"><?php echo htmlspecialchars($f); ?></label>
      <input type="text" name="<?php echo htmlspecialchars($f); ?>" class="form-control" value="<?php echo htmlspecialchars($_POST[$f] ?? '') ?>">
      <div class="small muted"><?php echo htmlspecialchars($c['Type']); ?></div>
    </div>
  <?php endforeach; ?>
  <button class="btn btn-primary">Insert</button>
  <a href="view.php?table=<?php echo urlencode($table); ?>" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'footer.php'; ?>
