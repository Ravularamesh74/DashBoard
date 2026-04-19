<?php
$title = "Login";
ob_start();
?>

<form method="POST" action="/login" class="space-y-4">

    <input type="hidden" name="csrf" value="<?= generateCSRF() ?>">

    <div>
        <label class="text-sm text-gray-400">Email</label>
        <input type="email" name="email" required
            class="w-full mt-1 px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
        <label class="text-sm text-gray-400">Password</label>
        <input type="password" name="password" required
            class="w-full mt-1 px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="flex items-center justify-between text-sm">
        <label>
            <input type="checkbox" name="remember"> Remember me
        </label>
        <a href="#" class="text-blue-400 hover:underline">Forgot?</a>
    </div>

    <button type="submit"
        class="w-full py-2 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 hover:opacity-90 transition">
        Login
    </button>

</form>

<p class="text-sm text-center mt-4 text-gray-400">
    Don't have an account?
    <a href="/register" class="text-blue-400 hover:underline">Register</a>
</p>

<?php
$content = ob_get_clean();
require "layout.php";
?>