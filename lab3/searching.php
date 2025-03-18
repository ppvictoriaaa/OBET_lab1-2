<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            background-color: #2c2f36;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: left;
        }

        h2 {
            text-align: center;
            font-size: 2em;
            color: rgb(198, 64, 98);
        }

        h3 {
            font-size: 1.5em;
            margin-top: 20px;
            text-align: center;

        }

        .search-container {
            text-align: center;
            margin: 20px 0;
        }

        .search-container input {
            padding: 10px;
            font-size: 1.2em;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-container button {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: rgb(163, 31, 77);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .search-container button:hover {
            background-color: rgb(198, 64, 98);
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

            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            font-size: 1.2em;
            margin: 20px;
            color: #f0f0f0;
        }


        .date-range {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #444c53;
            border-radius: 10px;
        }

        .date-range input,
        .date-range button {
            font-size: 1.1em;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
        }

        .date-range input {
            width: 200px;
            border: 1px solid #ccc;
        }

        .date-range button {
            background-color: rgb(163, 31, 77);
            color: white;
            border: none;
            cursor: pointer;
        }

        .date-range button:hover {
            background-color: rgb(198, 64, 98);
        }

        .back_to_list {
            color: #f0f0f0;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>

<?php
$link = mysqli_connect("localhost:3307", "root", "password", "mytechcard");
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// with pattern
$search_query = str_replace('*', '%', $search_query);
$search_query = str_replace('?', '_', $search_query);

// key word
$sql_details_list = "SELECT * FROM details_list WHERE (name LIKE '%$search_query%' OR detail_id LIKE '%$search_query%')";
if ($start_date && $end_date) {
    $sql_details_list .= " AND date_added BETWEEN '$start_date' AND '$end_date'";
}
$result_details_list = mysqli_query($link, $sql_details_list);

$sql_info_detail = "SELECT * FROM info_detail WHERE (process_type LIKE '%$search_query%' OR process_time_min LIKE '%$search_query%' OR detail_id LIKE '%$search_query%')";
if ($start_date && $end_date) {
    $sql_info_detail .= " AND date_added BETWEEN '$start_date' AND '$end_date'";
}
$result_info_detail = mysqli_query($link, $sql_info_detail);
?>

<body>
    <p class="back_to_list"><a href="../index.php">Back to List</a></p>

    <h2>Searching</h2>
    <div class="search-container">
        <form method="POST" action="searching.php">
            <input type="text" name="search_query" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search for details...">
            <button type="submit">Search</button>
        </form>
    </div>

    <h3>Details List</h3>
    <?php
    if ($result_details_list && mysqli_num_rows($result_details_list) > 0) {
        echo "<table><tr><th>ID</th><th>Name</th><th>Material</th><th>Date</th></tr>";
        while ($row = mysqli_fetch_array($result_details_list, MYSQLI_ASSOC)) {
            echo "<tr><td>" . htmlspecialchars($row['detail_id']) . "</td>";
            echo "<td><a href='../info_detail.php?detail_id=" . $row['detail_id'] . "&name=" . urlencode($row['name']) . "'>" . htmlspecialchars($row['name']) . "</a></td>";
            echo "<td>" . htmlspecialchars($row['material']) . "</td>";
            echo "<td>" . date('Y-m-d', strtotime($row['date_added'])) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-data'>No data found in the details list.</p>";
    }
    ?>

    <h3>Info Detail</h3>
    <?php
    if ($result_info_detail && mysqli_num_rows($result_info_detail) > 0) {
        echo "<table><tr><th>ID</th><th>Process Type</th><th>Process Time (min)</th><th>Date</th><th>Detail ID</th><th>Name</th></tr>";
        while ($row = mysqli_fetch_array($result_info_detail, MYSQLI_ASSOC)) {
            $detail_id = $row['detail_id'];
            $sql_name = "SELECT name FROM details_list WHERE detail_id = '$detail_id'";
            $result_name = mysqli_query($link, $sql_name);
            $name_row = mysqli_fetch_array($result_name, MYSQLI_ASSOC);
            $name = $name_row ? $name_row['name'] : 'Unknown';
            echo "<tr><td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['process_type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['process_time_min']) . "</td>";
            echo "<td>" . date('Y-m-d', strtotime($row['date_added'])) . "</td>";
            echo "<td>" . htmlspecialchars($row['detail_id']) . "</td>";
            echo "<td><a href='../info_detail.php?detail_id=" . $row['detail_id'] . "&name=" . urlencode($name) . "'>" . htmlspecialchars($name) . "</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-data'>No data found in the info detail.</p>";
    }
    ?>

    <div class="date-range">
        <h2>Filter by Date Range</h2>
        <form method="POST" action="searching.php">
            <br>
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <button type="submit">Filter</button>
        </form>
    </div>
</body>

</html>