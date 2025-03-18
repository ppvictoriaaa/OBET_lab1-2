<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
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
            font-size: 2em;
        }

        h3 {
            color: rgb(198, 64, 98);
            padding: 10px 0;
            font-size: 1.8em;
        }

        p {
            text-align: left;
            font-size: 1.2em;
            margin: 10px 0;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #3d4349;
            border-radius: 10px;
        }

        a {
            color: #f0f0f0;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #f0f0f0;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #2c2f36;
        }

        .search_btn {
            margin-top: 20px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            background-color: rgb(163, 31, 77);
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php

        $link = mysqli_connect("localhost:3307", "root", "password", "mytechcard");
        if (!$link) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // 1. Number of records in table 1 (details_list)
        $query1 = "SELECT COUNT(*) as total FROM details_list";
        $result1 = mysqli_query($link, $query1);
        $row1 = mysqli_fetch_assoc($result1);
        $total1 = $row1['total'];

        // 2. Number of records in table 2 (info_details)
        $query2 = "SELECT COUNT(*) as total FROM info_detail";
        $result2 = mysqli_query($link, $query2);
        $row2 = mysqli_fetch_assoc($result2);
        $total2 = $row2['total'];

        // 3. Records in the last month in table 1
        $query3 = "SELECT COUNT(*) as recent FROM details_list WHERE DATE(date_added) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        $result3 = mysqli_query($link, $query3);
        $row3 = mysqli_fetch_assoc($result3);
        $recent1 = $row3['recent'];

        // 4. Records in the last month in table 2
        $query4 = "SELECT COUNT(*) as recent FROM info_detail WHERE DATE(date_added) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        $result4 = mysqli_query($link, $query4);
        $row4 = mysqli_fetch_assoc($result4);
        $recent2 = $row4['recent'];

        // 5. Latest record in table 1
        $query5 = "SELECT * FROM details_list ORDER BY date_added DESC LIMIT 1";
        $result5 = mysqli_query($link, $query5);
        $last_title = mysqli_fetch_assoc($result5);

        // 6. Record with the most related entries in table 2
        $query6 = "SELECT detail_id, COUNT(*) as count FROM info_detail GROUP BY detail_id ORDER BY count DESC LIMIT 1";
        $result6 = mysqli_query($link, $query6);
        $most_related = mysqli_fetch_assoc($result6);

        $query7 = "SELECT name FROM details_list WHERE detail_id = '{$most_related['detail_id']}'";
        $result7 = mysqli_query($link, $query7);
        $name_row = mysqli_fetch_assoc($result7);
        $detail_name = $name_row['name'];

        echo "<p class='search_btn'><a href='searching.php'>Search</a></p>";

        echo "<h2>Statistics</h2>";
        echo "<p>Number of records in table 1: $total1</p>";
        echo "<p>Number of records in table 2: $total2</p>";
        echo "<p>Records in the last month in table 1: $recent1</p>";
        echo "<p>Records in the last month in table 2: $recent2</p>";

        echo "<h3>Latest record in table 1</h3>";
        echo "<table>";
        echo "<tr><th>Detail ID</th><th>Name</th><th>Material</th><th>Date</th></tr>";
        echo "<tr><td>{$last_title['detail_id']}</td><td>{$last_title['name']}</td><td>{$last_title['material']}</td><td>" . date("Y-m-d", strtotime($last_title['date_added'])) . "</td></tr>";
        echo "</table>";

        echo "<p>Record in table 1 with the most related records in table 2:</p>";
        echo "<p><a href='../info_detail.php?detail_id={$most_related['detail_id']}&name={$detail_name}'>ID: {$most_related['detail_id']} Name: {$detail_name}</a></p>";
        echo "<p>Count: {$most_related['count']}</p>";

        mysqli_close($link);
        ?>
    </div>
</body>

</html>