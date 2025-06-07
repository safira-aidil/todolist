 <?php
 include 'db_todolist.php';
        
        $today = new DateTime(); 

        
        $reminder_sql = "SELECT id, Task, due_date, reminder_days_before, status FROM tasks WHERE status = 0";
        $reminder_result = $conn->query($reminder_sql);

        if ($reminder_result && $reminder_result->num_rows > 0) {
            while($reminder_row = $reminder_result->fetch_assoc()) {
                $due_date_obj = new DateTime($reminder_row['due_date']);
                $reminder_days = (int)$reminder_row['reminder_days_before'];

                
                if ($reminder_days > 0) {
                    
                    $reminder_date_obj = clone $due_date_obj;
                    $reminder_date_obj->modify("-{$reminder_days} days");

                    
                    if ($today >= $reminder_date_obj && $today <= $due_date_obj) {
                        $remaining_days = $today->diff($due_date_obj)->days;
                        $urgency_class = "";
                        if ($remaining_days <= 1) { 
                            $urgency_class = "urgent";
                        }
                        
                        echo "<div class='reminder-notification {$urgency_class}'>";
                        echo "Pengingat untuk: <strong>" . htmlspecialchars($reminder_row['Task']) . "</strong>. ";
                        echo "Deadline pada: " . $due_date_obj->format('d M Y') . ". ";
                        if ($remaining_days == 0) {
                            echo "Tugas ini deadline hari ini!";
                        } elseif ($remaining_days == 1) {
                            echo "Tugas ini deadline besok!";
                        } else {
                            echo "Tersisa " . $remaining_days . " hari lagi.";
                        }
                        echo "</div>";
                    }
                }
            }
        }
        ?>
