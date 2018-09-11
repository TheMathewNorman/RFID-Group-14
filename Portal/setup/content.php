<?php
    $output = array();

    if ($page == "noConfig") {
        // Add error to output for no configuration.
        $error['title'] = "Missing config.php";
        $error['body'] = $_SERVER['DOCUMENT_ROOT']."/config.php is missing.<br>This issue can typically be resolved by editing the 'config.sample.php' to include your database connection information and renaming it to 'config.php'.";
        array_push($output, $error);
    } else {
        require_once $_SERVER['DOCUMENT_ROOT'].'/php/setup.php';
        $DatabaseSetup = new DatabaseSetup(); 
        
        if ($page == "database") {
            // Add error to output for no configuration.
            $error['title'] = "Failed to connect to Database";
            $error['body'] = "Please check the credentials saved in ".$_SERVER['DOCUMENT_ROOT']."/config.php.<br>Also ensure that the database specified in the file exists and that the specified username/password is correct.";
            array_push($output, $error);
        } else if ($page == "tables") {
            
            // Create tables
            try {
                $DatabaseSetup->createTables();
            } catch (Exception $e) {
                $error['title'] = "Error creating tables";
                $error['body'] = "Please check to ensure the user saved in ".$_SERVER['DOCUMENT_ROOT']."/config.php has permission to create tables.";
                array_push($output, $error);
            }

            // Create key admin
            try {
                $pass = $DatabaseSetup->createPrimaryAdmin();
                $error['title'] = "Key admin account created.";
                $error['body'] = "A key administrator account has been created.<br><br>Email: admin@system<br>Password: $pass<br><br>Please take note of this login as it will serve as a backup.<br>Once logged in you may create an account for any other users who may need access to the system.";
                array_push($output, $error);
            } catch (Exception $e) {
                $error['title'] = "A primary admin account already exists.";
                $error['body'] = "A primary admin account was not created as one already exists.<br>If unable to access the system, please drop the admins table from the database and try again.";
                array_push($output, $error);
            }
        }
    }

    foreach ($output as $message) {
        //echo "";
        echo "<h1 style='margin-bottom: 10px'>".$message['title']."</h1>";
        echo '<p style="">'.$message['body'].'</p>';

        echo "<br><hr><br>";
    }
?>
<br><br>
<p>
    Once the above has been resolved or acknowledged you may <a href="../index.php">click here</a> to try again.
</p>