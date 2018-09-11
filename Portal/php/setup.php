<?php
require $_SERVER['DOCUMENT_ROOT'].'/php/database.php';

class DatabaseSetup extends Database {

    // Create the tables.
    function createTables() {
        
        $sql = "CREATE TABLE IF NOT EXISTS admins (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE,
            phone VARCHAR(10),
            passhash VARCHAR(128) NOT NULL
        ); CREATE TABLE IF NOT EXISTS members (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR(10),
            cardkey VARCHAR(128) NOT NULL
        ); CREATE TABLE IF NOT EXISTS privilege (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6),
            reader_group INT(6)
        ); CREATE TABLE IF NOT EXISTS readers (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            reader_name VARCHAR(30) NOT NULL,
            reader_group INT(6) NOT NULL,
            signature VARCHAR(60) NOT NULL,
            approved BOOLEAN NOT NULL
        ); CREATE TABLE IF NOT EXISTS logs (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6) NOT NULL,
            access_date TIMESTAMP NOT NULL,
            check_in BOOL DEFAULT false
        )";
            
        try {
            $this->_dbconn->exec($sql);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    private function generatePassword() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()<>";
        return substr(str_shuffle($chars),0,16);
    }

    private function countAdmins() {
        // Execute query
        $sql = "SELECT count(*) FROM admins";
        $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute();
        
        if ($stmt->fetchColumn() == 0) {
            return true;
        } else {
            return false;
        }
    }

    function createPrimaryAdmin() {
        // Create an admin system admin account only if there are no existing admins.
        if ($this->countAdmins()) {
            $pass = $this->generatePassword();
            $this->addAdmin('SYSTEM', 'ADMIN', 'admin@system', '', $pass);
        } else {
            throw new Exception("A primary administrator already exists.");
        }

        return $pass;
    }

}

?>