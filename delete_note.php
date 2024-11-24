<script>
    function update(newdate){
        document.getElementById("dateValue").textContent=newdate;
    }
</script>
<?php
include("database.php");

if (isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];
    $deleteQuery = "DELETE FROM notes WHERE id = ?";
    if ($stmt = $conn->prepare($deleteQuery)) {
        $stmt->bind_param("i", $note_id);
        if ($stmt->execute()) {
            fetchdate($conn);
            echo "success";
        } else {
            echo "error";
        }
        $stmt->close();
    } else {
        echo "error";
    }
} else {
    echo "error";
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