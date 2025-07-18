<?php
namespace Memo\System;
class Route
{
    protected static $routes = [
        'GET' => [],
        'POST' => [],
        'DELETE' => [],
        'PUT' => [],
    ];

    protected static $middlewares = [];

    public static function get($path, $callback)
    {
        self::$routes['GET'][$path] = $callback;
    }

    public static function post($path, $callback)
    {
        self::$routes['POST'][$path] = $callback;
    }

    public static function delete($path, $callback)
    {
        self::$routes['DELETE'][$path] = $callback;
    }

    public static function put($path, $callback)
    {
        self::$routes['PUT'][$path] = $callback;
    }

    public static function middleware($callback)
    {
        self::$middlewares[] = $callback;
    }

    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$middlewares as $middleware) {
            if (call_user_func($middleware) === false) {
                // 미들웨어에서 false 반환 시 중단
                return;
            }
        }

        // Route 클래스의 dispatch() 내 콜백 실행 부분 예시
        if (isset(self::$routes[$method][$uri])) {
            $callback = self::$routes[$method][$uri];
            if (is_array($callback) && count($callback) === 2) {
                $className = $callback[0];
                $methodName = $callback[1];
                $instance = new $className();

                // 리플렉션으로 파라미터 타입 확인
                $refMethod = new \ReflectionMethod($className, $methodName);
                $params = [];
                foreach ($refMethod->getParameters() as $param) {
                    $type = $param->getType();
                    if ($type && method_exists($type, 'isBuiltin') && !$type->isBuiltin()) {
                        // PHP 7.1 이상: getName(), 이하: (string)$type
                        $paramClass = method_exists($type, 'getName') ? $type->getName() : (string)$type;
                        $params[] = new $paramClass();
                    }
                }
                echo $refMethod->invokeArgs($instance, $params);
            } else {
                call_user_func($callback);
            }
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }
}