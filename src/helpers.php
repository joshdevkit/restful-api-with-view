<?php

use App\View;

/**
 * Render a view with data.
 *
 * @param string $view The view file name.
 * @param array $data The data to pass to the view.
 */
function view($view, $data = [])
{
    View::render($view, $data);
}
