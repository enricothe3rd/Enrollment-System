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
<nav class="flex flex-col lg:flex-row lg:justify-center items-center mt-10">
    <div id="profile-button" class="mb-4 lg:mb-0 lg:mr-4">
        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Open Enrollment Form
        </button>
    </div>
    <div id="account-button" class="lg:ml-4">
        <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
            Open Select Course Form
        </button>
    </div>
</nav>

<!-- Container to center content -->
<div class="flex justify-center items-top min-h-screen">
    <!-- Profile Section -->
    <div id="profile" class="content-section hidden text-center mt-5 relative w-[70%]">
        <button class="absolute top-3 right-4 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="closeSection('profile')">X</button>
        <iframe src="enrollment_form.php" class="w-full" style="height: 600px; border: 0;" frameborder="0"></iframe>
    </div>

    <!-- Account Section -->
    <div id="account" class="content-section hidden text-center mt-5 relative w-[70%]">
        <button class="absolute top-8 right-12 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="closeSection('account')">X</button>
        <iframe src="select_course.php" class="w-full" style="height: 600px; border: 0;" frameborder="0"></iframe>
    </div>
</div>

<script>
    // Function to handle button clicks
    function setupButtonClickListener(buttonId, sectionId) {
        document.getElementById(buttonId).addEventListener('click', function() {
            // Hide all content sections
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
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(function(section) {
                section.classList.add('hidden');
            });
            // Show the active section
            document.getElementById(activeSection).classList.remove('hidden');
        }
    }

    // Function to close a section
    function closeSection(sectionId) {
        document.getElementById(sectionId).classList.add('hidden');
        // Optionally clear the stored active section
        localStorage.removeItem('activeSection');
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

</body>
</html>
