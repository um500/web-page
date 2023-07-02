<?php
// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create a new PDO instance
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];

  // Fetch user's health report (PDF file) from the database
  $sql = "SELECT pdf_file FROM users WHERE email = :email";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $result = $stmt->fetch();

  if ($result) {
    $pdf_file = $result['pdf_file'];
    $file_path = "uploads/" . $pdf_file;

    // Send the PDF file to the user
    if (file_exists($file_path)) {
      header('Content-Type: application/pdf');
      header('Content-Disposition: inline; filename="' . $pdf_file . '"');
      readfile($file_path);
    } else {
      echo "File not found.";
    }
  } else {
    echo "User not found.";
  }
}
?>
