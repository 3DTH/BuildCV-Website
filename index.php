<?php
session_start();

// Load config
require_once __DIR__ . '/app/config/config.php';

// Autoload các class
spl_autoload_register(function ($class_name) {
    // Các thư mục chứa class
    $directories = [
        APP_PATH . '/models/',
        APP_PATH . '/controllers/',
        APP_PATH . '/config/',
        APP_PATH . '/helpers/'
    ];
    
    // Duyệt qua từng thư mục để tìm file
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Lấy URL từ request
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định controller và action
$controllerName = !empty($url[0]) ? ucfirst($url[0]) : 'Home';
$actionName = !empty($url[1]) ? str_replace('-', '', $url[1]) : 'index';

// Xử lý các trường hợp đặc biệt
if ($controllerName === 'Admin' && !empty($url[1])) {
    // Xử lý controller trong thư mục admin
    $controllerName = ucfirst($url[1]);
    $actionName = !empty($url[2]) ? $url[2] : 'index';
    $controllerFile = APP_PATH . '/controllers/admin/' . $controllerName . 'Controller.php';
    $controllerName .= 'Controller';
    $params = array_slice($url, 3);
} else if (in_array(strtolower($controllerName), ['auth', 'register', 'login', 'logout'])) {
    $controllerName = 'AuthController';
    if ($controllerName === 'AuthController' && empty($actionName)) {
        $actionName = strtolower($url[0]) === 'auth' ? 'login' : strtolower($url[0]);
    }
} else {
    $controllerName .= 'Controller';
    $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
    $params = array_slice($url, 2);
}

// Kiểm tra file controller tồn tại
$controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    die('404 - Không tìm thấy trang');
} else {
    $params = array_slice($url, 2);
}

// Load controller
require_once $controllerFile;
$controller = new $controllerName();

// Kiểm tra method tồn tại
if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    die('404 - Không tìm thấy trang');
}

// Gọi method với params
if (!empty($params)) {
    call_user_func_array([$controller, $actionName], $params);
} else {
    call_user_func([$controller, $actionName]);
}