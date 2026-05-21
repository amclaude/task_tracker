<?php

require_once __DIR__ . '/../../../controllers/AuthController.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $authController = new AuthController();

    $user = $authController->login(
        $email,
        $password
    );

    if ($user) {

        // Store logged in user
        $_SESSION['user_id'] = $user->id;

        $_SESSION['user_name'] = $user->name;

        // Redirect to dashboard
        header("Location: /tasks");

        exit;
    } else {

        $message = "Invalid email or password.";
    }
}
?>

<!-- ══ HEADER ══ -->
<header class="text-center pt-12 pb-6 px-4">
    <p class="text-xs font-medium tracking-widest uppercase text-accent mb-3">Welcome back</p>
    <h1 class="text-2xl sm:text-3xl font-semibold text-white">Sign in to your workspace</h1>
    <p class="text-muted text-sm mt-2">Manage your tasks where you left off.</p>
</header>

<main class="flex-1 flex items-start justify-center px-4 pb-12">
    <div class="w-full max-w-sm">

        <!-- Card -->
        <div class="bg-card border border-border rounded-2xl px-6 sm:px-8 py-8">

            <!-- Message slot (PHP will echo here) -->
            <?php if (!empty($message)): ?>
                <div class="mb-4 text-sm text-center rounded-lg px-4 py-2 bg-red-500/10 text-red-400 border border-red-500/20">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        required
                        class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors duration-150" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="text-xs font-medium text-muted uppercase tracking-wider">
                            Password
                        </label>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors duration-150" />
                </div>

                <!-- Remember me -->
                <!-- <div class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 rounded cursor-pointer accent-accent" />
                    <label for="remember" class="text-sm text-muted cursor-pointer select-none">
                        Remember me
                    </label>
                </div> -->

                <!-- Submit -->
                <button
                    id="login-btn"
                    type="submit"
                    class="w-full bg-accent hover:bg-accent-hover text-accent-text text-sm font-semibold py-3 rounded-lg transition-colors duration-150">
                    Sign in
                </button>

            </form>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-border"></div>
                <span class="text-xs text-muted">or</span>
                <div class="flex-1 h-px bg-border"></div>
            </div>

            <!-- Register -->
            <p class="text-center text-sm text-muted">
                Don't have an account?
                <a href="/auth/register" class="text-accent hover:text-accent-hover font-medium transition-colors">
                    Create one
                </a>
            </p>

        </div>
    </div>
</main>

<script>
    const form = document.querySelector('form');
    const button = document.getElementById('login-btn');

    form.addEventListener('submit', () => {

        button.disabled = true;

        button.innerText = 'Signing in...';

        button.classList.add(
            'opacity-70',
            'cursor-not-allowed'
        );
    });
</script>