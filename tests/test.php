<?php

require __DIR__ . '/../vendor/autoload.php';

function FireTcc () {
    $dtm = 'http://192.168.69.128:8080/api/dtmsvr';
    $svc = 'http://192.168.69.128:4005/api';

    Dtmcli\tccGlobalTransaction($dtm, function ($tcc) use ($svc) {
        /** @var Dtmcli\Tcc $tcc */

        $req = ['amount' => 30];
        echo 'calling trans out' . PHP_EOL;
        $tcc->callBranch($req, $svc . '/TransOutTry', $svc . '/TransOutConfirm', $svc . '/TransOutCancel');
        echo 'calling trans in' . PHP_EOL;
        $tcc->callBranch($req, $svc . '/TransInTry', $svc . '/TransInConfirm', $svc . '/TransInCancel');
    });
}

$vega = new Mix\Vega\Engine();
$vega->handleFunc('/api/TransOutTry', function (Mix\Vega\Context $ctx) {
    var_dump('TransOutTry', $ctx->request->getQueryParams(), $ctx->request->getParsedBody());
    $ctx->JSON(200, ['result' => 'SUCCESS']);
})->methods('POST');
$vega->handleFunc('/api/TransOutConfirm', function (Mix\Vega\Context $ctx) {
    var_dump('TransOutConfirm', $ctx->request->getQueryParams(), $ctx->request->getParsedBody());
    $ctx->JSON(200, ['result' => 'SUCCESS']);
})->methods('POST');
$vega->handleFunc('/api/TransOutCancel', function (Mix\Vega\Context $ctx) {
    var_dump('TransOutCancel', $ctx->request->getQueryParams(), $ctx->request->getParsedBody());
    $ctx->JSON(200, ['result' => 'SUCCESS']);
})->methods('POST');

$vega->handleFunc('/api/TransInTry', function (Mix\Vega\Context $ctx) {
    var_dump('TransInTry', $ctx->request->getQueryParams(), $ctx->request->getParsedBody());
    $ctx->JSON(200, ['result' => 'SUCCESS']);
})->methods('POST');
$vega->handleFunc('/api/TransInConfirm', function (Mix\Vega\Context $ctx) {
    var_dump('TransInConfirm', $ctx->request->getQueryParams(), $ctx->request->getParsedBody());
    $ctx->JSON(200, ['result' => 'SUCCESS']);
})->methods('POST');
$vega->handleFunc('/api/TransInCancel', function (Mix\Vega\Context $ctx) {
    var_dump('TransInCancel', $ctx->request->getQueryParams(), $ctx->request->getParsedBody());
    $ctx->JSON(200, ['result' => 'SUCCESS']);
})->methods('POST');

$vega->handleFunc('/api/FireTcc', function (Mix\Vega\Context $ctx) {
    FireTcc();
    $ctx->JSON(200, ['result' => 'SUCCESS']);
})->methods('POST');

$http_worker = new Workerman\Worker("http://0.0.0.0:4005");
$http_worker->onMessage = $vega->handler();
$http_worker->count = 4;
Workerman\Worker::runAll();
