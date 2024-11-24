<script>
    function update(newdate){
        document.getElementById("dateValue").textContent=newdate;
    }
</script>
<?php
include("database.php");

if (isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];
    // echo $note_id;
    // First, fetch the note name from the database using the provided note_id
    $note_name_query = "SELECT note FROM notes WHERE id = ?";
    if ($stmt = $conn->prepare($note_name_query)) {
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $stmt->bind_result($note_name);
        $stmt->fetch();
        $stmt->close();
        if (!$note_name) {
            echo "error: note_name not found for note_id: " . $note_id;
            exit();
        } else {
            //echo "debug: note_name fetched: " . $note_name . "\n";
            $deleteQuery = "DELETE FROM notes WHERE id = ?";
            if ($stmt = $conn->prepare($deleteQuery)) {
                $stmt->bind_param("i", $note_id);
                if ($stmt->execute()) {
                    // Construct the file path and attempt to delete the file
                    $file_path = "uploads/{$note_name}";
                    if (file_exists($file_path)) {
                        if (unlink($file_path)) {
                            // Call fetchdate function after successful file deletion
                            fetchdate($conn);
                            echo "success";
                        } else {
                            echo "error: unable to delete file";
                        }
                    } else {
                        echo "error: file does not exist";
                    }
                } else {
                    echo "error: unable to delete database record";
                }
                $stmt->close();
            } else {
                echo "error: unable to prepare delete query";
            }
        }
        // Proceed only if the note_name was successfully fetched
         //if ($note_name) {

        // } else {
        //     echo "error: note_name not found";
        // }
    } else {
        echo "error: unable to prepare select query";
    }
} else {
    echo "error: no note_id provided";
}

$conn->close();
function fetchdate($conn){
    $lastdate = "SELECT date FROM notes ORDER BY id DESC LIMIT 1";
    $lastdatequery = mysqli_query($conn, $lastdate);
    if ($lastdatequery) {
        $row = mysqli_fetch_assoc($lastdatequery);
        // Check if there is a row returned
        if ($row) {
            echo '<script type="text/javascript">update("' . htmlspecialchars($row['date']) . '");</script>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible" role="alert"> No data found';
            echo '<button class="btn-close" aria-label="close" data-bs-dismiss="alert"></button>';
            echo '</div>';
        }
        // Free the result set
        mysqli_free_result($lastdatequery);
    } else {
        // Handle query failure
        echo '<div class="alert alert-danger alert-dismissible" role="alert"> Query failed';
        echo '<button class="btn-close" aria-label="close" data-bs-dismiss="alert"></button>';
        echo '</div>';
    }
}
?>
