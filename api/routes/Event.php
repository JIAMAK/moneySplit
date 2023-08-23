<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get($DEFAULT_URL . 'event', function (Request $request, Response $response, $args) {
    $database = new Connection();
    $db = $database->getConnection();
    $id = $request->getQueryParams()['id'];

    $event = new Event($db);
    $event->id = $id;
    $resp = $event->get();

    $response->getBody()->write($resp);

    return $response;
});

$app->post($DEFAULT_URL . 'event/add', function (Request $request, Response $response, $args) {
    $input = json_decode(file_get_contents('php://input'), true);
    $database = new Connection();

    $db = $database->getConnection();
    $event = new Event($db);
    $event->params = $input;
    $result = $event->add();
    $response->getBody()->write($result);
    if ($result) {
        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    } else {
        return $response->withHeader("Content-Type", "application/json")->withStatus(400);
    }

    return $response;
});


