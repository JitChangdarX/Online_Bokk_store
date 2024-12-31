<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validate input fields
  $_SESSION['firstname'] = htmlspecialchars($_POST['firstname']);
  $_SESSION['lastname'] = htmlspecialchars($_POST['lastname']);
  $_SESSION['gender'] = htmlspecialchars($_POST['gender']);
  $_SESSION['language'] = isset($_POST['language']) ? $_POST['language'] : [];

  // Validate file input name
  if (!isset($_FILES['profile_pic'])) {
    echo "File input name does not match. Please check the HTML form.";
    exit;
  }

  // Check if file is uploaded
  $file = $_FILES['profile_pic'];
  if (empty($file['name'])) {
    echo "Please upload a file.";
    exit;
  }

  // Validate file type
  $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
  if (!in_array($file['type'], $allowedTypes)) {
    ?>
    <script>
      alert("Invalid file type. Only JPG, JPEG, PNG, GIF, and PDF files are allowed.");
    </script>
    <?php
    exit;
  }

  // Create upload directory
  $uploadDir = 'uploads/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  // Generate unique file name and save file
  $fileName = uniqid() . "_" . basename($file['name']);
  $filePath = $uploadDir . $fileName;

  if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    echo "Failed to upload file.";
    exit;
  }

  // Store file path in session
  $_SESSION['profile_pic'] = $filePath;

  // Redirect to the next page
  header("Location: page2.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign-Up Page</title>
  <link rel="stylesheet" href="page1.css">
</head>

<body>
  <nav>
    <div class="logo">
      <a href="/" style="color: white; font-size: 24px; font-weight: bold; text-decoration: none;">Bookstore</a>
    </div>
    <ul>
      <li><a href="/home/index.php">Home</a></li>
      <li><a href="/login">Login</a></li>
      <li><a href="/help">Help</a></li>
    </ul>
  </nav>

  <div class="container">
    <h2>Sign Up</h2>
    <form action="" method="post" enctype="multipart/form-data">
      <label for="firstname">First Name:</label>
      <input type="text" id="firstname" name="firstname" required>
      <br><br>

      <label for="lastname">Last Name:</label>
      <input type="text" id="lastname" name="lastname" required>
      <br><br>

      <label for="gender">Gender:</label>
      <select id="gender" name="gender" required>
        <option value="">Selact</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
        <option value="prefer-not-to-say">Prefer Not to Say</option>
      </select>
      <br><br>

      <!-- Language -->
      <label for="language">Languages Known:</label><br>

      <div class="language-option">
        <input type="checkbox" id="english" name="language[]" value="English">
        <label for="english">English</label>
      </div>

      <div class="language-option">
        <input type="checkbox" id="spanish" name="language[]" value="Spanish">
        <label for="spanish">Spanish</label>
      </div>

      <div class="language-option">
        <input type="checkbox" id="french" name="language[]" value="French">
        <label for="french">French</label>
      </div>

      <div class="language-option">
        <input type="checkbox" id="other" name="language[]" value="Other">
        <label for="other">Other</label>
      </div>

      <!-- File Upload -->
      <label for="profile_pic">Upload Profile Picture:</label>
      <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>
      <br><br>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-outline-primary">Next</button>
    </form>
  </div>
</body>

</html>