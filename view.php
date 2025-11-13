<?php
include 'header.php';
include 'db.php';

// allowed tables (from your DB)
$allowed = ['department','faculty','course','room','equipment','timetable','allocation_log'];

// get table param
$table = $_GET['table'] ?? 'faculty';
if(!in_array($table, $allowed)){
    echo "<div class='alert alert-danger'>Invalid table.</div>";
    include 'footer.php';
    exit;
}

// detect columns and primary key (we'll pick first column as PK)
$cols = [];
$pk = null;
$res = $conn->query("SHOW COLUMNS FROM `$table`");
while($r = $res->fetch_assoc()){
    $cols[] = $r;
}
if(count($cols)==0){
    echo "<div class='alert alert-warning'>No columns found for $table</div>";
    include 'footer.php';
    exit;
}
$pk = $cols[0]['Field']; // assume first column is primary key (works for your schema)

// fetch rows
$data = [];
$rs = $conn->query("SELECT * FROM `$table`");
while($row = $rs->fetch_assoc()) $data[] = $row;
?>
<div class="d-flex justify-content-between align-items-center">
  <h3 class="mb-3 text-capitalize">Table: <?php echo htmlspecialchars($table); ?></h3>
  <div>
    <a class="btn btn-success" href="add.php?table=<?php echo urlencode($table); ?>">+ Add</a>
    <a class="btn btn-secondary" href="home.php">Dashboard</a>
  </div>
</div>

<?php if(empty($data)): ?>
  <div class="alert alert-info">No records found. Use Add to insert new rows.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <?php foreach($cols as $c): ?>
            <th><?php echo htmlspecialchars($c['Field']); ?></th>
          <?php endforeach; ?>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data as $row): ?>
          <tr>
            <?php foreach($cols as $c): $f = $c['Field']; ?>
              <td><?php echo htmlspecialchars($row[$f]); ?></td>
            <?php endforeach; ?>
            <td style="white-space:nowrap;">
              <a class="btn btn-sm btn-primary" href="edit.php?table=<?php echo urlencode($table); ?>&id=<?php echo urlencode($row[$pk]); ?>">Edit</a>
              <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')" href="delete.php?table=<?php echo urlencode($table); ?>&id=<?php echo urlencode($row[$pk]); ?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
