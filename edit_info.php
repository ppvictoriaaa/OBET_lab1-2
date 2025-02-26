<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Info</title>
    <style>
        body {
            background-color: #2c2f36;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: rgb(198, 64, 98);
            padding: 20px;
            font-size: 1.8em;
        }

        form {
            margin: 0 auto;
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
            background-color: #3d4349;
            border-radius: 10px;
        }

        label {
            font-size: 1.1em;
            color: #f0f0f0;
        }

        input[type="text"],
        input[type="number"],
        select {
            padding: 10px;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            margin-top: 5px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .save-button,
        .cancel-button {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: rgb(163, 31, 77);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .save-button:hover,
        .cancel-button:hover {
            background-color: rgb(198, 64, 98);
        }

        a {
            color: #f0f0f0;
            text-decoration: none;
            text-align: center;
            display: block;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php
        $link = mysqli_connect("localhost:3307", "root", "password", "mytechcard");

        if ($link) {
            $detail_id = intval($_GET['detail_id']);
            $name = htmlspecialchars($_GET['name']);
            $ids = $_GET['ids'];

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
                foreach ($_POST['process_type'] as $key => $updated_process_type) {
                    $updated_process_time = intval($_POST['process_time'][$key]);
                    $id = intval($_POST['id'][$key]);

                    $update_query = "UPDATE info_detail SET process_type = '$updated_process_type', process_time_min = $updated_process_time WHERE id = $id";
                    if (!mysqli_query($link, $update_query)) {
                        echo "<p style='text-align: center;'>Error saving changes for ID $id!</p>";
                    }
                }
                header("Location: info_detail.php?detail_id=$detail_id&name=" . urlencode($name) . "");
            }

            $query = "SELECT * FROM info_detail WHERE id IN ($ids)";
            $result = mysqli_query($link, $query);

            echo "<h2>Edit Info for $name</h2>";
            echo "<form method='POST' action=''>";

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<div>";
                    echo "<label for='process_type'>Process Type for ID " . $row['id'] . ":</label>";
                    echo "<input type='text' id='process_type' name='process_type[]' value='" . htmlspecialchars($row['process_type']) . "' required>";
                    echo "<input type='hidden' name='id[]' value='" . $row['id'] . "'>";
                    echo "</div>";
                    echo "<div>";
                    echo "<label for='process_time'>Process Time (min) for ID " . $row['id'] . ":</label>";
                    echo "<input type='number' id='process_time' name='process_time[]' value='" . htmlspecialchars($row['process_time_min']) . "' required>";
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align: center;'>No data found for the selected IDs.</p>";
            }

            echo "<div class='button-container'>";
            echo "<button type='submit' name='save' class='save-button'>Save Changes</button>";
            echo "<a href='info_detail.php?detail_id=$detail_id&name=" . urlencode($name) . "' class='cancel-button'>Cancel</a>";
            echo "</div>";
            echo "</form>";

            mysqli_close($link);
        } else {
            echo "Connection failed", "<br>";
        }
        ?>

    </div>

</body>

</html>