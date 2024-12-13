<?php
include_once '../core/dbConfig.php';

if ($_SESSION['role'] != 'applicant') {
  header('Location: index.php');
  exit();
}

$sql = "SELECT jp.job_post_id, jp.title, jp.description, jp.created_at, u.username, u.email
        FROM job_posts jp 
        INNER JOIN users u ON jp.created_by = u.user_id 
        ORDER BY jp.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
  $jobPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $jobPosts = [];
}

// Handle job application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) {
  $job_post_id = $_POST['job_post_id'];
  $applicant_id = $_SESSION['user_id'];

  // Insert the application into the database
  $applySql = "INSERT INTO applications (job_post_id, applicant_id) VALUES (?, ?)";
  $applyStmt = $pdo->prepare($applySql);
  $applyStmt->execute([$job_post_id, $applicant_id]);

  // Redirect to avoid reapplying on refresh
  header("Location: applicant_dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applicant Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Updated background for a professional appearance */
    body {
      background: #f7fafc; /* Light gray background */
      font-family: 'Arial', sans-serif;
    }

    /* Navbar customizations for a cleaner look */
    nav {
      background-color: #2d3748; /* Darker, but subtle navbar */
    }

    nav a {
      color: #edf2f7; /* Light navbar text */
    }
    nav a:hover {
      color: #63b3ed; /* Light blue hover effect */
    }

    /* Job Post Card Styles */
    .job-post {
      background-color: #ffffff; /* White background for job posts */
      color: #2d3748; /* Dark text for readability */
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    /* Hover effect for job posts */
    .job-post:hover {
      transform: translateY(-5px); /* Slightly raise the card */
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Text customizations for job posts */
    .job-post h2 {
      font-size: 1.75rem;
      font-weight: bold;
      color: #2d3748;
    }

    .job-post p {
      color: #4a5568; /* Slightly lighter text color */
    }

    .job-post .meta {
      font-size: 0.875rem;
      color: #718096; /* Light gray meta text */
    }

    /* No job posts message */
    .no-posts-message {
      text-align: center;
      font-size: 1.25rem;
      color: #718096; /* Light gray */
    }

    /* Header and Titles */
    h1 {
      font-size: 2.5rem;
      color: #2b6cb0; /* Professional blue color for the main title */
      font-weight: bold;
    }

    /* Apply Button Customization */
    .apply-button {
      background-color: #3182ce; /* Blue background */
      color: white;
      padding: 10px 20px;
      font-size: 1rem;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .apply-button:hover {
      background-color: #63b3ed; /* Light blue hover effect */
    }
  </style>
</head>

<body class="max-w-7xl m-auto">

  <?php include_once 'navbar.php'; ?>

  <div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">Job Posts</h1>

    <?php if (!empty($jobPosts)): ?>
      <div class="space-y-4">
        <?php foreach ($jobPosts as $post): ?>
          <div class="job-post">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
            <p class="meta">Posted by: <?php echo htmlspecialchars($post['username']); ?></p>
            <p class="meta">Posted on: <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></p>
            <p class="meta">Contact: <?php echo htmlspecialchars($post['email']); ?></p>

            <!-- Apply Button -->
            <form method="POST">
              <input type="hidden" name="job_post_id" value="<?php echo $post['job_post_id']; ?>">
              <button type="submit" name="apply" class="apply-button mt-4">Apply for Job</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="no-posts-message">No job posts available at the moment.</p>
    <?php endif; ?>

  </div>

</body>

</html>
