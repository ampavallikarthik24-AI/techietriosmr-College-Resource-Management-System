<?php
// header.php
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>TechieTrios MR - DBMS Project</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding-bottom:40px; }
    .small-muted { font-size:0.85rem; color:#666; }
    table { font-size:0.95rem; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">TechieTrios MR</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="view.php?table=department">Departments</a></li>
        <li class="nav-item"><a class="nav-link" href="view.php?table=faculty">Faculty</a></li>
        <li class="nav-item"><a class="nav-link" href="view.php?table=course">Courses</a></li>
        <li class="nav-item"><a class="nav-link" href="view.php?table=room">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="view.php?table=equipment">Equipment</a></li>
        <li class="nav-item"><a class="nav-link" href="view.php?table=timetable">Timetable</a></li>
        <li class="nav-item"><a class="nav-link" href="view.php?table=allocation_log">Allocation Log</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
