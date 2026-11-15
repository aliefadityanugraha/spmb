<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Halaman Login Resmi Calon Taruna & Panitia Seleksi SPMB SMAN 5 Taruna Brawijaya Jawa Timur. Masuk menggunakan Nomor WhatsApp dan Password terdaftar.';
    $data['meta_robots']      = 'index, follow';
    require __DIR__ . '/../partials/seo_head.php';
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-slate-50 to-teal-50/60 flex flex-col justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden text-slate-800">
    <!-- Dekorasi Background Light Green -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -right-32 w-72 sm:w-96 h-72 sm:h-96 rounded-full bg-emerald-400/15 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-72 sm:w-96 h-72 sm:h-96 rounded-full bg-teal-400/15 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md mx-auto relative z-10">
        <!-- Badge / Identitas Sekolah -->
        <div class="flex justify-center">
            <a href="/" class="inline-block">
                <img src="/assets/img/logo-smata-150x150.png" alt="Logo SMAN 5 Taruna Brawijaya" class="h-20 w-20 sm:h-24 sm:w-24 object-contain drop-shadow-md mx-auto">
            </a>
        </div>
        <h2 class="mt-3 sm:mt-4 text-center text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight px-2">
            SPMB SMAN 5 Taruna Brawijaya
        </h2>
        <p class="mt-1 text-center text-xs sm:text-sm text-slate-600 px-2">
            Sistem Penerimaan Murid Baru &mdash; Masuk ke Akun Calon Taruna
        </p>
    </div>

    <div class="mt-6 sm:mt-8 w-full max-w-md mx-auto relative z-10">
        <div class="bg-white py-6 px-5 sm:py-8 sm:px-10 shadow-xl shadow-emerald-900/5 rounded-2xl border border-slate-200">

            <?php if (!empty($data['success'])): ?>
                <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 sm:p-4 text-xs sm:text-sm text-emerald-800 font-medium">
                    <?= htmlspecialchars($data['success']) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['errors']['general'])): ?>
                <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 p-3.5 sm:p-4 text-xs sm:text-sm text-rose-700 flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><?= htmlspecialchars($data['errors']['general']) ?></span>
                </div>
            <?php endif; ?>

            <form class="space-y-4 sm:space-y-5" action="/auth/login" method="POST" novalidate>
                <!-- Input No HP -->
                <div>
                    <label for="no_hp" class="block text-xs sm:text-sm font-semibold text-slate-700">
                        Nomor HP / WhatsApp
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="no_hp"
                            name="no_hp"
                            type="text"
                            autocomplete="username"
                            required
                            placeholder="Contoh: 081234567890"
                            value="<?= htmlspecialchars($data['old']['no_hp'] ?? '') ?>"
                            class="block w-full rounded-xl border <?= isset($data['errors']['no_hp']) ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/70 focus:bg-white px-4 py-3 text-base sm:text-sm text-slate-900 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 transition"
                        >
                    </div>
                    <?php if (isset($data['errors']['no_hp'])): ?>
                        <p class="mt-1.5 text-xs text-rose-600 font-medium"><?= htmlspecialchars($data['errors']['no_hp']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-700">
                        Password
                    </label>
                    <div class="mt-1.5 relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="Masukkan password Anda"
                            class="block w-full rounded-xl border <?= isset($data['errors']['password']) ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/70 focus:bg-white pl-4 pr-16 py-3 text-base sm:text-sm text-slate-900 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 transition"
                        >
                        <button
                            type="button"
                            onclick="const p = document.getElementById('password'); const isPwd = p.type === 'password'; p.type = isPwd ? 'text' : 'password'; this.textContent = isPwd ? 'Sembunyi' : 'Lihat';"
                            class="absolute inset-y-0 right-2 my-auto h-8 px-2.5 rounded-lg text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition"
                        >
                            Lihat
                        </button>
                    </div>
                    <?php if (isset($data['errors']['password'])): ?>
                        <p class="mt-1.5 text-xs text-rose-600 font-medium"><?= htmlspecialchars($data['errors']['password']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-1 sm:pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center py-3 sm:py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-emerald-600/20 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 transition duration-150"
                    >
                        Masuk ke Dasbor SPMB
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-200 text-center space-y-2">
                <p class="text-xs sm:text-sm text-slate-600">
                    Belum memiliki akun pendaftaran?
                    <a href="/auth/register" class="font-bold text-emerald-700 hover:text-emerald-800 transition inline-block mt-1 sm:mt-0">
                        Daftar Akun Baru
                    </a>
                </p>
                <p>
                    <a href="/" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-emerald-700 transition">
                        &larr; Kembali ke Halaman Beranda
                    </a>
                </p>
            </div>
        </div>

        <p class="mt-6 text-center text-[11px] sm:text-xs text-slate-500 px-2">
            &copy; <?= date('Y') ?> Panitia SPMB SMAN 5 Taruna Brawijaya Jawa Timur.
        </p>
    </div>
</body>
</html>
