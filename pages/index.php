<style>
    .grid-bg {
        background-image:
            linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .feature-card { transition: border-color 0.2s, background 0.2s; }
    .feature-card:hover { border-color: rgba(181,214,51,0.4); background: rgba(181,214,51,0.04); }
</style>

<!-- ══ HERO ══ -->
<section class="grid-bg border-b border-border">
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-24 text-center">

    <h1 class="font-['Bricolage_Grotesque'] text-4xl sm:text-5xl font-bold text-white leading-tight tracking-tight mb-4">
        A simple way to manage<br>your tasks
    </h1>

    <p class="text-muted text-base sm:text-lg leading-relaxed mb-10 max-w-md mx-auto">
        Add tasks, mark them done, stay on top of your work. Nothing more, nothing less.
    </p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="/auth/register"
            class="w-full sm:w-auto inline-flex items-center justify-center bg-accent hover:bg-accent-hover text-accent-text font-semibold text-sm px-6 py-3 rounded-lg transition-colors duration-150"
        >
            Get started — it's free
        </a>
        <a href="/auth/login"
            class="w-full sm:w-auto inline-flex items-center justify-center bg-surface border border-border hover:border-subtle text-white text-sm font-medium px-6 py-3 rounded-lg transition-colors duration-150"
        >
            Sign in
        </a>
    </div>

</div>
</section>

<!-- ══ FEATURES ══ -->
<section id="features" class="border-t border-border">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-16">

        <div class="text-center mb-10">
            <p class="text-xs font-medium tracking-widest uppercase text-accent mb-2">Features</p>
            <h2 class="font-['Bricolage_Grotesque'] text-2xl sm:text-3xl font-bold text-white">What you can do</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php
            $features = [
                [
                    'icon' => '<path d="M12 5v14M5 12h14" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round"/>',
                    'title' => 'Add tasks instantly',
                    'desc'  => 'Hit the Add Task button, write a title and optional description, and it\'s on your board.',
                ],
                [
                    'icon' => '<path d="M11 4H4a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                    'title' => 'Edit any time',
                    'desc'  => 'Changed your mind? Open the edit dialog, update the details, save — done.',
                ],
                [
                    'icon' => '<path d="M20 6L9 17l-5-5" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                    'title' => 'Mark as complete',
                    'desc'  => 'One click moves a task to the completed column. Your pending list stays focused.',
                ],
                [
                    'icon' => '<polyline points="3 6 5 6 21 6" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round"/><path d="M19 6l-1 14H6L5 6" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                    'title' => 'Delete with confidence',
                    'desc'  => 'A confirmation step before anything is removed so you never delete by accident.',
                ],
            ];
            foreach ($features as $f):
            ?>
            <div class="feature-card flex items-start gap-4 bg-card border border-border rounded-xl p-5">
                <div class="w-9 h-9 rounded-lg bg-accent/10 border border-accent/20 flex items-center justify-center flex-shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><?= $f['icon'] ?></svg>
                </div>
                <div>
                    <h3 class="text-white font-medium text-sm mb-1"><?= $f['title'] ?></h3>
                    <p class="text-muted text-sm leading-relaxed"><?= $f['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ══ CTA ══ -->
<section class="max-w-2xl mx-auto px-4 sm:px-6 py-20 text-center">
    <h2 class="font-['Bricolage_Grotesque'] text-2xl sm:text-3xl font-bold text-white mb-3">Ready to get started?</h2>
    <p class="text-muted text-sm mb-8">Create a free account and start organizing your work today.</p>
    <a href="/auth/register"
        class="inline-flex items-center bg-accent hover:bg-accent-hover text-accent-text font-semibold text-sm px-6 py-3 rounded-lg transition-colors duration-150"
    >
        Create your account
    </a>
    <p class="mt-4 text-xs text-subtle">
        Already have an account? <a href="/auth/login" class="text-accent hover:text-accent-hover transition-colors">Sign in</a>
    </p>
</section>