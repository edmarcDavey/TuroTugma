<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col bg-white">
            <?php if(View::exists('layouts.navigation')): ?>
                <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main class="flex-1">
                <?php echo e($slot); ?>

            </main>

            <!-- Global Footer -->
            <footer class="relative z-20 border-t bg-white">
                <div class="max-w-7xl mx-auto px-8 py-2 text-center">
                    <div class="text-xs text-gray-600">© <?php echo e(date('Y')); ?> TuroTugma. All Rights Reserved.</div>
                </div>
            </footer>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\Admin\TuroTugma\resources\views\layouts\app.blade.php ENDPATH**/ ?>