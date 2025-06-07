<?php include 'db_todolist.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
     <style>
        body {
    font-family: 'Quicksand', sans-serif;
    background: linear-gradient(to right, #e6ecf0, #dfe6ed);
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    padding: 25px;
    margin: 0;
    color: white;
    background: linear-gradient(to right, #2c3e50, #34495e);
    font-size: 32px;
    border-radius: 0 0 30px 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.container {
    background: #ffffff;
    max-width: 950px;
    margin: 40px auto;
    padding: 35px 40px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

form {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr 2fr;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

form label {
    font-weight: 700;
    font-size: 16px;
    color: #2c3e50;
    text-align: right;
}

form input[type="text"],
form input[type="date"],
form select {
    padding: 12px;
    border: 1.5px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    width: 100%;
    background-color: #f9fafb;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.04);
}

form button[type="submit"] {
    grid-column: span 4;
    background: #3498db;
    color: white;
    padding: 12px 25px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    justify-self: end;
}

form button[type="submit"]:hover {
    transform: scale(1.05);
    background: #2980b9;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 15px;
    border-radius: 12px;
    overflow: hidden;
}

th {
    background-color: #ecf0f1;
    padding: 14px;
    color: #2c3e50;
}

td {
    padding: 12px;
    text-align: center;
    background-color: #ffffff;
    color: #2c3e50;
}

tr:nth-child(even) {
    background-color: #f4f7f9;
}

.selesai {
    background-color:rgb(22, 212, 70);
    border-radius: 10px;
    padding: 5px 10px;
    color:rgb(255, 255, 255);
    font-weight: bold;
}

.belum {
    background-color:rgb(255, 184, 77);
    border-radius: 10px;
    padding: 5px 10px;
    color:rgb(255, 245, 236);
    font-weight: bold;
}

.aksi-btn {
    padding: 8px 14px;
    margin: 2px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    color: white;
    font-weight: bold;
}

.btn-selesai {
    background-color:rgb(4, 163, 71);
}

.btn-selesai:hover {
    background-color:rgb(20, 185, 89);
}

.btn-hapus {
    background-color:rgb(226, 60, 42);
}

.btn-hapus:hover {
    background-color: #c0392b;
}

.reminder-notification {
    background-color:rgb(255, 212, 57);
    color: #7d6608;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
    border: 1px solid #f9e79f;
}

.reminder-notification.urgent {
    background-color:rgb(238, 26, 2);
    color: white;
    border: none;
}

    </style>
</head>
<body>

<h2>To-Do List</h2>

<div class="container">
<?php
$today = new DateTime();
$reminder_sql = "SELECT id, Task, due_date, reminder_days_before, status FROM tasks WHERE status = 0";
$reminder_result = $conn->query($reminder_sql);
$show_reminder = false;

if ($reminder_result && $reminder_result->num_rows > 0) {
    while($reminder_row = $reminder_result->fetch_assoc()) {
        $due_date_obj = new DateTime($reminder_row['due_date']);
        $reminder_days = (int)$reminder_row['reminder_days_before'];

        if ($reminder_days > 0) {
            $reminder_date_obj = clone $due_date_obj;
            $reminder_date_obj->modify("-{$reminder_days} days");

            if ($today >= $reminder_date_obj && $today <= $due_date_obj) {
                $remaining_days = $today->diff($due_date_obj)->days;
                $urgency_class = ($remaining_days <= 1) ? "urgent" : "";
                $show_reminder = true;

                echo "<div class='reminder-notification {$urgency_class}'>";
                echo "Pengingat untuk: <strong>" . htmlspecialchars($reminder_row['Task']) . "</strong>. ";
                echo "Deadline pada: " . $due_date_obj->format('d M Y') . ". ";
                if ($remaining_days == 0) {
                    echo "Tugas ini deadline <strong>hari ini</strong>!";
                } elseif ($remaining_days == 1) {
                    echo "Tugas ini deadline <strong>besok</strong>!";
                } else {
                    echo "Tersisa <strong>{$remaining_days}</strong> hari lagi.";
                }
                echo "</div>";
            }
        }
    }
}

if (!$show_reminder) {
    echo "<div class='reminder-notification'>Tidak ada tugas yang mendekati deadline.</div>";
}
?>

<form method="POST" action="tambah.php">
    <label for="task">Nama Tugas:</label>
    <input type="text" name="task" id="task" required>

    <label for="due_date">Tanggal Deadline:</label>
    <input type="date" name="due_date" id="due_date" required>

    <label for="reminder">Pengingat:</label>
    <select name="reminder_days_before" id="reminder">
        <option value="0">Tidak Ada Pengingat</option>
        <option value="1">1 Hari Sebelumnya</option>
        <option value="2">2 Hari Sebelumnya</option>
        <option value="3">3 Hari Sebelumnya</option>
        <option value="7">7 Hari Sebelumnya</option>
    </select>

    <label for="mata_kuliah">Mata Kuliah:</label>
    <input type="text" name="mata_kuliah" id="mata_kuliah" required>

    <button type="submit">Tambah</button>
</form>

<table>
    <tr>
        <th>No</th>
        <th>Task</th>
        <th>Mata Kuliah</th>
        <th>Tanggal Deadline</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    $result = $conn->query("SELECT id, Task, mata_kuliah, due_date, status FROM tasks ORDER BY due_date ASC");

    while($row = $result->fetch_assoc()) {
        $statusText = $row['status'] ? "Selesai" : "Belum Selesai";
        $statusClass = $row['status'] ? "selesai" : "belum";

        echo "<tr>
            <td>".$no++."</td>
            <td>{$row['Task']}</td>
            <td>{$row['mata_kuliah']}</td>
            <td>{$row['due_date']}</td>
            <td class='$statusClass'>$statusText</td>
            <td>";

        if ($row['status'] == 0) {
            echo "<a href='selesai.php?id={$row['id']}'><button class='aksi-btn btn-selesai'>✔ Selesai</button></a>";
        }

        echo "<a href='hapus.php?id={$row['id']}'><button class='aksi-btn btn-hapus'>🗑 Hapus</button></a>
            </td>
        </tr>";
    }
    ?>
</table>
</div>
</body>
</html>
