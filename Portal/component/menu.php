
<div id="menu">
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='index.php';">Home</button>
    </div>
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='listmember.php';">Members</button>
    <div class="dropdown-content">
        <a href="addmember.php">Add</a>
        <a href="listmember.php">List</a>
    </div>
    </div>
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='viewlogs.php';">Logs</button>
    <div class="dropdown-content">
        <a href="viewlogs.php">View check-ins</a>
        <a href="viewlogs.php">View all</a>
    </div>
    </div>
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='listadmin.php';">Admins</button>
    <div class="dropdown-content">
        <a href="addadmin.php">Add</a>
        <a href="listadmin.php">List</a>
    </div>
    </div>

    <div class="dropdown right">
        <button class="dropbtn" onclick="location.href='../php/logout.php'">Logout</button>
    </div>
    <div class="dropdown right">
        <button class="dropbtn" onclick="location.href='settings.php';">Settings</button>
        <div class="dropdown-content">
            <a href="settings.php">General</a>
            <a href="settings.php">Database</a>
            <a href="about.php">About</a>
        </div>
    </div>
</div>