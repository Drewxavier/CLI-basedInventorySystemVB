<?php
declare(strict_types=1);

/**
 * Student Course Registration System
 *
 * A CLI-based system demonstrating modern PHP 8.x features and OOP concepts.
 * This system allows managing students, courses, and course registrations.
 *
 * Commands:
 *   add-student <name> <email>    - Add a new student
 *   add-course <code> <title> <capacity> - Add a new course
 *   register <student_id> <course_code> - Register student for course
 *   unregister <student_id> <course_code> - Unregister student from course
 *   list-students               - Show all students
 *   list-courses                - Show all courses
 *   list-registrations          - Show all registrations
 *   student-courses <student_id> - Show courses for a student
 *   course-students <course_code> - Show students in a course
 *   help                        - Display help text
 *   exit                        - Quit the CLI
 *   demo                        - Demonstrate modern PHP 8 features
 */

// Make sure the script is run on CLI
if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "This script is intended to be run from the command line.\n");
    exit(1);
}

interface Registrable
{
    public function getId(): string;
    public function getDisplayName(): string;
}


class Student implements Registrable
{
    private static int $nextId = 1;

    public function __construct(
        private string $name,
        private string $email,
        private int $studentId = 0
    ) {
        if ($this->studentId === 0) {
            $this->studentId = self::$nextId++;
        }
    }

    public function getId(): string
    {
        return (string)$this->studentId;
    }

    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }
}

/**
 * Class Course
 * Represents a course with inheritance from a base class
 */
class Course implements Registrable
{
    public function __construct(
        private string $code,
        private string $title,
        private int $capacity,
        private int $enrolled = 0
    ) {}

    public function getId(): string
    {
        return $this->code;
    }

    public function getDisplayName(): string
    {
        return $this->title;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getEnrolled(): int
    {
        return $this->enrolled;
    }

    public function canEnroll(): bool
    {
        return $this->enrolled < $this->capacity;
    }

    public function enrollStudent(): bool
    {
        if ($this->canEnroll()) {
            $this->enrolled++;
            return true;
        }
        return false;
    }

    public function unenrollStudent(): void
    {
        if ($this->enrolled > 0) {
            $this->enrolled--;
        }
    }
}

/**
 * Class RegistrationManager
 * Manages student-course registrations using modern PHP features
 */
class RegistrationManager
{
    /** @var array<string, Student> */
    private array $students = [];

    /** @var array<string, Course> */
    private array $courses = [];

    /** @var array<string, array<string>> Associative array: student_id => [course_codes] */
    private array $registrations = [];

    public function addStudent(string $name, string $email): Student
    {
        $student = new Student($name, $email);
        $this->students[$student->getId()] = $student;
        return $student;
    }

    public function addCourse(string $code, string $title, int $capacity): Course
    {
        $course = new Course($code, $title, $capacity);
        $this->courses[$code] = $course;
        return $course;
    }

    public function registerStudent(string $studentId, string $courseCode): bool
    {
        $student = $this->students[$studentId] ?? null;
        $course = $this->courses[$courseCode] ?? null;

        if (!$student || !$course) {
            return false;
        }

        if (!$course->canEnroll()) {
            return false;
        }

        // Initialize array if not exists
        $this->registrations[$studentId] ??= [];

        // Check if already registered
        if (in_array($courseCode, $this->registrations[$studentId])) {
            return false;
        }

        $this->registrations[$studentId][] = $courseCode;
        $course->enrollStudent();
        return true;
    }

    public function unregisterStudent(string $studentId, string $courseCode): bool
    {
        if (!isset($this->registrations[$studentId])) {
            return false;
        }

        $index = array_search($courseCode, $this->registrations[$studentId]);
        if ($index === false) {
            return false;
        }

        // Remove from registrations
        unset($this->registrations[$studentId][$index]);
        $this->registrations[$studentId] = array_values($this->registrations[$studentId]);

        // Decrease enrollment
        $course = $this->courses[$courseCode] ?? null;
        if ($course) {
            $course->unenrollStudent();
        }

        return true;
    }

    /**
     * @return Student[]
     */
    public function getAllStudents(): array
    {
        return array_values($this->students);
    }

    /**
     * @return Course[]
     */
    public function getAllCourses(): array
    {
        return array_values($this->courses);
    }

    /**
     * @return array<array{student: Student, course: Course}>
     */
    public function getAllRegistrations(): array
    {
        $result = [];
        foreach ($this->registrations as $studentId => $courseCodes) {
            $student = $this->students[$studentId] ?? null;
            if (!$student) continue;

            foreach ($courseCodes as $courseCode) {
                $course = $this->courses[$courseCode] ?? null;
                if ($course) {
                    $result[] = ['student' => $student, 'course' => $course];
                }
            }
        }
        return $result;
    }

    /**
     * @return Course[]
     */
    public function getStudentCourses(string $studentId): array
    {
        $courses = [];
        $courseCodes = $this->registrations[$studentId] ?? [];

        foreach ($courseCodes as $code) {
            $course = $this->courses[$code] ?? null;
            if ($course) {
                $courses[] = $course;
            }
        }

        return $courses;
    }

    /**
     * @return Student[]
     */
    public function getCourseStudents(string $courseCode): array
    {
        $students = [];

        foreach ($this->registrations as $studentId => $courseCodes) {
            if (in_array($courseCode, $courseCodes)) {
                $student = $this->students[$studentId] ?? null;
                if ($student) {
                    $students[] = $student;
                }
            }
        }

        return $students;
    }
}

/**
 * CLI Interface Class
 * Handles user input and output for the registration system
 */
class CLI
{
    private RegistrationManager $manager;

    public function __construct()
    {
        $this->manager = new RegistrationManager();
    }

    public function run(): void
    {
        echo "Welcome to the Student Course Registration System!\n";
        echo "Type 'help' for available commands or 'demo' for PHP 8 features.\n\n";

        while (true) {
            echo "> ";
            $input = trim(fgets(STDIN));

            if ($input === false) {
                break;
            }

            $parts = explode(' ', $input);
            $command = $parts[0] ?? '';

            try {
                $result = match ($command) {
                    'add-student' => $this->handleAddStudent($parts),
                    'add-course' => $this->handleAddCourse($parts),
                    'register' => $this->handleRegister($parts),
                    'unregister' => $this->handleUnregister($parts),
                    'list-students' => $this->handleListStudents(),
                    'list-courses' => $this->handleListCourses(),
                    'list-registrations' => $this->handleListRegistrations(),
                    'student-courses' => $this->handleStudentCourses($parts),
                    'course-students' => $this->handleCourseStudents($parts),
                    'help' => $this->showHelp(),
                    'demo' => $this->showDemo(),
                    'exit' => $this->handleExit(),
                    default => "Unknown command. Type 'help' for available commands.\n"
                };

                echo $result;
            } catch (Throwable $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }

    private function handleAddStudent(array $parts): string
    {
        if (count($parts) < 3) {
            return "Usage: add-student <name> <email>\n";
        }

        $name = $parts[1];
        $email = $parts[2];

        $student = $this->manager->addStudent($name, $email);
        return "Added student: {$student->getDisplayName()} (ID: {$student->getId()})\n";
    }

    private function handleAddCourse(array $parts): string
    {
        if (count($parts) < 4) {
            return "Usage: add-course <code> <title> <capacity>\n";
        }

        $code = $parts[1];
        $title = $parts[2];
        $capacity = (int)$parts[3];

        $course = $this->manager->addCourse($code, $title, $capacity);
        return "Added course: {$course->getDisplayName()} (Code: {$course->getCode()})\n";
    }

    private function handleRegister(array $parts): string
    {
        if (count($parts) < 3) {
            return "Usage: register <student_id> <course_code>\n";
        }

        $studentId = $parts[1];
        $courseCode = $parts[2];

        $success = $this->manager->registerStudent($studentId, $courseCode);
        return $success
            ? "Student registered successfully!\n"
            : "Registration failed. Check student ID, course code, and capacity.\n";
    }

    private function handleUnregister(array $parts): string
    {
        if (count($parts) < 3) {
            return "Usage: unregister <student_id> <course_code>\n";
        }

        $studentId = $parts[1];
        $courseCode = $parts[2];

        $success = $this->manager->unregisterStudent($studentId, $courseCode);
        return $success
            ? "Student unregistered successfully!\n"
            : "Unregistration failed. Check student ID and course code.\n";
    }

    private function handleListStudents(): string
    {
        $students = $this->manager->getAllStudents();

        if (empty($students)) {
            return "No students registered.\n";
        }

        $output = "Students:\n";
        foreach ($students as $student) {
            $output .= "- ID: {$student->getId()}, Name: {$student->getDisplayName()}, Email: {$student->getEmail()}\n";
        }

        return $output;
    }

    private function handleListCourses(): string
    {
        $courses = $this->manager->getAllCourses();

        if (empty($courses)) {
            return "No courses available.\n";
        }

        $output = "Courses:\n";
        foreach ($courses as $course) {
            $output .= "- Code: {$course->getCode()}, Title: {$course->getDisplayName()}, Capacity: {$course->getCapacity()}, Enrolled: {$course->getEnrolled()}\n";
        }

        return $output;
    }

    private function handleListRegistrations(): string
    {
        $registrations = $this->manager->getAllRegistrations();

        if (empty($registrations)) {
            return "No registrations found.\n";
        }

        $output = "Registrations:\n";
        foreach ($registrations as $reg) {
            $output .= "- {$reg['student']->getDisplayName()} (ID: {$reg['student']->getId()}) -> {$reg['course']->getDisplayName()} (Code: {$reg['course']->getCode()})\n";
        }

        return $output;
    }

    private function handleStudentCourses(array $parts): string
    {
        if (count($parts) < 2) {
            return "Usage: student-courses <student_id>\n";
        }

        $studentId = $parts[1];
        $courses = $this->manager->getStudentCourses($studentId);

        if (empty($courses)) {
            return "No courses found for student ID: $studentId\n";
        }

        $output = "Courses for Student ID $studentId:\n";
        foreach ($courses as $course) {
            $output .= "- {$course->getDisplayName()} (Code: {$course->getCode()})\n";
        }

        return $output;
    }

    private function handleCourseStudents(array $parts): string
    {
        if (count($parts) < 2) {
            return "Usage: course-students <course_code>\n";
        }

        $courseCode = $parts[1];
        $students = $this->manager->getCourseStudents($courseCode);

        if (empty($students)) {
            return "No students enrolled in course: $courseCode\n";
        }

        $output = "Students in Course $courseCode:\n";
        foreach ($students as $student) {
            $output .= "- {$student->getDisplayName()} (ID: {$student->getId()})\n";
        }

        return $output;
    }

    private function showHelp(): string
    {
        return <<<HELP
Available commands:
  add-student <name> <email>           - Add a new student
  add-course <code> <title> <capacity> - Add a new course
  register <student_id> <course_code>  - Register student for course
  unregister <student_id> <course_code> - Unregister student from course
  list-students                       - Show all students
  list-courses                        - Show all courses
  list-registrations                  - Show all registrations
  student-courses <student_id>         - Show courses for a student
  course-students <course_code>        - Show students in a course
  help                                - Display this help text
  demo                                - Demonstrate modern PHP 8 features
  exit                                - Quit the CLI

HELP;
    }

    private function showDemo(): string
    {
        // Demonstrate modern PHP 8 features
        echo "\n=== Modern PHP 8.x Features Demo ===\n\n";

        // 1. Match expressions (PHP 8.0)
        echo "1. Match Expressions:\n";
        $status = 'active';
        $message = match ($status) {
            'active' => 'User is active',
            'inactive' => 'User is inactive',
            default => 'Unknown status'
        };
        echo "   Status: $status -> $message\n\n";

        // 2. Null coalescing assignment (PHP 7.4, but still modern)
        echo "2. Null Coalescing Assignment:\n";
        $data = null;
        $data ??= 'default value';
        echo "   \$data was null, now: $data\n\n";

        // 3. Arrow functions (PHP 7.4)
        echo "3. Arrow Functions:\n";
        $numbers = [1, 2, 3, 4, 5];
        $squares = array_map(fn($n) => $n * $n, $numbers);
        echo "   Numbers: " . implode(', ', $numbers) . "\n";
        echo "   Squares: " . implode(', ', $squares) . "\n\n";

        // 4. Typed properties and strict typing
        echo "4. Strict Typing and Typed Properties:\n";
        echo "   All classes use typed properties with declare(strict_types=1);\n\n";

        // 5. Anonymous functions with closures
        echo "5. Anonymous Functions and Closures:\n";
        $greet = function($name) {
            return "Hello, $name!";
        };
        echo "   " . $greet("World") . "\n\n";

        // 6. Array operations
        echo "6. Array Operations (Associative vs Indexed):\n";
        $indexed = [1, 2, 3, 4, 5];
        $associative = ['name' => 'John', 'age' => 25, 'email' => 'john@example.com'];
        echo "   Indexed array: [" . implode(', ', $indexed) . "]\n";
        echo "   Associative array keys: " . implode(', ', array_keys($associative)) . "\n\n";

        echo "=== Demo Complete ===\n\n";
        return "";
    }

    private function handleExit(): string
    {
        echo "Goodbye!\n";
        exit(0);
    }
}

// Run the CLI application
$cli = new CLI();
$cli->run();

?>
