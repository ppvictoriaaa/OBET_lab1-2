<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Details</title>
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
            justify-self: center;
        }

        .container2 {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .button-container {
            margin-top: 20px;
            display: flex;
            flex-direction: row;
            justify-content: center;
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
        <?php
        session_start();
        $link = mysqli_connect("localhost:3307", "root", "password", "mytechcard");

        if ($link) {
            $detail_id = intval($_GET['detail_id']);
            $name = htmlspecialchars($_GET['name']);

            //original session for sorting 
            if (!isset($_SESSION['sort_order'])) {
                $_SESSION['sort_order'] = '';
            }

            if (isset($_GET['sort'])) {
                $_SESSION['sort_order'] = $_SESSION['sort_order'] ?? '';

                switch ($_SESSION['sort_order']) {
                    case '':
                        $_SESSION['sort_order'] = 'DESC';
                        break;
                    case 'DESC':
                        $_SESSION['sort_order'] = 'ASC';
                        break;
                    default:
                        $_SESSION['sort_order'] = '';
                        break;
                }
            }

            //deleting selected list
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                if (!empty($_POST['delete_ids'])) {
                    $delete_ids = implode(",", array_map('intval', $_POST['delete_ids']));
                    $delete_query = "DELETE FROM info_detail WHERE id IN ($delete_ids)";
                    mysqli_query($link, $delete_query);
                }
            }

            //query with sorting if added
            $query = "SELECT * FROM info_detail WHERE detail_id = $detail_id";
            if ($_SESSION['sort_order'] != '') {
                $query .= " ORDER BY process_time_min " . $_SESSION['sort_order'];
            }

            $result = mysqli_query($link, $query);

            //editing slected list
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
                if (!empty($_POST['delete_ids'])) {
                    $edit_ids = implode(",", array_map('intval', $_POST['delete_ids']));
                    header("Location: edit_info.php?detail_id=$detail_id&name=" . urlencode($name) . "&ids=$edit_ids");
                    exit();
                }
            }

            echo "<h2>Info Details for $name with Detail ID $detail_id</h2>";
            echo "<form method='POST' action=''>";
            echo "<table>";
            echo "<tr><th>Select</th><th>ID</th><th>Process Type</th>";
            echo "<th><a href='?detail_id=$detail_id&name=" . urlencode($name) . "&sort'>Process Time (min)</a>";

            //showing arrows
            if ($_SESSION['sort_order'] == 'ASC') {
                echo " &#8593;";
            } elseif ($_SESSION['sort_order'] == 'DESC') {
                echo " &#8595;";
            } else {
                echo " &#8593; &#8595;";
            }

            echo "</th><th>Detail ID</th></tr>";

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='delete_ids[]' value='" . $row['id'] . "'></td>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['process_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['process_time_min']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['detail_id']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center;'>No data</td></tr>";
            }

            echo "</table>";
            echo "<div class='container2'>";
            echo "<div class='button-container'>";
            echo "<button type='submit' name='delete' class='delete-button'>Delete</button>";
            echo "<a href='add_info.php?detail_id=" . $detail_id . "&name=" . urlencode($name) . "' class='add-button'>Add</a>";
            echo "<button type='submit' name='edit' class='add-button'>Edit</button>";
            echo "</div>";
            echo  "<a href=\"index.php\" >Back to List</a>";
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