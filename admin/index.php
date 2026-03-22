<?php
session_start();
require_once '../config.php';

// Simple authentication (in production, use proper authentication)
$admin_username = 'admin';
$admin_password = 'admin123'; // Change this!

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tesfaw Amare</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <?php if (!$isLoggedIn): ?>
        <div class="login-container">
            <div class="login-box">
                <h2>Admin Login</h2>
                <form method="POST">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="admin-container">
            <aside class="admin-sidebar">
                <h2>Admin Panel</h2>
                <nav>
                    <a href="#dashboard" class="active"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="#messages"><i class="fas fa-envelope"></i> Messages</a>
                    <a href="#projects"><i class="fas fa-folder"></i> Projects</a>
                    <a href="#blog"><i class="fas fa-blog"></i> Blog Posts</a>
                    <a href="#subscribers"><i class="fas fa-users"></i> Subscribers</a>
                </nav>
                <form method="POST" style="margin-top: auto;">
                    <button type="submit" name="logout" class="btn btn-secondary">Logout</button>
                </form>
            </aside>
            
            <main class="admin-content">
                <div id="dashboard" class="admin-section active">
                    <h1>Dashboard</h1>
                    <div class="stats-grid">
                        <?php
                        $conn = getDBConnection();
                        
                        $messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE status='new'")->fetch_assoc()['count'];
                        $projects = $conn->query("SELECT COUNT(*) as count FROM projects WHERE status='active'")->fetch_assoc()['count'];
                        $posts = $conn->query("SELECT COUNT(*) as count FROM blog_posts WHERE status='published'")->fetch_assoc()['count'];
                        $subscribers = $conn->query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status='active'")->fetch_assoc()['count'];
                        ?>
                        <div class="stat-card">
                            <i class="fas fa-envelope"></i>
                            <h3><?php echo $messages; ?></h3>
                            <p>New Messages</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-folder"></i>
                            <h3><?php echo $projects; ?></h3>
                            <p>Active Projects</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-blog"></i>
                            <h3><?php echo $posts; ?></h3>
                            <p>Published Posts</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-users"></i>
                            <h3><?php echo $subscribers; ?></h3>
                            <p>Subscribers</p>
                        </div>
                    </div>
                </div>
                
                <div id="messages" class="admin-section">
                    <h1>Contact Messages</h1>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 20");
                                while($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td><span class="badge badge-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
        
        <script>
            document.querySelectorAll('.admin-sidebar a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = e.currentTarget.getAttribute('href').substring(1);
                    
                    document.querySelectorAll('.admin-sidebar a').forEach(a => a.classList.remove('active'));
                    document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
                    
                    e.currentTarget.classList.add('active');
                    document.getElementById(target).classList.add('active');
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>
<?php $conn->close(); ?>
