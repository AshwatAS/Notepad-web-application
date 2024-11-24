<?php
    include("database.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .note-text {
            word-wrap: break-word; /* Ensure long words break to the next line */
        }
    </style>
    <script>
        function deleteNote(id) {
            if(confirm("Are you sure you want to delete this note?")) {
                $.ajax({
                    url: 'delete_note.php',
                    type: 'POST',
                    data: { note_id: id },
                    success: function(response) {
                        /*if(response == "success") {
                    }*/ if (response == "error") {
                            alert("Failed to delete note. Please try again.");
                            location.reload();
                        } else{
                            alert("Note deleted successfully!");
                            location.reload(); // Reload the page to reflect changes
                        }
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h4 class="mb-4">My Notes</h4>
        <div class="list-group">
        <?php 
            $links = "SELECT * FROM notes WHERE type = 'note'";
            $result = mysqli_query($conn, $links);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="list-group-item">';
                    echo '<h5 class="mb-1">Date: ' . $row["date"] . '</h5>';
                    echo '<p class="mb-1 note-text">Note: ' . $row["note"] . '</p>';
                    echo '<button onclick="deleteNote(' . $row["id"] . ')" class="btn btn-danger btn-sm">Delete</button>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted">No notes yet!</p>';
            }
            mysqli_free_result($result);
            mysqli_close($conn);
        ?>
        </div>
    </div>
</body>
</html>
