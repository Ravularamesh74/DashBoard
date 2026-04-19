<?php

$title = "Edit User";

/**
 * Expected:
 * $user = [
 *   'id' => 1,
 *   'email' => 'test@gmail.com',
 *   'role' => 'admin'
 * ];
 */

ob_start();
?>

<div class="max-w-2xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit User</h2>
        <p class="text-gray-400 text-sm">Update user details</p>
    </div>

    <!-- FORM -->
    <form method="POST" action="/users/<?= $user['id'] ?>" class="glass p-6 rounded-2xl space-y-5">

        <input type="hidden" name="csrf" value="<?= generateCSRF() ?>">
        <input type="hidden" name="_method" value="PUT">

        <!-- EMAIL -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Email</label>
            <input 
                type="email" 
                name="email" 
                required
                value="<?= htmlspecialchars($user['email']) ?>"
                class="w-full px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- PASSWORD (OPTIONAL) -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">New Password</label>
            <input 
                type="password" 
                name="password"
                class="w-full px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Leave blank to keep current password"
            >
            <p class="text-xs text-gray-500 mt-1">Only fill if you want to change password</p>
        </div>

        <!-- ROLE -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Role</label>
            <select 
                name="role"
                class="w-full px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-purple-500"
            >
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <!-- ACTIONS -->
        <div class="flex justify-between items-center pt-4">

            <a href="/users" 
               class="text-gray-400 hover:text-white transition">
               ← Back
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 hover:opacity-90 transition">
                Update User
            </button>

        </div>

    </form>

</div>

<?php
$content = ob_get_clean();
require "../layouts/main.php";
?>