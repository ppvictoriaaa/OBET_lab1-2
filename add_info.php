<?php

if (isset($_GET['detail_id']) && isset($_GET['name'])) {
    $detail_id = intval($_GET['detail_id']);
    $name = htmlspecialchars($_GET['name']);
} else {
    echo "Required parameters are missing<br>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $link = mysqli_connect("localhost:3307", "root", "password", "mytechcard");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $type = mysqli_real_escape_string($link, $_POST['process_type']);
    $time = mysqli_real_escape_string($link, $_POST['process_time_min']);

    if (!empty($type) && !empty($time)) {
        $query = "INSERT INTO info_detail (process_type, process_time_min, detail_id) VALUES ('$type', '$time', '$detail_id')";

        if (mysqli_query($link, $query)) {
            header("Location: info_detail.php?detail_id=" . $detail_id . "&name=" . urlencode($name) . "");
            exit();
        } else {
            echo "Error: " . mysqli_error($link);
        }
    } else {
        echo "All fields are required!";
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Info</title>
    <style>
        body {
            background-color: #2c2f36;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }

        form {
            background-color: #3d4349;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            height: 300px;
            width: 30%;
            flex-direction: column;
            justify-content: center;
            justify-self: center;
        }

        input,
        button {
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            border: none;
        }

        button {
            background-color: rgb(163, 31, 77);
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: rgb(198, 64, 98);
        }
    </style>
</head>

<body>
    <h1>Add Info for <?php echo $name; ?></h1>
    <form method="POST" action="">
        <input type="text" name="process_type" placeholder="Enter process type" required><br>
        <input type="number" name="process_time_min" placeholder="Enter process time" required><br>
        <button type="submit">Add</button>
    </form>
    <br>
    <a href='javascript:history.back()' style='color: #f0f0f0; text-decoration: none;'>Back to List</a>

</body>

</html>