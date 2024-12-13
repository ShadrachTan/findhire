<?php 
require_once 'core/dbConfig.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $email = $_POST['email'];
  $role = $_POST['role']; 

  if ($password !== $confirm_password) {
    $error = "Passwords do not match!";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashed_password, $role, $email]);

    if ($stmt) {
      header("Location: login.php");
      exit();
    } else {
      $error = "An error occurred while registering.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-start px-4 bg-cover bg-center" style="background-image: url('img/bg.jpg');">
  <div class="bg-gray-800 border border-gray-300 rounded-2xl p-8 shadow-lg w-full max-w-md ml-24">
    <form action="registration.php" method="POST" class="space-y-6">
      <!-- Heading -->
      <div class="text-center">
        <h3 class="text-2xl font-bold text-white">Create an Account</h3>
        <p class="text-gray-400 text-sm mt-2">Welcome! Please fill in the details to register.</p>
      </div>

      <!-- Error Message -->
      <?php if (isset($error) && $error != "") {
        echo "<p class='text-red-500 text-sm text-center'>$error</p>";
      } ?>

      <!-- Username -->
      <div>
        <label for="username" class="text-white text-sm font-medium mb-2 block">Username</label>
        <div class="relative">
          <input id="username" name="username" type="text" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
            placeholder="Enter your username" />
        </div>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="text-white text-sm font-medium mb-2 block">Email</label>
        <div class="relative">
          <input id="email" name="email" type="email" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
            placeholder="Enter your email" />
        </div>
      </div>

      <!-- Role -->
      <div>
        <label for="role" class="text-white text-sm font-medium mb-2 block">Role</label>
        <div class="relative">
          <select id="role" name="role" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200">
            <option value="applicant">Applicant</option>
            <option value="hr">HR</option>
          </select>
        </div>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="text-white text-sm font-medium mb-2 block">Password</label>
        <div class="relative">
          <input id="password" name="password" type="password" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
            placeholder="Enter your password" />
        </div>
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="confirm_password" class="text-white text-sm font-medium mb-2 block">Confirm Password</label>
        <div class="relative">
          <input id="confirm_password" name="confirm_password" type="password" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
            placeholder="Confirm your password" />
        </div>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit"
          class="w-full py-3 px-4 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 focus:outline-none transition duration-200">
          Register
        </button>
      </div>

      <!-- Login Link -->
      <p class="text-sm text-center text-gray-400">
        Already have an account? 
        <a href="login.php" class="text-blue-600 font-semibold hover:underline">Login here</a>
      </p>
    </form>
  </div>
</body>

</html>
