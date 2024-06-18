<?php

$app->add(function ($request, $response, $next) {
    $uri = $request->getUri();
    error_log("Request URI: " . $uri);
    $response = $next($request, $response);
    return $response;
});
