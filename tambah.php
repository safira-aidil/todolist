<?php
include 'db_todolist.php';


$task = $_POST['task'];
$due_date = $_POST['due_date'];
$reminder_days_before = $_POST['reminder_days_before'];
$mata_kuliah = $_POST['mata_kuliah']; 


$stmt = $conn->prepare("INSERT INTO tasks (Task, due_date, status, reminder_days_before, Mata_Kuliah)
                        VALUES (?, ?, 0, ?, ?)");

$stmt->bind_param("ssis", $task, $due_date, $reminder_days_before, $mata_kuliah);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Gagal menambahkan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
