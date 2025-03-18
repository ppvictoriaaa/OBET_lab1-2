<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details List</title>
    <style>
        body {
            background-color: #2c2f36;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: rgb(198, 64, 98);
            padding: 20px;
            font-size: 2em;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #3d4349;
            border-radius: 10px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            font-size: 1.1em;
        }

        th {
            background-color: rgb(163, 31, 77);
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #444c53;
        }

        tr:nth-child(odd) {
            background-color: #3d4349;
        }

        tr:hover {
            background-color: #5f666d;
        }

        a {
            color: #f0f0f0;
            text-decoration: none;
        }

        a:hover {
            color: rgb(163, 31, 77);
        }

        .container {
            padding: 20px;
            text-align: center;
        }

        .button-container {
            margin-top: 20px;
        }

        .delete-button,
        .add-button {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: rgb(163, 31, 77);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px;
        }

        .delete-button:hover,
        .add-button:hover {
            background-color: rgb(198, 64, 98);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Details List</h1>
        <?php

        $link = mysqli_connect("localhost:3307", "root", "password", "mytechcard");

        if (!$link) {
            die("Connection failed: " . mysqli_connect_error());
        }

        //deleting selected list delete_ids
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
            if (!empty($_POST['delete_ids'])) {
                $delete_ids = implode(",", array_map('intval', $_POST['delete_ids']));
                $delete_query = "DELETE FROM details_list WHERE detail_id IN ($delete_ids)";
                mysqli_query($link, $delete_query);
            }
        }

        //showing the table
        $query = "SELECT * FROM details_list";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "<form method='POST' action=''>";
            echo "<table>";
            echo "<tr><th>Select</th><th>ID</th><th>Name</th><th>Material</th><th>Date</th></tr>";

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo "<tr>";
                echo "<td><input type='checkbox' name='delete_ids[]' value='" . $row['detail_id'] . "'></td>";
                echo "<td>" . htmlspecialchars($row['detail_id']) . "</td>";
                echo "<td><a href='info_detail.php?detail_id=" . $row['detail_id'] . "&name=" . urlencode($row['name']) . "'>" . htmlspecialchars($row['name']) . "</a></td>";
                echo "<td>" . htmlspecialchars($row['material']) . "</td>";
                echo "<td>" . date('Y-m-d', strtotime($row['date_added'])) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "<div class='button-container'>";
            echo "<button type='submit' name='delete' class='delete-button'>Delete</button>";
            echo "<a href='add_detail.php' class='add-button'>Add</a>";
            echo "<a href='lab3/statistic.php' class='add-button'>Statistic</a>";

            echo "</div>";
            echo "</form>";
        } else {
            echo "Error fetching data";
        }

        mysqli_close($link);
        ?>
    </div>
</body>

</html>


</html>