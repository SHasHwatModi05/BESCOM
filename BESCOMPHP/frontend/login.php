<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user'])) {
    header("Location: ../reports/supervisor.php");
    exit();
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$mobile = isset($_GET['mobile']) ? htmlspecialchars($_GET['mobile']) : '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        $input_mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
        if (preg_match('/^[0-9]{10}$/', $input_mobile)) {
            header("Location: login.php?step=2&mobile=" . urlencode($input_mobile));
            exit();
        } else {
            $error = 'Please enter a valid 10-digit mobile number.';
        }
    } elseif ($step === 2) {
        $input_otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
        if ($input_otp === '123456') {
            $_SESSION['user'] = 'Supervisor';
            $_SESSION['mobile'] = $mobile;
            header("Location: ../reports/supervisor.php");
            exit();
        } else {
            $error = 'Invalid OTP entered. Please use mock OTP: 123456';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BESCOM Meter Reader - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        primary: '#0284c7',
                        primaryHover: '#0369a1',
                        accent: '#0ea5e9',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-sky-100 via-white to-blue-50 relative antialiased">

    <!-- Background decorative blur elements -->
    <div class="absolute w-96 h-96 bg-primary opacity-10 blur-3xl rounded-full top-10 left-10"></div>
    <div class="absolute w-96 h-96 bg-accent opacity-10 blur-3xl rounded-full bottom-10 right-10"></div>

    <div class="w-full max-w-md bg-white/80 backdrop-blur-md shadow-2xl rounded-2xl p-6 sm:p-8 border border-white relative z-10">
        <!-- Header -->
        <div class="text-center mb-8 space-y-3">
            <div class="bg-white rounded-2xl shadow-md p-3 flex items-center justify-center border border-slate-100 w-16 h-16 mx-auto">
                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-primary tracking-wide leading-tight">
                Bangalore Electricity Supply Company Limited
            </h1>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-widest">BESCOM</h2>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 bg-rose-50 border border-rose-100 rounded-xl p-3.5 flex items-center gap-3 text-rose-800 text-xs font-semibold">
                <i class="fas fa-exclamation-circle text-rose-500 text-base"></i>
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <!-- STEP 1: MOBILE FORM -->
        <?php if ($step === 1): ?>
            <form action="login.php?step=1" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Mobile Number</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="mobile" maxlength="10" required
                               placeholder="Enter 10-digit mobile number"
                               class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               oninput="this.value = this.value.replace(/\D/g,'').slice(0,10)" />
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-primary to-primaryHover text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-primary/20 hover:scale-[1.01] active:scale-[0.99] transition duration-200">
                    Send OTP
                </button>
            </form>
        <?php endif; ?>

        <!-- STEP 2: OTP FORM -->
        <?php if ($step === 2): ?>
            <form action="login.php?step=2&mobile=<?php echo urlencode($mobile); ?>" method="POST" class="space-y-5">
                <div class="bg-sky-50 border border-sky-100 rounded-xl p-3.5 flex items-center gap-3">
                    <i class="fas fa-info-circle text-primary"></i>
                    <p class="text-xs text-sky-800 leading-relaxed">
                        OTP code sent to <strong><?php echo $mobile; ?></strong>. Use testing OTP: <strong class="underline">123456</strong>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Verification OTP</label>
                    <div class="relative">
                        <i class="fas fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="otp" maxlength="6" required
                               placeholder="Enter 6-digit OTP"
                               class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               oninput="this.value = this.value.replace(/\D/g,'').slice(0,6)" />
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="login.php" class="flex-1 bg-slate-100 text-slate-700 py-3 rounded-xl font-semibold hover:bg-slate-200 transition text-center flex items-center justify-center">
                        Back
                    </a>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-primary to-primaryHover text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-primary/20 hover:scale-[1.01] active:scale-[0.99] transition duration-200">
                        Verify OTP
                    </button>
                </div>
            </form>
        <?php endif; ?>

        <div class="mt-8 border-t border-slate-100 pt-6 flex justify-center">
            <a href="https://api.vidyut-suvidha.in/images/apc_bulk_ocr_09_10_v1.apk" download class="flex items-center gap-2.5 bg-slate-100 text-slate-700 text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-slate-200 transition">
                <i class="fas fa-cloud-download-alt text-primary text-base animate-bounce"></i>
                Download Bescom APK
            </a>
        </div>

        <footer class="mt-6 text-center space-y-2">
            <div class="flex items-center justify-center gap-1.5 text-slate-500 font-bold text-xs uppercase tracking-wider">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Assetplus Consulting
            </div>
            <p class="text-[10px] text-slate-400">
                © 2026 Assetplus Consulting. All rights reserved.
            </p>
        </footer>
    </div>
</body>
</html>
