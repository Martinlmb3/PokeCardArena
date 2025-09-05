<?php
require_once 'vendor/autoload.php';
if (!class_exists('App\Models\Pokemaster')) {
    echo 'ERROR: Pokemaster class not found!';
    exit(1);
}
echo 'SUCCESS: Pokemaster class loaded correctly';
