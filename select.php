<?php
include("db.php"); // ✅ fixed semicolon

// Latest record
$latest = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1");

if ($latest && $latest->num_rows > 0) {
    $latestRow = $latest->fetch_assoc();
} else {
    $latestRow = null;
}

// History records
$history = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 20");
?>

<html>
<head>
    <title>Dashboard</title>

    <style>
        body {
            font-family: Arial;
            background: #0f172a;
            color: white;
            padding: 20px;
        }

        h1 { text-align: center; }

        .cards {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            padding: 20px;
            border-radius: 12px;
            width: 200px;
            text-align: center;
        }

        .cold { background: #3b82f6; }
        .normal { background: #10b981; }
        .hot { background: #ef4444; }
        .humidity { background: #6366f1; }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background: #1e293b;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #334155;
            text-align: center;
        }

        th { background: #334155; }
    </style>
</head>

<body>

<h1>Sensor Dashboard</h1>

<div class="cards">

<?php if ($latestRow): ?>

    <?php
    $temp = $latestRow['temperature'];

    if ($temp < 20) $class = "cold";
    elseif ($temp <= 30) $class = "normal";
    else $class = "hot";
    ?>

    <div class="card <?php echo $class; ?>">
        Temperature<br>
        <strong><?php echo $temp; ?> °C</strong>
    </div>

    <div class="card humidity">
        Humidity<br>
        <strong><?php echo $latestRow['humidity']; ?> %</strong>
    </div>

<?php else: ?>

    <div class="card" style="background:#334155;">
        No data yet
    </div>

<?php endif; ?>

</div>

<h2 style="text-align:center;">History</h2>

<table>
<tr>
    <th>ID</th>
    <th>Temperature</th>
    <th>Humidity</th>
    <th>Time</th>
</tr>

<?php if ($history && $history->num_rows > 0): ?>
    <?php while($row = $history->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['temperature']; ?></td>
        <td><?php echo $row['humidity']; ?></td>
        <td><?php echo $row['time']; ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="4">No data available</td></tr>
<?php endif; ?>

</table>

<script>
setInterval(() => {
    location.reload();
}, 5000);
</script>

</body>
</html>