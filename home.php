<?php
include 'header.php';
include 'db.php';

// quick counts for dashboard
$tables = ['department','faculty','course','room','equipment','timetable','allocation_log'];
$counts = [];
foreach($tables as $t){
  $res = $conn->query("SELECT COUNT(*) AS c FROM `$t`");
  $row = $res->fetch_assoc();
  $counts[$t] = $row['c'] ?? 0;
}
?>
<h1 class="mb-3">Dashboard</h1>
<p class="small-muted">Simple admin interface for your DBMS project. Click a table to view / manage records.</p>

<div class="row">
  <?php foreach($tables as $t): ?>
    <div class="col-md-3 mb-3">
      <a class="text-decoration-none" href="view.php?table=<?php echo $t; ?>">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-capitalize"><?php echo str_replace('_',' ',$t); ?></h5>
            <p class="card-text">Records: <strong><?php echo $counts[$t]; ?></strong></p>
          </div>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<footer class="text-center mt-5 text-muted small">
  © 2025 TechieTrios MR — DBMS Project Dashboard
</footer>

<?php include 'footer.php'; ?>
