<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal PHP Example</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">

    <!-- Buttons to trigger modals -->
    <nav  class="flex justify-center text-center mt-10">
        <div id="profile-button">
            <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Open Enrollment Form
            </button>
        </div>
        <div id="account-button">
            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mt-4">
                Open Another Form
            </button>
        </div>
    </nav>



    <div id="profile" class="content-section hidden text-center">
    <iframe src="enrollment_form.php" class="w-full" style="height: 600px; border: 0;" frameborder="0"></iframe>
</div>

<div id="account" class="content-section hidden">
    <iframe src="select_course.php" class="w-full" style="height: 600px; border: 0;" frameborder="0"></iframe>
</div>




    

</body>
</html>

<script>
    // Function to handle button clicks
function setupButtonClickListener(buttonId, sectionId) {
    document.getElementById(buttonId).addEventListener('click', function() {
        // Hide all content password_reset
        document.querySelectorAll('.content-section').forEach(function(section) {
            section.classList.add('hidden');
        });
        // Show the targeted section
        document.getElementById(sectionId).classList.remove('hidden');
        // Store the active section in localStorage
        localStorage.setItem('activeSection', sectionId);
    });
}

// Function to show the active section based on stored state
function showActiveSection() {
    const activeSection = localStorage.getItem('activeSection');
    if (activeSection) {
        // Hide all password_reset
        document.querySelectorAll('.content-section').forEach(function(section) {
            section.classList.add('hidden');
        });
        // Show the active section
        document.getElementById(activeSection).classList.remove('hidden');
    }
}

// Set up event listeners for all buttons
const buttonSectionMapping = {
    'profile-button': 'profile',
    'account-button': 'account'
};

Object.keys(buttonSectionMapping).forEach(function(buttonId) {
    setupButtonClickListener(buttonId, buttonSectionMapping[buttonId]);
});

// Call the function to show the active section when the page loads
document.addEventListener('DOMContentLoaded', function() {
    showActiveSection();
});

</script>
