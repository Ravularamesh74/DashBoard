<?php
$title = "Register";
ob_start();
?>

<form method="POST" action="/register" class="space-y-4">

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

    <button type="submit"
        class="w-full py-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 hover:opacity-90 transition">
        Create Account
    </button>

</form>

<p class="text-sm text-center mt-4 text-gray-400">
    Already have an account?
    <a href="/login" class="text-blue-400 hover:underline">Login</a>
</p>

<?php
$content = ob_get_clean();
require "layout.php";
?>