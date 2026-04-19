<?php

$title = "Users";

/**
 * Expected:
 * $users = [...]
 * $pagination = [...]
 */

ob_start();
?>

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold">Users</h2>
            <p class="text-gray-400 text-sm">Manage system users</p>
        </div>

        <a href="/users/create"
           class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 hover:opacity-90 transition">
           + Add User
        </a>
    </div>

    <!-- SEARCH -->
    <form method="GET" class="flex gap-3">
        <input 
            type="text" 
            name="search"
            placeholder="Search users..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
            class="flex-1 px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500"
        >

        <button class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition">
            Search
        </button>
    </form>

    <!-- TABLE -->
    <div class="glass rounded-2xl overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-white/10 text-left">
                <tr>
                    <th class="p-4"><input type="checkbox" id="selectAll"></th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Created</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/10">

                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-white/5 transition">

                            <td class="p-4">
                                <input type="checkbox" name="ids[]" value="<?= $user['id'] ?>">
                            </td>

                            <td class="p-4">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-1 text-xs rounded-lg 
                                    <?= $user['role'] === 'admin' 
                                        ? 'bg-purple-500/20 text-purple-300'
                                        : 'bg-blue-500/20 text-blue-300' ?>">
                                    <?= $user['role'] ?>
                                </span>
                            </td>

                            <td class="p-4 text-gray-400">
                                <?= formatDate($user['created_at']) ?>
                            </td>

                            <td class="p-4 text-right space-x-2">

                                <a href="/users/<?= $user['id'] ?>/edit"
                                   class="px-3 py-1 text-xs rounded-lg bg-blue-500/20 hover:bg-blue-500/40">
                                   Edit
                                </a>

                                <form method="POST" action="/users/<?= $user['id'] ?>" class="inline">
                                    <input type="hidden" name="csrf" value="<?= generateCSRF() ?>">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <button 
                                        onclick="return confirm('Delete this user?')"
                                        class="px-3 py-1 text-xs rounded-lg bg-red-500/20 hover:bg-red-500/40">
                                        Delete
                                    </button>
                                </form>

                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-400">
                            No users found
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="flex justify-between items-center text-sm text-gray-400">

        <span>
            Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?>
        </span>

        <div class="flex gap-2">

            <?php if ($pagination['current_page'] > 1): ?>
                <a href="?page=<?= $pagination['current_page'] - 1 ?>"
                   class="px-3 py-1 bg-white/10 rounded-lg hover:bg-white/20">
                   Prev
                </a>
            <?php endif; ?>

            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="?page=<?= $pagination['current_page'] + 1 ?>"
                   class="px-3 py-1 bg-white/10 rounded-lg hover:bg-white/20">
                   Next
                </a>
            <?php endif; ?>

        </div>

    </div>

</div>

<!-- BULK SELECT SCRIPT -->
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>

<?php
$content = ob_get_clean();
require "../layouts/main.php";
?>