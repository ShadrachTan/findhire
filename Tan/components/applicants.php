<?php
include_once '../core/dbConfig.php';

$statuses = ['pending', 'accepted', 'rejected'];

$applications = [];
foreach ($statuses as $status) {
  $sql = "SELECT a.application_id, a.applicant_id, a.job_post_id, a.description, a.application_status, a.application_date, a.resume, u.username, jp.title 
            FROM applications a 
            INNER JOIN users u ON a.applicant_id = u.user_id 
            INNER JOIN job_posts jp ON a.job_post_id = jp.job_post_id 
            WHERE a.application_status = :status
            ORDER BY a.application_date DESC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['status' => $status]);

  if ($stmt->rowCount() > 0) {
    $applications[$status] = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $applications[$status] = [];
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
  $application_id = $_POST['application_id'];
  $action = $_POST['action'];

  $updateSql = "UPDATE applications SET application_status = :status WHERE application_id = :application_id";
  $updateStmt = $pdo->prepare($updateSql);
  $updateStmt->execute(['status' => $action, 'application_id' => $application_id]);

  header('Location: applicants.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applications</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* General Styling */
    body {
      background: #f7fafc; /* Light background color */
      font-family: 'Arial', sans-serif;
    }
    nav {
      background-color: #2d3748; /* Dark navbar */
    }
    nav a {
      color: #edf2f7; /* Light text color */
    }
    nav a:hover {
      color: #63b3ed; /* Hover effect */
    }

    /* Job Application Card */
    .application-card {
      background-color: #ffffff;
      color: #2d3748;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .application-card:hover {
      transform: translateY(-5px); /* Slight hover effect */
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .meta {
      font-size: 0.875rem;
      color: #718096; /* Meta text color */
    }
  </style>
</head>

<body class="max-w-7xl m-auto">

  <?php include_once 'navbar.php'; ?>

  <div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">Applications</h1>

    <?php foreach (['pending', 'accepted', 'rejected'] as $status): ?>
      <h2 class="text-2xl font-semibold mb-4"><?php echo ucfirst($status); ?> Applications</h2>
      <?php if (!empty($applications[$status])): ?>
        <div class="space-y-4">
          <?php foreach ($applications[$status] as $application): ?>
            <div class="application-card">
              <h3 class="font-semibold text-xl"><?php echo htmlspecialchars($application['title']); ?></h3>
              <p class="meta">Applicant: <?php echo htmlspecialchars($application['username']); ?></p>
              <p class="meta">Application Date: <?php echo date('F j, Y, g:i a', strtotime($application['application_date'])); ?></p>
              <p><?php echo nl2br(htmlspecialchars($application['description'])); ?></p>
              <p class="meta">Status: <?php echo ucfirst(htmlspecialchars($application['application_status'])); ?></p>
              <p class="meta">
                <a href="<?php echo htmlspecialchars($application['resume']); ?>" class="text-blue-500 hover:underline" download>Download Resume</a>
              </p>
              <form method="POST" class="mt-4">
                <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                <div class="space-x-4">
                  <?php if ($application['application_status'] == 'pending'): ?>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'accepted'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'rejected'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-gray-600">No <?php echo $status; ?> applications at the moment.</p>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

</body>

</html>
