<?php
include("database.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap 5 JS Bundle with Popper -->
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
    <script>
        // function deleteNote(note) {
        //     if(confirm("Are you sure you want to delete this file?")) {
        //         $.ajax({
        //             url: 'delete_file.php',
        //             type: 'POST',
        //             data: { note_id: note },
        //             success: function(response) {
        //                 console.log("Server response:", response);
        //                 if(response == "success") {
        //                     alert("File deleted successfully!");
        //                     location.reload(); // Reload the page to reflect changes
        //                 } else {
        //                     alert("Failed to delete file. Please try again.");
        //                 }
        //             }
        //         });
        //     }
        // }
    </script>
</head>
<body>
<?php 
$links = "SELECT * FROM notes WHERE type = 'file'";
$result = mysqli_query($conn, $links);
if (mysqli_num_rows($result) > 0){
    echo '<div class="container">';
    echo '<table class="table table-hover table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Date</th>';
    echo '<th>File Name</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="table-group-divider">';
    while ($row = mysqli_fetch_assoc($result)){
        echo '<tr data-id="' . $row["id"] . '" data-note="' . $row["note"] . '">';
        echo '<td>' . $row["date"] . '</td>';
        echo '<td>' . htmlspecialchars($row["note"]) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo "No files yet!";
}
mysqli_free_result($result);
mysqli_close($conn);
?>
<div class="custom-menu" id="customMenu">
    <a href="#" id="option1">Delete file</a>
    <a href="#" id="option2" type="application/pdf" referrerpolicy="no-referrer">Open file</a>
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
        if (currentRow) {
            const noteId = currentRow.getAttribute('data-id');
            //const noteName = currentRow.getAttribute('data-note');
            if(confirm("Are you sure you want to delete this file?")) {
                $.ajax({
                    url: 'delete_file.php',
                    type: 'POST',
                    data: { note_id: noteId },
                    success: function(response) {
                        if(response == "error"){
                            alert("Failed to delete file. Please try again.");
                        }else{
                            alert("File deleted successfully!");
                            location.reload(); // Reload the page to reflect changes
                        }
                    }
                });
            }
        }
    }

    document.getElementById('option1').addEventListener('click', deleteNote);

    // Update the href of option2 with the note link
    document.getElementById('option2').addEventListener('click', function() {
        if (currentRow) {
        const noteName = currentRow.getAttribute('data-note');
        const fileExtension = noteName.split('.').pop().toLowerCase(); // Get file extension
        
        // Set the correct file path
        this.href = 'uploads/' + noteName;

        // Optional: Handle file types if you want to add specific behavior
        switch(fileExtension) {
            case 'png':
            case 'jpg':
            case 'jpeg':
                // These are image files, the browser can open them directly
                break;
            case 'pdf':
                // PDF files can be opened directly in the browser or downloaded
                break;
            case 'xlsx':
            case 'csv':
                // Excel files might trigger download, depending on the browser setup
                break;
            default:
                alert('Unsupported file type: ' + fileExtension);
                event.preventDefault(); // Prevent opening if the type is unsupported
                break;
        }
    } else {
        alert('No file selected.');
        event.preventDefault(); // Prevent the link from doing anything if no file is selected
    }
        // if (currentRow) {
        //     const noteName = currentRow.getAttribute('data-note');
        //     this.href = 'uploads/' + noteName;
        // }
    });
    //});
</script>
</body>
</html>

<!-- <script>
    window.oncontextmenu = function ()
{
    showCustomMenu();
    return false;     // cancel default menu
}
</script> -->
