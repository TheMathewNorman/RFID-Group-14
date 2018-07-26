<?php
session_start();

class Sessions {

    // Create a session
    function startSession($userid, $fname) {
        
    }

    // End a Session
    function endSession() {
        session_unset();
        session_destroy();
    }

}

?>

<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
    $connection = mysqli_connect("localhost", "root", "");
// Selecting Database
    $db = mysqli_select_db("therfidmen", $connection);
    session_start();// Starting Session
// Storing Session
    $user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
    $ses_sql=mysqli_query("select username from login where username='$user_check'", $connection);
    $row = mysqli_fetch_assoc($ses_sql);
    $login_session =$row['username'];
    if(!isset($login_session)){
    mysqli_close($connection); // Closing Connection
    header('Location: index.php'); // Redirecting To Home Page
}
?>

<?php
    session_start(); // Starting Session

    $error=''; // Variable To Store Error Message
    if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
    $error = "Username or Password is invalid";
    }
    else
    {
        
// Define $username and $password
    $username=$_POST['username'];
    $password=$_POST['password'];
    // Establishing Connection with Server by passing server_name, user_id and password as a parameter
    $connection = mysqli_connect("localhost", "root", "");
// To protect MySQL injection for Security purpose
    $username = stripslashes($username);
    $password = stripslashes($password);
    $username = mysqli_real_escape_string($username);
    $password = mysqli_real_escape_string($password);
// Selecting Database
    $db = mysqli_select_db("therfidmen", $connection);
// SQL query to fetch information of registerd users and finds user match.
    $query = mysqli_query("select * from login where password='$password' AND username='$username'", $connection);
    $rows = mysqli_num_rows($query);
    if ($rows == 1) {
    $_SESSION['login_user']=$username; // Initializing Session
    header("location: profile.php"); // Redirecting To Other Page
    } else {
    $error = "Username or Password is invalid";
    }
    mysqli_close($connection); // Closing Connection
    }
    }
    ?>
