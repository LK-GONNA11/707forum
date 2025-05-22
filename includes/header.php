<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= SITE_DESC ?></title>
    <link rel="stylesheet" href="styles/dark.css">
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <nav class="main-nav">
                <a href="index.php" class="logo">707Forum</a>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <?php if(Admin::isAdmin()): ?>
                        <a href="admin/">Admin Panel</a>
                        <a href="admin/logout.php">Logout</a>
                    <?php else: ?>
                        <a href="admin/login.php">Admin Login</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </div>
    <main class="main-content">
