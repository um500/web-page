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

// Insert user details and PDF file into the database
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $age = $_POST["age"];
  $weight = $_POST["weight"];
  $email = $_POST["email"];

  $pdf_file = $_FILES["report"]["name"];
  $pdf_temp_file = $_FILES["report"]["tmp_name"];

  $target_dir = "uploads/";
  $target_file = $target_dir . basename($pdf_file);

  // Move uploaded file to the target directory
  move_uploaded_file($pdf_temp_file, $target_file);

  // Insert data into the database
  $sql = "INSERT INTO users (name, age, weight, email, pdf_file) VALUES (:name, :age, :weight, :email, :pdf_file)";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':age', $age);
  $stmt->bindParam(':weight', $weight);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':pdf_file', $pdf_file);

  if ($stmt->execute()) {
    echo "Data inserted successfully.";
  } else {
    echo "Error inserting data.";
  }
}
?>
