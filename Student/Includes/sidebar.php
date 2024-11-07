<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
  <a class="sidebar-brand d-flex align-items-center bg-gradient-primary justify-content-center" href="index.php">
    <div class="sidebar-brand-icon">
      <img src="img/logo/attnlg.jpg">
    </div>
    <div class="sidebar-brand-text mx-3">AMS</div>
  </a>
  <hr class="sidebar-divider my-0">
  <li class="nav-item active">
    <a class="nav-link" href="index.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>
  <hr class="sidebar-divider">
  <div class="sidebar-heading">
    Attendance
  </div>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
      aria-expanded="true" aria-controls="collapseBootstrapcon">
      <i class="fa fa-calendar-alt"></i>
      <span>Manage Attendance</span>
    </a>
    <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Manage Attendance</h6>
        <a class="collapse-item" href="viewAttendance.php">View Attendance</a>
        <a class="collapse-item" href="downloadRecord.php">Today's Attendance Report (xls)</a>
        <a class="collapse-item" href="downloadallRecord.php">All Attendance Report (xls)</a>
      </div>
    </div>
  </li>
  <hr class="sidebar-divider">
</ul>
