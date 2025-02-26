<?php
$link = mysqli_connect("localhost:3307", "root", "password");

if (!$link) {
    die("Error with connection");
} else {
    echo "Database is connected<br>";
}

$create_db_query = "CREATE DATABASE IF NOT EXISTS mytechcard";
if (!mysqli_query($link, $create_db_query)) {
    die("Error: " . mysqli_error($link));
} else {
    echo "mytechcard db was created successfully<br>";
}

mysqli_select_db($link, "mytechcard");

$create_table_query = "CREATE TABLE IF NOT EXISTS details_list (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    material VARCHAR(100) NOT NULL
)";

if (!mysqli_query($link, $create_table_query)) {
    die("Error: " . mysqli_error($link));
} else {
    echo "table details_list was created successfully<br>";
}

$create_info_table_query = "CREATE TABLE IF NOT EXISTS info_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    process_type VARCHAR(100) NOT NULL,
    process_time_min INT NOT NULL,
    detail_id INT NOT NULL,
    FOREIGN KEY (detail_id) REFERENCES details_list(detail_id) ON DELETE CASCADE
)";

if (!mysqli_query($link, $create_info_table_query)) {
    die("Error: " . mysqli_error($link));
} else {
    echo "table info_detail was created successfully";
}

mysqli_close($link);
