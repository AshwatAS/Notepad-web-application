// document.addEventListener("DOMContentLoaded", function() {
    // Function to handle hiding and showing frames
    function hidenewnote(button) {
        // Hide the new note form
        document.querySelectorAll('.heading-form').forEach(function(element) {
            element.style.display = 'none';
        });
        document.getElementById("datealert").style.display = "none";

        // Reset button background colors
        document.querySelectorAll(".collapse-navbar-collapse button").forEach(function(btn) {
            btn.style.backgroundColor = "white";
        });

        // Highlight the clicked button
        button.style.backgroundColor = "#00bfff";

        // Get the frame ID from the button ID
        const frameId = button.id.replace("Button", "").toLowerCase();
        console.log("Button clicked:", frameId);

        // Hide all iframes
        document.querySelectorAll(".frames iframe").forEach(function(iframe) {
            iframe.style.display = "none";
        });

        // Show the selected iframe
        const frametodisplay = document.getElementById(frameId);
        if (frametodisplay) {
            frametodisplay.style.display = "block";
            console.log("Displaying frame:", frametodisplay.id);
        } else {
            console.error("No frame found to display for button ID:", frameId);
        }
    }

    // Function to hide all frames
    function hideallframes() {
        document.querySelectorAll(".frames iframe").forEach(function(iframe) {
            iframe.style.display = 'none';
        });
    }

    // Function to show the new note form
    function opennewnote() {
        document.querySelectorAll('.heading-form').forEach(function(element) {
            element.style.display = 'block';
        });
        document.getElementById("datealert").style.display = "block";
        location.reload();

        // Reset button background colors
        document.querySelectorAll(".collapse-navbar-collapse button").forEach(function(button) {
            console.log("color is white")
            button.style.backgroundColor = "white";
        });

        // Hide all frames when opening new note form
        hideallframes();
    }

    // Attach event listeners to buttons
    document.querySelectorAll(".collapse-navbar-collapse button").forEach(function(button) {
        button.addEventListener("click", function() {
            hidenewnote(this);
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("clickable").addEventListener("click", function(event) {
            // Your function to run on click
            opennewnote();
            hideallframes();
        });
    });

    // document.querySelector(".plusButton").addEventListener("click", function() {
    //     opennewnote();
    // });
// });
