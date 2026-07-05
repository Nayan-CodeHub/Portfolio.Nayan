<?php
session_start();
require 'db.php';

$adminPassword = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $loginError = 'Incorrect password.';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Admin Login</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 40px; background: #111827; color: #f3f4f6; }
        .box { max-width: 360px; margin: auto; background: #1f2937; padding: 24px; border-radius: 14px; border: 1px solid #34d3ab; }
        input { width: 100%; padding: 10px; margin-top: 8px; border-radius: 8px; border: 1px solid #34d3ab; }
        button { margin-top: 12px; padding: 10px 14px; border: none; border-radius: 8px; background: #34d3ab; color: #06231d; cursor: pointer; font-weight: 700; }
        .error { color: #fca5a5; margin-top: 10px; }
      </style>
    </head>
    <body>
      <div class="box">
        <h2>Admin Login</h2>
        <form method="post">
          <label>Password</label>
          <input type="password" name="password" required />
          <button type="submit">Login</button>
        </form>
        <?php if (!empty($loginError)) echo '<div class="error">' . htmlspecialchars($loginError) . '</div>'; ?>
      </div>
    </body>
    </html>
    <?php
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    $stmt = $conn->prepare('DELETE FROM contacts WHERE id = ?');
    $stmt->bind_param('i', $deleteId);
    $stmt->execute();
    $stmt->close();
    header('Location: admin.php');
    exit;
}

$sql = 'SELECT id, name, email, subject, message, created_at FROM contacts ORDER BY id DESC';
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | Portfolio Messages</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 30px; background: #111827; color: #f3f4f6; }
    .container { max-width: 1100px; margin: auto; }
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .logout { text-decoration: none; color: #34d3ab; font-weight: bold; }
    .card { background: #1f2937; padding: 16px; border-radius: 12px; border: 1px solid #34d3ab; margin-bottom: 16px; }
    .meta { color: #9ca3af; font-size: 0.9rem; margin-bottom: 8px; }
    .msg { white-space: pre-wrap; line-height: 1.6; }
    .empty { color: #9ca3af; }
    .delete-btn { margin-top: 10px; padding: 8px 12px; border: none; border-radius: 8px; background: #ef4444; color: white; cursor: pointer; }
  </style>
</head>
<body>
  <div class="container">
    <div class="topbar">
      <h1>Saved Contact Messages</h1>
      <a class="logout" href="admin.php?logout=1">Logout</a>
    </div>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <div class="meta"><strong>#<?php echo $row['id']; ?></strong> • <?php echo htmlspecialchars($row['name']); ?> • <?php echo htmlspecialchars($row['email']); ?> • <?php echo htmlspecialchars($row['created_at']); ?></div>
          <div class="meta"><strong>Subject:</strong> <?php echo htmlspecialchars($row['subject']); ?></div>
          <div class="msg"><?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
          <form method="post" onsubmit="return confirm('Delete this message?');">
            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>" />
            <button type="submit" class="delete-btn">Delete</button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="empty">No messages saved yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>
<?php
$conn->close();
