<?php

require_once __DIR__ . '/../../controllers/TaskController.php';

$taskController = new TaskController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Complete task
    if (isset($_POST['complete_task'])) {

        $taskController->complete(
            $_POST['complete_task_id']
        );
    }

    // Delete task
    elseif (isset($_POST['delete_task'])) {

        $taskController->delete(
            $_POST['delete_task']
        );
    }

    // Update task
    elseif (isset($_POST['update_task'])) {

        $taskController->edit(
            $_POST['update_task_id'],
            $_POST['title'],
            $_POST['description']
        );
    }

    // Create task
    else {

        $taskController->create(
            $_SESSION['user_id'],
            $_POST['title'],
            $_POST['description']
        );
    }

    header("Location: /tasks");

    exit;
}

$tasks = $taskController->getAll($_SESSION['user_id']);

$total     = count($tasks);
$completed = count(array_filter($tasks, fn($t) => $t->status === 'completed'));
$pending   = $total - $completed;
?>

<style>
    #add-dialog::backdrop,
    #edit-dialog::backdrop,
    #delete-dialog::backdrop {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(2px);
    }

    #add-dialog,
    #edit-dialog,
    #delete-dialog {
        border: none;
        border-radius: 1rem;
        padding: 0;
        background: transparent;
        width: min(100% - 2rem, 460px);
         margin: auto;
    }
</style>

<main class="w-full flex-1">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        <!-- ══ PAGE HEADER ══ -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs font-medium tracking-widest uppercase text-accent mb-1">Hello!</p>
                <h1 class="text-2xl sm:text-3xl font-semibold text-white"><?php echo $_SESSION['user_name'] ?></h1>
            </div>
            <?php if ($tasks): ?>
                <div class="flex items-center gap-4">
                    <!-- Stats -->
                    <div class="flex items-center gap-4 text-sm">
                        <div class="text-center">
                            <div class="text-xl font-semibold text-white"><?= $total ?></div>
                            <div class="text-xs text-muted uppercase tracking-wider">Total</div>
                        </div>
                        <div class="w-px h-8 bg-border"></div>
                        <div class="text-center">
                            <div class="text-xl font-semibold text-accent"><?= $completed ?></div>
                            <div class="text-xs text-muted uppercase tracking-wider">Done</div>
                        </div>
                        <div class="w-px h-8 bg-border"></div>
                        <div class="text-center">
                            <div class="text-xl font-semibold text-white"><?= $pending ?></div>
                            <div class="text-xs text-muted uppercase tracking-wider">Pending</div>
                        </div>
                    </div>

                    <!-- Add task button -->
                    <button
                        onclick="document.getElementById('add-dialog').showModal()"
                        class="flex items-center gap-2 bg-accent hover:bg-accent-hover text-accent-text text-sm font-semibold px-4 py-2 rounded-lg transition-colors duration-150">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M7 1v12M1 7h12" stroke="#1a1b2e" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <span class="hidden sm:inline">Add Task</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Progress bar -->
        <?php if ($total > 0): ?>
            <div class="mb-8">
                <div class="flex justify-between text-xs text-muted mb-1.5">
                    <span>Overall progress</span>
                    <span><?= round(($completed / $total) * 100) ?>%</span>
                </div>
                <div class="h-1.5 bg-border rounded-full overflow-hidden">
                    <div
                        class="h-full bg-accent rounded-full transition-all duration-500"
                        style="width: <?= round(($completed / $total) * 100) ?>%"></div>
                </div>
            </div>
        <?php endif; ?>


        <!-- ══ TASKS ══ -->
        <?php if (empty($tasks)): ?>

            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-14 h-14 rounded-xl bg-card border border-border flex items-center justify-center mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4a4b6a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="3" />
                        <path d="M9 12l2 2 4-4" />
                    </svg>
                </div>
                <p class="text-white font-medium mb-1">No tasks yet</p>
                <p class="text-muted text-sm mb-6">Create your first task to get started.</p>
                <button
                    onclick="document.getElementById('add-dialog').showModal()"
                    class="bg-accent hover:bg-accent-hover text-accent-text text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    + Add your first task
                </button>
            </div>

        <?php else: ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Pending column -->
                <div id="pending-tasks">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                        <span class="text-xs font-medium uppercase tracking-widest text-muted">Pending</span>
                        <span class="ml-auto text-xs text-subtle bg-card border border-border rounded-full px-2 py-0.5">
                            <?= $pending ?>
                        </span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task->status !== 'completed'): ?>

                                <div class="bg-card border border-border rounded-xl p-4 hover:border-subtle transition-colors duration-150">
                                    <div class="flex items-start gap-3">

                                        <!-- Mark complete -->
                                        <form method="POST" class="flex-shrink-0 mt-0.5">
                                            <input type="hidden" name="complete_task" value="1">
                                            <input type="hidden" name="complete_task_id" value="<?= htmlspecialchars($task->id) ?>">
                                            <button
                                                type="submit"
                                                title="Mark as complete"
                                                class="w-8 h-8 rounded-full border-2 border-subtle hover:border-accent hover:bg-accent/10 transition-all duration-150 flex items-center justify-center group/btn">
                                                <svg class="opacity-0 group-hover/btn:opacity-100 transition-opacity" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <path d="M2 6l3 3 5-5" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-white leading-snug mb-1 truncate">
                                                <?= htmlspecialchars($task->title) ?>
                                            </h3>
                                            <?php if (!empty($task->description)): ?>
                                                <p class="text-xs text-muted leading-relaxed line-clamp-2">
                                                    <?= htmlspecialchars($task->description) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Card footer -->
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-xs bg-yellow-400/10 text-yellow-400 border border-yellow-400/20 rounded-md px-2 py-0.5 font-medium">
                                            Pending
                                        </span>
                                        <div class="flex items-center gap-1">
                                            <!-- Edit -->
                                            <button
                                                type="button"
                                                onclick='openEditDialog(
                                            <?= json_encode($task->id) ?>,
                                            <?= json_encode($task->title) ?>,
                                            <?= json_encode($task->description ?? "") ?>
                                        )'
                                                class="w-7 h-7 rounded-md border border-border bg-surface hover:border-subtle text-muted hover:text-white flex items-center justify-center transition-colors"
                                                title="Edit task">

                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <path
                                                        d="M8.5 1.5a1.414 1.414 0 0 1 2 2L3.5 10.5l-3 .5.5-3 7.5-6.5z"
                                                        stroke="currentColor"
                                                        stroke-width="1.2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>

                                            </button>
                                            <!-- Delete -->
                                            <button
                                                type="button"
                                                onclick='openDeleteDialog(
                                            <?= json_encode($task->id) ?>,
                                            <?= json_encode($task->title) ?>
                                        )'
                                                class="w-7 h-7 rounded-md border border-border bg-surface hover:border-red-500/50 text-muted hover:text-red-400 flex items-center justify-center transition-colors"
                                                title="Delete task">

                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <path
                                                        d="M1.5 3h9M4.5 3V2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1M5 5.5v3M7 5.5v3M2.5 3l.75 6.5a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5L9.5 3"
                                                        stroke="currentColor"
                                                        stroke-width="1.2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>

                                            </button>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ($pending === 0): ?>
                            <div class="border border-dashed border-border rounded-xl p-6 text-center text-muted text-sm">
                                All caught up!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Completed column -->
                <div id="completed-tasks">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 rounded-full bg-accent"></span>
                        <span class="text-xs font-medium uppercase tracking-widest text-muted">Completed</span>
                        <span class="ml-auto text-xs text-subtle bg-card border border-border rounded-full px-2 py-0.5">
                            <?= $completed ?>
                        </span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task->status === 'completed'): ?>

                                <div class="bg-card border border-border rounded-xl p-4 opacity-60">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-accent/20 border border-accent/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                <path d="M2 6l3 3 5-5" stroke="#b5d633" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-muted line-through leading-snug mb-1 truncate">
                                                <?= htmlspecialchars($task->title) ?>
                                            </h3>
                                            <?php if (!empty($task->description)): ?>
                                                <p class="text-xs text-subtle leading-relaxed line-clamp-2">
                                                    <?= htmlspecialchars($task->description) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-xs bg-accent/10 text-accent border border-accent/20 rounded-md px-2 py-0.5 font-medium">
                                            Completed
                                        </span>
                                        <!-- Delete only for completed -->
                                        <button
                                            type="button"
                                            onclick='openDeleteDialog(
                                        <?= json_encode($task->id) ?>,
                                        <?= json_encode($task->title) ?>
                                    )'
                                            class="w-7 h-7 rounded-md border border-border bg-surface hover:border-red-500/50 text-muted hover:text-red-400 flex items-center justify-center transition-colors"
                                            title="Delete task">

                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                <path
                                                    d="M1.5 3h9M4.5 3V2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1M5 5.5v3M7 5.5v3M2.5 3l.75 6.5a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5L9.5 3"
                                                    stroke="currentColor"
                                                    stroke-width="1.2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                        </button>
                                    </div>
                                </div>

                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ($completed === 0): ?>
                            <div class="border border-dashed border-border rounded-xl p-6 text-center text-muted text-sm">
                                No completed tasks yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        <?php endif; ?>


        <!-- ══ ADD DIALOG ══ -->
        <dialog id="add-dialog">
            <div class="bg-card border border-border rounded-2xl px-6 py-6 w-full">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-white font-semibold text-lg">New Task</h2>
                        <p class="text-muted text-sm">Fill in the details below.</p>
                    </div>
                    <button
                        onclick="document.getElementById('add-dialog').close()"
                        class="w-8 h-8 rounded-lg bg-surface border border-border flex items-center justify-center text-muted hover:text-white hover:border-subtle transition-colors">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="add-title" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">Task title</label>
                        <input
                            type="text" id="add-title" name="title"
                            placeholder="e.g. Design the login screen" required
                            class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors" />
                    </div>
                    <div>
                        <label for="add-desc" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">
                            Description <span class="normal-case tracking-normal text-subtle">(optional)</span>
                        </label>
                        <textarea
                            id="add-desc" name="description" rows="3"
                            placeholder="Add some details…"
                            class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors resize-none"></textarea>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="button" onclick="document.getElementById('add-dialog').close()"
                            class="flex-1 bg-surface border border-border text-muted hover:text-white hover:border-subtle text-sm font-medium py-2.5 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            id="add-btn"
                            class="flex-1 bg-accent hover:bg-accent-hover text-accent-text text-sm font-semibold py-2.5 rounded-lg transition-colors">
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </dialog>


        <!-- ══ EDIT DIALOG ══ -->
        <dialog id="edit-dialog">
            <div class="bg-card border border-border rounded-2xl px-6 py-6 w-full">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-white font-semibold text-lg">Edit Task</h2>
                        <p class="text-muted text-sm">Make your changes and save.</p>
                    </div>
                    <button
                        onclick="document.getElementById('edit-dialog').close()"
                        class="w-8 h-8 rounded-lg bg-surface border border-border flex items-center justify-center text-muted hover:text-white hover:border-subtle transition-colors">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="update_task" value="1">
                    <input type="hidden" name="update_task_id" id="edit-task-id">
                    <div>
                        <label for="edit-title" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">Task title</label>
                        <input
                            type="text" id="edit-title" name="title" required
                            class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors" />
                    </div>
                    <div>
                        <label for="edit-desc" class="block text-xs font-medium text-muted uppercase tracking-wider mb-2">
                            Description <span class="normal-case tracking-normal text-subtle">(optional)</span>
                        </label>
                        <textarea
                            id="edit-desc" name="description" rows="3"
                            class="w-full bg-surface border border-border text-white text-sm rounded-lg px-4 py-3 placeholder-subtle outline-none focus:border-accent transition-colors resize-none"></textarea>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="button" onclick="document.getElementById('edit-dialog').close()"
                            class="flex-1 bg-surface border border-border text-muted hover:text-white hover:border-subtle text-sm font-medium py-2.5 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            id="edit-btn"
                            class="flex-1 bg-accent hover:bg-accent-hover text-accent-text text-sm font-semibold py-2.5 rounded-lg transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </dialog>


        <!-- ══ DELETE CONFIRMATION DIALOG ══ -->
        <dialog id="delete-dialog">
            <div class="bg-card border border-border rounded-2xl px-6 py-6 w-full">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-10 h-10 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center flex-shrink-0">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 5.5v4M8 11.5v.5M3 14h10a1 1 0 0 0 .87-1.5l-5-8.66a1 1 0 0 0-1.74 0L2.13 12.5A1 1 0 0 0 3 14z"
                                stroke="#f87171" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white font-semibold text-lg leading-tight">Delete task?</h2>
                        <p class="text-muted text-sm mt-1">
                            You're about to delete <span id="delete-task-name" class="text-white font-medium"></span>. This action cannot be undone.
                        </p>
                    </div>
                </div>
                <form method="POST" class="flex gap-3">
                    <input type="hidden" name="delete_task" id="delete-task-id">
                    <button type="button" onclick="document.getElementById('delete-dialog').close()"
                        class="flex-1 bg-surface border border-border text-muted hover:text-white hover:border-subtle text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        id="delete-btn"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </dialog>
    </div>
</main>

<script>
    // Add dialog disable btn
    const createForm = document.querySelector('#add-dialog form');
    const createButton = document.getElementById('add-btn');

    createForm.addEventListener('submit', () => {

        createButton.disabled = true;

        createButton.innerText = 'Creating task...';

        createButton.classList.add(
            'opacity-70',
            'cursor-not-allowed'
        );
    });
    // Edit Dialog
    function openEditDialog(id, title, description) {

        const dialog = document.getElementById('edit-dialog');

        document.getElementById('edit-task-id').value = id;

        document.getElementById('edit-title').value = title;

        document.getElementById('edit-desc').value = description || '';

        dialog.showModal();
    }

    // Edit dialog disable btn
    const updateForm = document.querySelector('#edit-dialog form');
    const updateButton = document.getElementById('edit-btn');

    updateForm.addEventListener('submit', () => {

        updateButton.disabled = true;

        updateButton.innerText = 'Saving changes...';

        updateButton.classList.add(
            'opacity-70',
            'cursor-not-allowed'
        );
    });

    // Delete Dialog

    function openDeleteDialog(id, title) {

        const dialog = document.getElementById('delete-dialog');

        document.getElementById('delete-task-id').value = id;

        document.getElementById('delete-task-name').textContent =
            '"' + title + '"';

        dialog.showModal();
    }

    // Delete dialog disable btn
    const deleteForm = document.querySelector('#delete-dialog form');
    const deleteDialogButton = document.getElementById('delete-btn');

    deleteForm.addEventListener('submit', () => {

        deleteDialogButton.disabled = true;

        deleteDialogButton.innerText = 'Deleting...';

        deleteDialogButton.classList.add(
            'opacity-70',
            'cursor-not-allowed'
        );
    });


                            
    document.querySelectorAll('dialog').forEach(dialog => {

        dialog.addEventListener('click', function(e) {

            const rect = dialog.getBoundingClientRect();

            const clickedOutside =
                e.clientX < rect.left ||
                e.clientX > rect.right ||
                e.clientY < rect.top ||
                e.clientY > rect.bottom;

            if (clickedOutside) {
                dialog.close();
            }
        });
    });
</script>