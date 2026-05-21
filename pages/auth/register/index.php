<?php
require_once __DIR__ . '/../../../controllers/AuthController.php';

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
    } else {
        $authController = new AuthController();

        $success = $authController->register(
            $name,
            $email,
            $password
        );

        if ($success) {
            $message = "User registered!";
        } else {
            $message = "Registration failed.";
        }
    }
}
?>

<!-- ══ HEADER ══ -->
<header class="text-center pt-12 pb-6 px-4">
    <p class="text-xs font-medium tracking-widest uppercase text-accent mb-3">Get started</p>
    <h1 class="text-2xl sm:text-3xl font-semibold text-white">Create your account</h1>
    <p class="text-muted text-sm mt-2">Start organizing your tasks in seconds.</p>
</header>

<!-- ══ MAIN ══ -->
<main class="flex-1 flex items-start justify-center px-4 pb-12">
    <div class="w-full max-w-sm">

        <div class="bg-card border border-border rounded-2xl px-6 sm:px-8 py-8">

            <?php if (!empty($message)): ?>
                <div class="mb-5 text-sm text-center rounded-lg px-4 py-2
                    <?= $success ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">
                        Full name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Jane Smith"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                        required
                        class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors duration-150" />
                </div>

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
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                        class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors duration-150" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Min. 8 characters"
                        required
                        class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors duration-150" />
                    <div class="flex gap-1 mt-2">
                        <div class="h-1 flex-1 rounded-full bg-border" id="s1"></div>
                        <div class="h-1 flex-1 rounded-full bg-border" id="s2"></div>
                        <div class="h-1 flex-1 rounded-full bg-border" id="s3"></div>
                        <div class="h-1 flex-1 rounded-full bg-border" id="s4"></div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">
                        Confirm password
                    </label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Repeat your password"
                        required
                        class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors duration-150" />
                    <p class="text-xs mt-1.5 hidden text-red-400" id="match-msg">Passwords do not match.</p>
                </div>

                <!-- Submit -->
                <button
                    id="submit-btn"
                    type="submit"
                    class="w-full bg-accent hover:bg-accent-hover text-accent-text text-sm font-semibold py-3 rounded-lg transition-colors duration-150">
                    Create account
                </button>

            </form>

            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-border"></div>
                <span class="text-xs text-muted">or</span>
                <div class="flex-1 h-px bg-border"></div>
            </div>

            <p class="text-center text-sm text-muted">
                Already have an account?
                <a href="/auth/login" class="text-accent hover:text-accent-hover font-medium transition-colors">Sign in</a>
            </p>

        </div>
    </div>
</main>


<script>
    const form = document.querySelector('form');
    const button = document.getElementById('submit-btn');

    form.addEventListener('submit', () => {

        button.disabled = true;

        button.innerText = 'Creating account...';

        button.classList.add(
            'opacity-70',
            'cursor-not-allowed'
        );
    });
</script>