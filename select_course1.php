<?php
session_start();
require 'db/db_connection1.php';

// Check if session variables are set
if (!isset($_SESSION['student_number']) || !isset($_SESSION['user_role'])) {

    // Redirect to login page or show an error
    header("Location: index.php");
    exit();
}

class User {
    private $pdo;
    private $studentNumber;
    private $userRole;
    private $userId;
    private $schoolYear;
    private $semester;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->studentNumber = $_SESSION['student_number'] ?? null;
        $this->userRole = $_SESSION['user_role'] ?? null;
        $this->fetchUserDetails();
    }

    private function fetchUserDetails() {
        try {
            $stmt = $this->pdo->prepare("SELECT student_id, school_year, semester FROM enrollment WHERE student_number = ?");
            $stmt->execute([$this->studentNumber]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
            if ($user) {
                $this->userId = $user['student_id'];  // Store user ID if needed
                $this->schoolYear = $user['school_year'] ?? 'N/A';
                $this->semester = $user['semester'] ?? 'N/A';
                $_SESSION['user_id'] = $this->userId;
                $_SESSION['school_year'] = $this->schoolYear;
                $_SESSION['semester'] = $this->semester;
            } else {
                $this->userId = null;
                $this->schoolYear = 'N/A';
                $this->semester = 'N/A';
                $_SESSION['user_id'] = null;
                $_SESSION['school_year'] = $this->schoolYear;
                $_SESSION['semester'] = $this->semester;
            }
        } catch (PDOException $e) {
            echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            $this->schoolYear = 'N/A';
            $this->semester = 'N/A';
            $_SESSION['school_year'] = $this->schoolYear;
            $_SESSION['semester'] = $this->semester;
        }
    }
    
    public function getStudentNumber() {
        return $this->studentNumber;
    }

    public function getSchoolYear() {
        return $this->schoolYear;
    }

    public function getSemester() {
        return $this->semester;
    }

    public function getUserId() {
        return $this->userId;
    }
}

// Instantiate User class
$user = new User($pdo);




class ClassSelection {
    private $pdo;
    private $user;
    private $selectedClassId;
    private $sections = [];

    public function __construct($pdo, $user) {
        $this->pdo = $pdo;
        $this->user = $user;
        $this->selectedClassId = $_POST['class_id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_class'])) {
            $this->handleClassSelection();
        }
    }

    private function handleClassSelection() {
        if ($this->selectedClassId) {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM classes WHERE id = ?");
                $stmt->execute([$this->selectedClassId]);
                $class = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($class) {
                    $this->fetchSectionsAndSubjects($class);
                } else {
                    echo '<p class="text-red-500">Class not found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }
    }

    private function fetchSectionsAndSubjects($class) {
        $userSchoolYear = $this->user->getSchoolYear();
        $userSemester = $this->user->getSemester();

        if ($userSchoolYear && $userSemester) {
            $sectionPrefix = $this->getSectionPrefix($userSchoolYear);
            $subjectCodePrefix = $this->getSubjectCodePrefix($userSemester);

            try {
                $stmt = $this->pdo->prepare("
                    SELECT s.id AS subject_id, s.subject_title, s.code, s.units, s.room, s.day, s.start_time, s.end_time,
                           sec.id AS section_id, sec.section_name
                    FROM subjects s
                    JOIN sections sec ON s.section_id = sec.id
                    WHERE sec.class_id = ? AND sec.section_name LIKE ? AND s.code LIKE ?
                ");
                $stmt->execute([$this->selectedClassId, $sectionPrefix . '%', $subjectCodePrefix . '%']);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results as $row) {
                    $sectionId = $row['section_id'];
                    $subjectId = $row['subject_id'];

                    if (!isset($this->sections[$sectionId])) {
                        $this->sections[$sectionId] = [
                            'section_name' => $row['section_name'],
                            'subjects' => []
                        ];
                    }

                    $this->sections[$sectionId]['subjects'][] = [
                        'id' => $subjectId,
                        'subject_title' => $row['subject_title'],
                        'code' => $row['code'],
                        'units' => $row['units'],
                        'room' => $row['room'],
                        'day' => $row['day'],
                        'start_time' => $row['start_time'],
                        'end_time' => $row['end_time']
                    ];
                }
            } catch (PDOException $e) {
                echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }
    }

    private function getSectionPrefix($schoolYear) {
        return match ($schoolYear) {
            '1st Year' => '1',
            '2nd Year' => '2',
            '3rd Year' => '3',
            '4th Year' => '4',
            default => '',
        };
    }

    private function getSubjectCodePrefix($semester) {
        return match ($semester) {
            '1st Semester' => '1',
            '2nd Semester' => '2',
            'Summer' => 'S',
            default => '',
        };
    }

    public function getSections() {
        return $this->sections;
    }

    public function getClasses() {
        $stmt = $this->pdo->query("SELECT * FROM classes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSelectedClassId() {
        return $this->selectedClassId;
    }
}




// Class for handling subject enrollment
class SubjectEnrollment {
    private $pdo;
    private $user;
    private $studentNumber;

    public function __construct($pdo, $user) {
        $this->pdo = $pdo;
        $this->user = $user;
        $this->studentNumber = $user->getStudentNumber();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll'])) {
            $this->handleEnrollment();
        }
    }

    private function handleEnrollment() {
        $selectedSubjects = $_POST['subjects'] ?? [];
        $subjectCodes = $_POST['subject_codes'] ?? [];
        $subjectTitles = $_POST['subject_titles'] ?? [];
        $subjectUnits = $_POST['subject_units'] ?? [];
        $subjectRooms = $_POST['subject_rooms'] ?? [];
        $subjectDays = $_POST['subject_days'] ?? [];
        $subjectStartTimes = $_POST['subject_start_times'] ?? [];
        $subjectEndTimes = $_POST['subject_end_times'] ?? [];
    
        if (!empty($selectedSubjects)) {
            try {
                $this->pdo->beginTransaction();
    
                $stmt = $this->pdo->prepare("INSERT INTO subject_enrollments (student_id, student_number, subject_id, code, title, units, room, day, start_time, end_time, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    
                foreach ($selectedSubjects as $index => $subjectId) {
                    $stmt->execute([
                        $this->user->getUserId(),
                        $this->studentNumber,
                        $subjectId,
                        $subjectCodes[$index] ?? '',
                        $subjectTitles[$index] ?? '',
                        $subjectUnits[$index] ?? '',
                        $subjectRooms[$index] ?? '',
                        $subjectDays[$index] ?? '',
                        $subjectStartTimes[$index] ?? '',
                        $subjectEndTimes[$index] ?? '',
                    ]);
                }
    
                $this->pdo->commit();
                echo '<p class="text-yellow-500">Enrollment pending payment. Please complete payment to finalize enrollment.</p>';
            } catch (PDOException $e) {
                $this->pdo->rollBack();
                echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<p class="text-red-500">No subjects selected.</p>';
        }
    }
    
}

// Initialize the classes
$user = new User($pdo);
$classSelection = new ClassSelection($pdo, $user);
$subjectEnrollment = new SubjectEnrollment($pdo, $user);
$classes = $classSelection->getClasses();
$sections = $classSelection->getSections();

// Use the method to get the selected class ID
$selectedClassId = $classSelection->getSelectedClassId();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Class</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .disabled-section {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.select_all').forEach(function(selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    var sectionId = this.getAttribute('data-section-id');
                    var subjectCheckboxes = document.querySelectorAll('.subject_checkbox[data-section-id="' + sectionId + '"]');
                    var selectAllCheckboxes = document.querySelectorAll('.select_all');
                    var selectAllCheckbox = document.querySelector('.select_all[data-section-id="' + sectionId + '"]');
                    
                    subjectCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });

                    if (selectAllCheckbox.checked) {
                        document.querySelectorAll('.section-container').forEach(function(container) {
                            if (container.getAttribute('data-section-id') !== sectionId) {
                                container.classList.add('disabled-section');
                            }
                        });
                    } else {
                        document.querySelectorAll('.section-container').forEach(function(container) {
                            container.classList.remove('disabled-section');
                        });
                    }
                });
            });
        });
    </script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <p class="text-lg font-semibold">Student Number: <?php echo htmlspecialchars($user->getStudentNumber()); ?></p>

        <h1 class="text-2xl font-bold mb-4">Select a Class</h1>

        <!-- Class Selection Form -->
        <form method="POST" class="px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="class_id">Choose Class</label>
                <select name="class_id" id="class_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select a class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo htmlspecialchars($class['id']); ?>" <?php echo $selectedClassId == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" name="select_class" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Select Class</button>
            </div>
        </form>

        <?php if ($selectedClassId && $sections): ?>
            <!-- Display Sections and Subjects -->
            <form id="enroll_form" method="POST" class="bg-white shadow-md rounded p-4 mt-4">
                <h2 class="text-xl font-semibold mb-4">Sections and Subjects for Selected Class</h2>
                <?php foreach ($sections as $sectionId => $section): ?>
                    <div class="section-container mb-4" data-section-id="<?php echo htmlspecialchars($sectionId); ?>">
                        <h3 class="text-lg font-semibold">
                            Section: <?php echo htmlspecialchars($section['section_name']); ?>
                            <input type="checkbox" class="select_all" data-section-id="<?php echo htmlspecialchars($sectionId); ?>"> Select All
                        </h3>
                        <table class="min-w-full bg-white mt-2">
                            <thead>
                                <tr>
                                    <th class="py-2">Select</th>
                                    <th class="py-2">Code</th>
                                    <th class="py-2">Subject Title</th>
                                    <th class="py-2">Units</th>
                                    <th class="py-2">Room</th>
                                    <th class="py-2">Day</th>
                                    <th class="py-2">Start Time</th>
                                    <th class="py-2">End Time</th>
                                    <th class="py-2">Enrolled Students</th>
                                </tr>
                            </thead>
                            <tbody>
<?php foreach ($section['subjects'] as $subject): ?>
    <tr>
        <td class="border px-4 py-2">
            <input type="checkbox" name="subjects[]" value="<?php echo htmlspecialchars($subject['id'] ?? ''); ?>" class="subject_checkbox" data-section-id="<?php echo htmlspecialchars($sectionId ?? ''); ?>">
        </td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['code'] ?? ''); ?></td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['subject_title'] ?? ''); ?></td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['units'] ?? ''); ?></td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['room'] ?? ''); ?></td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['day'] ?? ''); ?></td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['start_time'] ?? ''); ?></td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['end_time'] ?? ''); ?></td>
        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['enrollment_count'] ?? ''); ?></td>

        <!-- Hidden inputs for subject details -->
        <input type="hidden" name="subject_codes[]" value="<?php echo htmlspecialchars($subject['code'] ?? ''); ?>">
        <input type="hidden" name="subject_titles[]" value="<?php echo htmlspecialchars($subject['subject_title'] ?? ''); ?>">
        <input type="hidden" name="subject_units[]" value="<?php echo htmlspecialchars($subject['units'] ?? ''); ?>">
        <input type="hidden" name="subject_rooms[]" value="<?php echo htmlspecialchars($subject['room'] ?? ''); ?>">
        <input type="hidden" name="subject_days[]" value="<?php echo htmlspecialchars($subject['day'] ?? ''); ?>">
        <input type="hidden" name="subject_start_times[]" value="<?php echo htmlspecialchars($subject['start_time'] ?? ''); ?>">
        <input type="hidden" name="subject_end_times[]" value="<?php echo htmlspecialchars($subject['end_time'] ?? ''); ?>">
        <input type="hidden" name="subject_enrollment_counts[]" value="<?php echo htmlspecialchars($subject['enrollment_count'] ?? ''); ?>">
    </tr>
<?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                <div class="flex items-center justify-between">
                    <button type="submit" name="enroll" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enroll</button>
                    <button type="button" id="reset_button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" disabled>Reset</button>
                </div>
            </form>
        <?php endif; ?>

    </div>
</body>
</html>
