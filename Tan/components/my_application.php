<?php
include_once '../core/dbConfig.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$applicant_id = $_SESSION['user_id'];

$sql = "SELECT a.application_id, a.application_status, a.description, a.application_date, a.resume, jp.title AS job_title
        FROM applications a
        JOIN job_posts jp ON a.job_post_id = jp.job_post_id
        WHERE a.applicant_id = :applicant_id
        ORDER BY a.application_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['applicant_id' => $applicant_id]);

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Applications</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: #f7fafc;
      font-family: 'Arial', sans-serif;
    }

    nav {
      background-color: #2d3748;
    }

    nav a {
      color: #edf2f7;
    }

    nav a:hover {
      color: #63b3ed;
    }

    .application-card {
      background-color: #ffffff;
      color: #2d3748;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .application-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .application-card h2 {
      font-size: 1.75rem;
      font-weight: bold;
      color: #2d3748;
    }

    .application-card p {
      color: #4a5568;
    }

    .application-card .meta {
      font-size: 0.875rem;
      color: #718096;
    }

    .no-applications-message {
      text-align: center;
      font-size: 1.25rem;
      color: #718096;
    }

    h1 {
      font-size: 2.5rem;
      color: #2b6cb0;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px 20px;
      text-align: center;
      border: 1px solid #e2e8f0;
    }

    th {
      background-color: #edf2f7;
    }

    td {
      background-color: #ffffff;
    }

    .resume-link {
      color: #3182ce;
      text-decoration: none;
    }

    .resume-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body class="max-w-7xl m-auto">

  <?php include_once 'navbar.php'; ?>

  <div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">My Applications</h1>

    <?php if (empty($applications)): ?>
      <p class="no-applications-message">You have not applied for any jobs yet.</p>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach ($applications as $application): ?>
          <div class="application-card">
            <h2 class="text-2xl"><?php echo htmlspecialchars($application['job_title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($application['description'])); ?></p>
            <p class="meta">Application Status: <?php echo ucfirst(htmlspecialchars($application['application_status'])); ?></p>
            <p class="meta">Applied on: <?php echo date('F j, Y, g:i a', strtotime($application['application_date'])); ?></p>
            <p class="meta">Resume: <a href="<?php echo htmlspecialchars($application['resume']); ?>" class="resume-link" download>Download</a></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>

</body>

</html>
