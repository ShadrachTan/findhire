<?php

require_once '../core/dbConfig.php';

if ($_SESSION['role'] != 'hr') {
  header('Location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $hr_id = $_SESSION['user_id'];

  $sql = "INSERT INTO job_posts (title, description, created_by) VALUES (:title, :description, :created_by)";

  $stmt = $pdo->prepare($sql);

  $stmt->bindParam(':title', $title, PDO::PARAM_STR);
  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
  $stmt->bindParam(':created_by', $hr_id, PDO::PARAM_INT);

  // Execute the statement
  if ($stmt->execute()) {
    header('Location: hr_dashboard.php');
    exit();
  } else {
    echo "Error creating job post";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Job</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 bg-cover bg-center">
  <div class="bg-gray-800 border border-gray-300 rounded-2xl p-8 shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-white text-center mb-6">Add Job</h2>

    <form method="POST" class="space-y-6">
      <div>
        <label for="title" class="text-white text-sm font-medium mb-2 block">Job Title</label>
        <div class="relative">
          <input type="text" name="title" id="title" placeholder="Enter job title"
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
            required>
        </div>
      </div>

      <div>
        <label for="description" class="text-white text-sm font-medium mb-2 block">Job Description</label>
        <div class="relative">
          <textarea name="description" id="description" placeholder="Enter job description"
            class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
            required></textarea>
        </div>
      </div>

      <div class="flex justify-center">
        <button type="submit"
          class="w-full py-3 px-4 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 focus:outline-none transition duration-200">
          Create Job Post
        </button>
      </div>
    </form>
  </div>
</body>

</html>
