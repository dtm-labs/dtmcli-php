# 分布式事务管理器dtm的客户端sdk

# 使用示例

```
composer require yedf/dtmcli-php:dev-main
```

```php
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
```

# 可运行的使用示例

见[https://github.com/yedf/dtmcli-php-sample](https://github.com/yedf/dtmcli-php-sample)

# 特别说明
使用[mix/guzzle](https://github.com/mix-php/guzzle)为http客户端，支持swoole携程请求
