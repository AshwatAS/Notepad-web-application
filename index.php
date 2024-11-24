<?php
// adds the database connection
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="java2.js"></script>
    <style>
        .note-text {
            word-wrap: break-word; /* Ensure long words break to the next line in text box*/
        }
        textarea {
            resize: none; /* Prevent manual resizing */
            overflow: hidden; /* Hide scroll bar */
        }
        .navbar {
            padding: 0.5rem 1rem;
        }
        .navbar-brand {
            font-size: 1.25rem;
        }
        .nav-link {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        .plusButton img {
            max-height: 24px;
        }
    </style>
    <script>
        function adjustTextareaHeight(textarea) {
            textarea.style.height = 'auto'; //automatically adjusts the height of the text area or text field
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }

        document.addEventListener('DOMContentLoaded', function() { //checks if all contents of webpage is loaded
            var textarea = document.getElementById('note');
            textarea.addEventListener('input', function() {
                adjustTextareaHeight(textarea);
            });
            adjustTextareaHeight(textarea); // Initial adjustment
        });
        
    </script>
</head>
<body>
    <div class="container mt-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <a class="navbar-brand" id="clickable" href="#">My Notepad</a>
            <div class="collapse-navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button id="LinksButton" class="nav-link btn btn-link" onclick="hidenewnote(this)">Links</button>
                    </li>
                    <li class="nav-item">
                        <button id="NotesButton" class="nav-link btn btn-link" onclick="hidenewnote(this)">Notes</button>
                    </li>
                    <li class="nav-item">
                        <button id="FilesButton" class="nav-link btn btn-link" onclick="hidenewnote(this)">Files</button>
                    </li>
                    <!-- <li class="nav-item">
                        <button id="PicturesButton" class="nav-link btn btn-link" onclick="hidenewnote(this)" disabled>Pictures</button>
                    </li> -->
                </ul>
            </div>
            <button class="btn btn-outline-success plusButton" onclick="opennewnote(); hideallframes()" style="margin-left: 940px;">
                <img src="./images/plus_icon.png" alt="Plus Icon" class="img-fluid">
            </button>
        </nav>
        
        <div class="frames mb-4">
            <iframe id="links" src="linksreport.php" class="w-100" style="display: none; height: 400px;"></iframe>
            <iframe id="notes" src="notesreport.php" class="w-100" style="display: none; height: 400px;"></iframe>
            <iframe id="files" src="filesreport.php" class="w-100" style="display: none; height: 400px;"></iframe>
        </div>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off" enctype="multipart/form-data" class="bg-light p-4 rounded shadow heading-form" id="noteform">
            <div>
                <h1>Create a new note</h1>
                <div class="input-group input-group-lg">
                    <input class="form-control form-control-lg" placeholder="Enter your text here" type="text" name="note" id="note" />
                    <button type="submit" class="btn btn-success" id="save">Save</button>
                </div>
                <style>
                    .form-control:focus {
                        outline: none; 
                        box-shadow: none;
                    }
                </style>
                <br>
                <!-- <div class="form-group"> -->
                    
                    <!-- <input type="text" class="form-control" placeholder="Enter your text here" name="note" id="note"> -->
                <!-- </div> -->
                <div class="form-group">
                    <input type="file" class="form-control-file" id="fileInput" name="fileInput">
                </div>
            </div>
        </form>
    </div>
    <footer>
        <div class="container">
            <div class="alert alert-success alert-dismissible" role="alert" id="datealert">Last saved at: <span id="dateValue"></span>
                <button class="btn-close btn-lg" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </footer>
</body>
</html>
 <script>
document.getElementById("noteform").addEventListener("submit", function(event) {
    //event.preventDefault(); // Prevent form submission to allow asynchronous handling
    const noteinput = document.getElementById("note").value;
    const fileInput = document.getElementById('fileInput');
    const hasFile = fileInput.files.length > 0;
    if (noteinput.trim() === "" && !hasFile) {
        alert("The input is empty. Please enter a note.");
        return; // Exit the function if input is empty
    }
     });

 </script>

<script>
    function update(newdate){
        document.getElementById("dateValue").textContent=newdate;
    }
</script>
 <?php
    function fetchdate($conn){
        $lastdate = "SELECT date FROM notes ORDER BY id DESC LIMIT 1";
        $lastdatequery = mysqli_query($conn, $lastdate);
        if ($lastdatequery) {
            // Fetch the result as an associative array
            
            $row = mysqli_fetch_assoc($lastdatequery);
            // Check if there is a row returned
            if ($row) {
                //echo "<footer>";
                // echo '<div class="container">';
                // echo '<div class="alert alert-success alert-dismissible" role="alert" id="datealert"> Last saved at: ' . htmlspecialchars($row['date']);
                // echo '<button class="btn-close btn-lg" data-bs-dismiss="alert" aria-label="Close"></button>';
                // echo '</div>';
                // echo '</div>';
                echo '<script type="text/javascript">update("' . htmlspecialchars($row['date']) . '");</script>';
                //echo "  " 
                // echo "</footer>";
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
<?php
     fetchdate($conn);
    if ($_SERVER["REQUEST_METHOD"] == "POST" /*&& $_POST['randcheck']==$_SESSION['rand']*/){
        // $lastdate = "SELECT date FROM notes ORDER BY id DESC LIMIT 1";
        // Execute the query
        // $lastdatequery = mysqli_query($conn, $lastdate);
        // Check if the query was successful
        
        if(empty($_POST["note"])){
            echo "";
            //for saving files
            if (isset($_FILES["fileInput"]) && !empty($_FILES["fileInput"]["name"])){
                $file=$_FILES['fileInput'];
                $filename=$_FILES['fileInput']['name'];
                $filetmpname=$_FILES['fileInput']['tmp_name'];
                $fileerror=$_FILES['fileInput']['error'];
                if ($fileerror==0){
                    $filedestination = "uploads/{$filename}";
                    move_uploaded_file($filetmpname,$filedestination);
                    $filearray = explode('.',$filename);
                    $fileext=strtolower(end($filearray));
                    $allowed=array('jpg','jpeg','png','gif');
                    reset($filearray);
                    $note=current($filearray);
                    if (in_array($fileext,$allowed)){
                        $type='file';
                    } else{
                        $type='file';
                    }
                    $insert = "INSERT INTO notes (note,type)
                                VALUES ('$filename','$type')";
                    mysqli_query($conn, $insert);
                    fetchdate($conn);
                } else{
                    echo '<div class="alert alert-danger alert-dismissible" role="alert"> Error uploading file';
                    echo '<button class="btn-close" aria-label="close" data-bs-dismiss="alert"></button>';
                    echo '</div>';
                }
            }
        }
        else{
            if(!empty($_POST["note"])){
                $note = $_POST["note"];
                $urlPattern = '/^(http:\/\/|https:\/\/|www\.)[a-zA-Z0-9-\.]+\.[a-z]{2,}$/';
                $type="note";
                if (preg_match($urlPattern, $note)){
                    $type="link";
                    if (!preg_match('/^https?:\/\//', $note)) {
                        $note = 'http://' . $note; // Add http:// if it doesn't start with http:// or https://
                    }
                }

                $insert = "INSERT INTO notes (note, type) VALUES (?, ?)";
                if ($stmt = $conn->prepare($insert)) {
                    $stmt->bind_param("ss", $note, $type);
                    $stmt->execute();
                    fetchdate($conn);
                    $stmt->close();
                } else {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert"> Connection error';
                    echo '<button class="btn-close" aria-label="close" data-bs-dismiss="alert"></button>';
                    echo '</div>';
                }
            }
        }
    }
    mysqli_close($conn);
?>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
//     chrome.runtime.onMessage.addListener(function(message, sender, sendResponse) {
//     if (message.action === "doSomething") {
//         // Indicate we will send a response asynchronously
//         new Promise((resolve, reject) => {
//             // Simulate async work
//             setTimeout(() => {
//                 resolve("result");
//             }, 1000);
//         }).then(result => {
//             sendResponse({status: "success", data: result});
//         }).catch(error => {
//             console.error(error);
//             sendResponse({status: "error", message: error.message});
//         });
//         return true; // Indicate that we will respond asynchronously
//     }
// });
</script>