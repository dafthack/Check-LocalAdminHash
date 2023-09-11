<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for CSRF token validity here
    
    // Ensure the "uploads" directory exists and has appropriate permissions
    $uploadDirectory = 'uploads/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }
    
    if (!empty($_FILES['uploaded_file'])) {
        $uploadedFile = $_FILES['uploaded_file'];
        $targetPath = $uploadDirectory . basename($uploadedFile['name']);
        
        // Perform file type validation (e.g., allow only specific file extensions)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Invalid file type. Allowed types: " . implode(', ', $allowedExtensions);
        } elseif ($uploadedFile['size'] > 10485760) { // 10 MB limit
            echo "File size exceeds the limit of 10 MB.";
        } elseif (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
            echo "The file " . basename($uploadedFile['name']) . " has been uploaded";
        } else {
            echo "There was an error uploading the file, please try again!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <p>Upload your file</p>
    <!-- Add CSRF token here -->
    <input type="hidden" name="csrf_token" value="YOUR_CSRF_TOKEN"></input>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload"></input>
  </form>
</body>
</html>
