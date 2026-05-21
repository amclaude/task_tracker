<?php
// Get the requests url
$request = trim($_SERVER['REQUEST_URI'], '/');

$page_title = "";

if ($request === '' || $request === 'index') {
    $page_title = "Home";
} else {
    // get the last part of the url and use it as the page title
    $parts = explode('/', $request);
    $page_title = ucfirst(end($parts));
}

// Start session
session_start();

// Public auth pages
$guestOnlyPages = [
    'auth/login',
    'auth/register',
];

// Protected pages
$protectedPages = [
    'tasks'
];

$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Redirect logged in users away from auth pages
if (
    $is_logged_in &&
    in_array($request, $guestOnlyPages)
) {
    header("Location: /tasks");

    exit;
}

// Redirect guests away from protected pages
if (
    !$is_logged_in &&
    in_array($request, $protectedPages)
) {
    header("Location: /auth/login");

    exit;
}

// Pre-load the page to catch early redirects BEFORE html output
$page_content = '';

// If the request is empty use pages/index.php as the default
if ($request === '' || $request === 'index') {
    $file = __DIR__ . "/index.php";
} else {
    $file = __DIR__ . "/$request/index.php";
}

try {

    if (file_exists($file)) {

        ob_start();

        require $file;

        $page_content = ob_get_clean();

    } else {

        http_response_code(404);

        ob_start();

        require_once __DIR__ . "/partials/404.php";

        $page_content = ob_get_clean();
    }

} catch (Throwable $e) {

    error_log($e);

    http_response_code(500);

    ob_start();

    require_once __DIR__ . "/partials/500.php";

    $page_content = ob_get_clean();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#1a1b2e',
                        surface: '#23243a',
                        card: '#2a2b42',
                        border: '#35364f',
                        accent: '#b5d633',
                        'accent-hover': '#a0c020',
                        'accent-text': '#1a1b2e',
                        muted: '#8889a8',
                        subtle: '#4a4b6a',
                    },
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" href="/favicon.ico">
    <link href="/styles.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1b2e;
        }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #2a2b42 inset !important;
            -webkit-text-fill-color: #e2e2f0 !important;
        }
    </style>
</head>

<body class="font-sans min-h-screen flex flex-col text-white">

    <!-- ══ NAV ══ -->
    <nav class="w-full border-b border-border bg-surface">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">

            <!-- Logo -->
            <a href="#" class="flex items-center gap-2 group">
                <div class="w-7 h-7 rounded-md bg-accent flex items-center justify-center flex-shrink-0">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <rect x="1" y="2" width="5" height="5" rx="1" fill="#1a1b2e" />
                        <rect x="8" y="2" width="5" height="5" rx="1" fill="#1a1b2e" opacity=".6" />
                        <rect x="1" y="9" width="5" height="3" rx="1" fill="#1a1b2e" opacity=".6" />
                        <rect x="8" y="9" width="5" height="3" rx="1" fill="#1a1b2e" opacity=".3" />
                    </svg>
                </div>
                <span class="text-white font-semibold text-lg tracking-tight">tasker</span>
            </a>

            <!-- Desktop nav links -->
            <div class="hidden sm:flex items-center gap-6 text-sm text-muted">

                <?php if ($is_logged_in): ?>
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <a href="/auth/logout" class="text-accent hover:text-accent-hover font-medium transition-colors">Logout</a>
                <?php else: ?>
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <a href="/#features" class="hover:text-white transition-colors">Features</a>

                    <?php if ($request !== 'auth/login'): ?>
                        <a href="/auth/login" class="text-accent hover:text-accent-hover font-medium transition-colors">Sign in</a>
                    <?php else: ?>
                        <a href="/auth/register" class="text-accent hover:text-accent-hover font-medium transition-colors">Register</a>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <!-- Mobile nav — same logic, shows only the primary action -->
            <div class="sm:hidden text-sm font-medium">

                <?php if ($is_logged_in): ?>
                    <!-- <a href="#pending-tasks" class="hover:text-white transition-colors">Pending</a>
                    <a href="#completed-tasks" class="hover:text-white transition-colors">Completed</a> -->
                    <a href="/auth/logout" class="text-accent hover:text-accent-hover transition-colors">Logout</a>
                <?php else: ?>

                    <?php if ($request !== 'auth/login'): ?>
                        <a href="/auth/login" class="text-accent hover:text-accent-hover transition-colors">Sign in</a>
                    <?php else: ?>
                        <a href="/auth/register" class="text-accent hover:text-accent-hover transition-colors">Register</a>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>
    </nav>

    <!-- ══ BODY / MAIN ══ -->

    <?= $page_content ?>

    <!-- ══ FOOTER ══ -->
    <footer class="border-t border-border bg-surface">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-12 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-muted">&copy; 2026 Tasker. All rights reserved.</p>
            <div class="flex items-center gap-4 text-xs text-subtle">
                <a href="#" class="hover:text-muted transition-colors">Privacy</a>
                <a href="#" class="hover:text-muted transition-colors">Terms</a>
                <a href="#" class="hover:text-muted transition-colors">Support</a>
            </div>
        </div>
    </footer>

</body>

</html>