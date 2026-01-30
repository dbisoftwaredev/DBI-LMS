<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = 'paul';
$dbname = 'bitnami_moodle';
$backup_file = 'moodle_backup.sql';

echo "Connecting to database via PDO...\n";

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Dropping database '$dbname' if it exists to clean up broken tables...\n";
    $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");

    echo "Creating database '$dbname'...\n";
    $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    
    echo "Database created/selected successfully\n";

    // Read SQL file
    echo "Reading backup file '$backup_file'...\n";
    if (!file_exists($backup_file)) {
        die("Error: Backup file not found!\n");
    }

    // Temporary disable foreign key checks and strict mode for import
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    try {
        $pdo->exec("SET SESSION innodb_strict_mode = 0");
    } catch (PDOException $e) {
        echo "Warning: Could not set innodb_strict_mode: " . $e->getMessage() . "\n";
    }

    $pdo->exec("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

    // Process the file line by line
    $handle = fopen($backup_file, "r");
    if ($handle) {
        $templine = '';
        $count = 0;
        while (($line = fgets($handle)) !== false) {
            // Skip comments and empty lines
            if (substr(trim($line), 0, 2) == '--' || trim($line) == '')
                continue;

            $templine .= $line;
            // If the query ends with a semicolon, execute it
            if (substr(trim($line), -1, 1) == ';') {
                try {
                    $pdo->exec($templine);
                } catch (PDOException $e) {
                    echo "Error performing query '<strong>" . substr($templine, 0, 100) . "...</strong>': " . $e->getMessage() . "\n";
                }
                $templine = '';
                $count++;
                if ($count % 100 == 0) echo ".";
                if ($count % 5000 == 0) echo "\n";
            }
        }
        fclose($handle);
        echo "\nImport complete! Processed approximately $count queries.\n";
    } else {
        echo "Error opening backup file.\n";
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}
?>
