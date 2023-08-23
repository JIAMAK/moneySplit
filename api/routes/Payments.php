<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post($DEFAULT_URL . 'pay/update', function (Request $request, Response $response, $args) {
    $input = json_decode(file_get_contents('php://input'), true);
    $database = new Connection();

    $db = $database->getConnection();
    $payment = new Payments($db);
    $payment->params = $input;
    $result = $payment->updatePayments();
    $response->getBody()->write($result);
    if ($result) {
        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    } else {
        return $response->withHeader("Content-Type", "application/json")->withStatus(400);
    }

    return $response;
});

$app->post($DEFAULT_URL . 'pay', function (Request $request, Response $response, $args) {
    $input = json_decode(file_get_contents('php://input'), true);
    $database = new Connection();

    $db = $database->getConnection();
    $payment = new Payments($db);
    $payment->params = $input;
    $result = $payment->addPayments();
    $response->getBody()->write($result);
    if ($result) {
        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    } else {
        return $response->withHeader("Content-Type", "application/json")->withStatus(400);
    }

    return $response;
});

$app->get($DEFAULT_URL . 'payments', function (Request $request, Response $response, $args) {
    $database = new Connection();

    $db = $database->getConnection();
    $payment = new Payments($db);
    $payment->id = $request->getQueryParams()['id'];
    $result = $payment->getPayments();
    $response->getBody()->write($result);
    if ($result) {
        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    } else {
        return $response->withHeader("Content-Type", "application/json")->withStatus(400);
    }

    return $response;
});

$app->get($DEFAULT_URL . 'payback_transaction', function (Request $request, Response $response, $args) {
    $database = new Connection();

    $db = $database->getConnection();
    $payment = new Payments($db);
    $payment->id = $request->getQueryParams()['id'];
    $result = $payment->getPayback();
    $response->getBody()->write($result);

    return $response;
});
