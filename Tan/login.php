<?php
require_once 'core/dbConfig.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  $query = "SELECT * FROM users WHERE username = :username";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];



    if ($_SESSION['role'] == 'hr') {
      header("Location: components/hr_dashboard.php");
    } else {
      header("Location: components/applicant_dashboard.php");
    }
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-start px-4 bg-cover bg-center" style="background-image: url('img/bg.jpg');">
  <div class="bg-gray-800 border border-gray-300 rounded-2xl p-8 shadow-lg w-full max-w-md ml-24">
    <form method="POST" class="space-y-6">
      <!-- Heading -->
      <div class="text-center">
        <h3 class="text-2xl font-bold text-white">Sign in</h3>
        <p class="text-gray-400 text-sm mt-2">Welcome back! Please log in to your account.</p>
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

      <!-- Password -->
      <div>
        <label for="password" class="text-white text-sm font-medium mb-2 block">Password</label>
        <div class="relative">
          <input id="password" name="password" type="password" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
            placeholder="Enter your password" />
        </div>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit"
          class="w-full py-3 px-4 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 focus:outline-none transition duration-200">
          Log in
        </button>
      </div>

      <!-- Register Link -->
      <p class="text-sm text-center text-gray-400">
        Don't have an account? 
        <a href="registration.php" class="text-blue-600 font-semibold hover:underline">Register here</a>
      </p>
    </form>
  </div>
</body>



</html>