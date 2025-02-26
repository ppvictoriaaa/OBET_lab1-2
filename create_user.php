<?php
$link = mysqli_connect("localhost:3307", "root", "password");

if ($link) {
    echo "connect is okay", "<br>";
} else {
    echo "connect is not okay";
    exit;
}

$query_create_user = "CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED BY 'admin';";
$create_user_query = mysqli_query($link, $query_create_user);
if ($create_user_query) {
    echo "User created successfully.", "<br>";
} else {
    echo "Failed to create user.", "<br>";
    exit;
}

$query_grant_privileges = "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' WITH GRANT OPTION;";
$grant_query = mysqli_query($link, $query_grant_privileges);
if ($grant_query) {
    echo "Privileges granted successfully.", "<br>";
} else {
    echo "Failed to grant privileges.", "<br>";
    exit;
}

$query_flush = "FLUSH PRIVILEGES;";
$flush_query = mysqli_query($link, $query_flush);
if ($flush_query) {
    echo "Privileges flushed successfully.", "<br>";
} else {
    echo "Failed to flush privileges.", "<br>";
}
