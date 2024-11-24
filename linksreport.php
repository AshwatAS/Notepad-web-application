<?php
include("database.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .custom-menu {
            display: none;
            position: absolute;
            z-index: 1000;
            width: 150px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
        .custom-menu a {
            display: block;
            padding: 8px 12px;
            color: black;
            text-decoration: none;
        }
        .custom-menu a:hover {
            background-color: #f1f1f1;
        }
    </style>
    <!-- <script>
        function deleteNote(id) {
            if (confirm("Are you sure you want to delete this note?")) {
                $.ajax({
                    url: 'delete_note.php',
                    type: 'POST',
                    data: { note_id: id },
                    success: function(response) {
                        if (response == "success") {
                            alert("Note deleted successfully!");
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Failed to delete note. Please try again.");
                        }
                    }
                });
            }
        }
    </script> -->
</head>
<body>
    <!-- <div class="container">
         <h4 class="mb-4">My Links</h4>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody class="table-group-divider"> -->
        <?php 
            $links = "SELECT * FROM notes WHERE type = 'link'";
            $result = mysqli_query($conn, $links);
            if (mysqli_num_rows($result) > 0) {
                //$linksarray=array();
                echo '<div class="container">';
                echo '<table class="table table-hover table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Date</th>';
                echo '<th>File Name</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody class="table-group-divider">';
                while ($row = mysqli_fetch_assoc($result)) {
                    //echo '<div class="list-group-item">';
                    echo '<tr data-id="' . $row["id"] . '" data-note="' . $row["note"] . '">';
                    echo '<td>' . $row["date"] . '</td>';
                    // echo '<h5 class="mb-1">Date: ' . $row["date"] . '</h5>';
                    echo '<td><a href="' . htmlspecialchars($row["note"], ENT_QUOTES, 'UTF-8') . '" target="_blank">' . htmlspecialchars($row["note"], ENT_QUOTES, 'UTF-8') . '</a></td>';
                    array_push($linksarray,$row["note"]);
                    echo '</tr>';
                    //echo '<button onclick="deleteNote(' . $row["id"] . ')" class="btn btn-danger btn-sm">Delete</button>';
                    //echo '</div>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p class="text-muted">No links yet!</p>';
            }
            mysqli_free_result($result);
            mysqli_close($conn);
        ?>
            <!-- </tbody>
        </table>
    </div> -->
    <div class="custom-menu" id="customMenu">
        <a href="#" id="option1">Delete link</a>
        <a href="#" id="option2" target="_blank">Open link</a>
    </div>

    <script>
        //document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('table tbody tr');
        const customMenu = document.getElementById('customMenu');
        let currentRow = null;

        tableRows.forEach(row => {
            row.addEventListener('contextmenu', function(event) {
                event.preventDefault();
                currentRow = row;
                showCustomMenu(event);
            });
        });

        document.addEventListener('click', function() {
            hideCustomMenu();
        });

        customMenu.addEventListener('click', function(event) {
            hideCustomMenu();
        });

        function showCustomMenu(event) {
            customMenu.style.display = 'block';
            customMenu.style.left = event.pageX + 'px';
            customMenu.style.top = event.pageY + 'px';
        }

        function hideCustomMenu() {
            customMenu.style.display = 'none';
        }
        function deleteNote() {
        // if (currentRow) {
            const noteId = currentRow.getAttribute('data-id');
            const noteName = currentRow.getAttribute('data-note');
            if(confirm("Are you sure you want to delete this link?")) {
                $.ajax({
                    url: 'delete_note.php',
                    type: 'POST',
                    data: { note_id: noteId},
                    success: function(response) {
                        if(response == "error"){
                            alert("Failed to delete link. Please try again.");
                        }else{
                            alert("Links deleted successfully!");
                            location.reload(); // Reload the page to reflect changes
                        }
                        // if(response == "success") {
                        //     alert("File deleted successfully!");
                        //     location.reload(); // Reload the page to reflect changes
                        // } else {
                        //     alert("Failed to delete link. Please try again.");
                        // }
                    }
                });
            }
        //}
    }

    document.getElementById('option1').addEventListener('click', deleteNote);

    // Update the href of option2 with the note link
    document.getElementById('option2').addEventListener('click', function() {
        if (currentRow) {
            const noteName = currentRow.getAttribute('data-note');
            this.href = noteName;
        }
    });
    //});
</script>
</body>
</html>


