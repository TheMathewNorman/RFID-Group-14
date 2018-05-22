
<div id="menu">
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='index.php';">Home</button>
    </div>
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='listmember.php';">Members</button>
    <div class="dropdown-content">
        <a href="addmember.php">Add New</a>
        <a href="listmember.php">List</a>
    </div>
    </div>
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='viewlogs.php';">View Logs</button>
    </div>
    <div class="dropdown">
    <button class="dropbtn" onclick="location.href='listadmin.php';">Admins</button>
    <div class="dropdown-content">
        <a href="addadmin.php">Add New</a>
        <a href="listadmin.php">List</a>
    </div>
    </div>

    <div class="dropdown right">
        <button class="dropbtn" onclick="location.href='about.php';">About</button>
    </div>
    <div class="dropdown right">
        <button class="dropbtn">Settings</button>
        <div class="dropdown-content">
            <a href="settings.php">General</a>
            <a href="settings.php">Database</a>
        </div>
    </div>
</div>