<?php

// 모든 에러를 화면에 출력
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// index.php 상단에 추가
spl_autoload_register(function ($class) {
    $prefix = 'Memo\\';
    $base_dir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // 네임스페이스 프리픽스가 다르면 무시
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});


use Memo\System\Route;
Route::get('/hello', [\Memo\HelloWorld::class, 'say']);




// 요청 처리
Route::dispatch();