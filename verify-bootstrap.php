<?php

/**
 * Bootstrap verification for PokeCardArena
 * This file ensures all required classes are properly loaded
 */

// Ensure we have the autoloader
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('ERROR: Composer autoloader not found. Run "composer install" first.' . PHP_EOL);
}

require_once __DIR__ . '/vendor/autoload.php';

// Verify critical classes
$criticalClasses = [
    'App\\Models\\Pokemaster',
    'App\\Http\\Controllers\\PokemonMasterController',
    'App\\Models\\Pokemon',
    'App\\Models\\Pokedex'
];

$missing = [];
foreach ($criticalClasses as $class) {
    if (!class_exists($class)) {
        $missing[] = $class;
    }
}

if (!empty($missing)) {
    echo 'ERROR: Missing critical classes:' . PHP_EOL;
    foreach ($missing as $class) {
        echo '  - ' . $class . PHP_EOL;
    }

    // Try to give helpful debug info
    echo PHP_EOL . 'Debug info:' . PHP_EOL;
    echo 'Composer autoload file exists: ' . (file_exists(__DIR__ . '/vendor/autoload.php') ? 'YES' : 'NO') . PHP_EOL;
    echo 'App directory exists: ' . (is_dir(__DIR__ . '/app') ? 'YES' : 'NO') . PHP_EOL;
    echo 'Models directory exists: ' . (is_dir(__DIR__ . '/app/Models') ? 'YES' : 'NO') . PHP_EOL;
    echo 'Pokemaster.php exists: ' . (file_exists(__DIR__ . '/app/Models/Pokemaster.php') ? 'YES' : 'NO') . PHP_EOL;

    exit(1);
}

echo 'SUCCESS: All critical classes loaded successfully!' . PHP_EOL;

// If we're in a Laravel context, also verify auth configuration
if (class_exists('Illuminate\\Support\\Facades\\App') && function_exists('app')) {
    try {
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

        // We can't easily check auth config without full bootstrap,
        // but at least we know Laravel can be instantiated
        echo 'Laravel application can be instantiated: YES' . PHP_EOL;
    } catch (Exception $e) {
        echo 'WARNING: Laravel bootstrap failed: ' . $e->getMessage() . PHP_EOL;
    }
}

echo 'Bootstrap verification completed successfully!' . PHP_EOL;
