<?php

$title = "Create User";

// Capture content
ob_start();
?>

<div class="max-w-2xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Create New User</h2>
        <p class="text-gray-400 text-sm">Add a new user to the system</p>
    </div>

    <!-- FORM -->
    <form method="POST" action="/users" class="glass p-6 rounded-2xl space-y-5">

        <input type="hidden" name="csrf" value="<?= generateCSRF() ?>">

        <!-- EMAIL -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Email</label>
            <input 
                type="email" 
                name="email" 
                required
                class="w-full px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter email"
            >
        </div>

        <!-- PASSWORD -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Password</label>
            <input 
                type="password" 
                name="password" 
                required
                class="w-full px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter password"
            >
            <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
        </div>

        <!-- ROLE -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Role</label>
            <select 
                name="role"
                class="w-full px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-purple-500"
            >
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- ACTIONS -->
        <div class="flex justify-between items-center pt-4">

            <a href="/users" 
               class="text-gray-400 hover:text-white transition">
               ← Back
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 hover:opacity-90 transition">
                Create User
            </button>

        </div>

    </form>

</div>

<?php
$content = ob_get_clean();
require "../layouts/main.php";
?>