<?php

namespace App;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);

        $filePath = __DIR__ . '/../resources/views/' . $view . '.php';
        $absolutePath = realpath($filePath);

        if ($absolutePath && file_exists($absolutePath)) {
            include $absolutePath;
        } else {
            http_response_code(404);
            include __DIR__ . '/../runtime/errors/404.php';
        }
    }
}
