<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $link = mysqli_connect("localhost:3307", "root", "password", "mytechcard");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $name = mysqli_real_escape_string($link, $_POST['name']);
    $material = mysqli_real_escape_string($link, $_POST['material']);

    if (!empty($name) && !empty($material)) {
        $query = "INSERT INTO details_list (name, material) VALUES ('$name', '$material')";

        if (mysqli_query($link, $query)) {
            header("Location: index.php");
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
    <title>Add Detail</title>
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
    <h1>Add New Detail</h1>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Enter name" required><br>
        <input type="text" name="material" placeholder="Enter material" required><br>
        <button type="submit">Add</button>
    </form>
    <br>
    <a href="index.php" style="color: #f0f0f0; text-decoration: none">Back to List</a>
</body>

</html>