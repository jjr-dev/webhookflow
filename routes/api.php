<?php
    use App\Controllers\Api;

    $router->any('/{id}', [
        function($request, $response) {
            return Api\Request::receive($request, $response);
        }
    ]);

    $router->get('/api/v1/requests', [
        function($request, $response) {
            return Api\Request::list($request, $response);
        }
    ]);

    $router->get('/api/v1/requests/{id}', [
        function($request, $response) {
            return Api\Request::find($request, $response);
        }
    ]);