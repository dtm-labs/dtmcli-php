a client for distributed transaction manager [dtm](https://github.com/yedf/dtm)


`dtmcli` 是分布式事务管理器[dtm](https://github.com/yedf/dtm)的客户端sdk

## dtm分布式事务管理服务

DTM是一款跨语言的开源分布式事务管理器，优雅的解决了幂等、空补偿、悬挂等分布式事务难题。提供了简单易用、高性能、易水平扩展的分布式事务解决方案。

## 亮点

* 极易接入
  - 支持HTTP，提供非常简单的接口，极大降低上手分布式事务的难度，新手也能快速接入
* 使用简单
  - 开发者不再担心悬挂、空补偿、幂等各类问题，框架层代为处理
* 跨语言
  - 可适合多语言栈的公司使用。方便go、python、php、nodejs、ruby各类语言使用。
* 易部署、易扩展
  - 仅依赖mysql，部署简单，易集群化，易水平扩展
* 多种分布式事务协议支持
  - TCC、SAGA、XA、事务消息

## 与其他框架对比

目前开源的分布式事务框架，暂未看到非Java语言有成熟的框架。而Java语言的较多，有阿里的SEATA、华为的ServiceComb-Pack，京东的shardingsphere，以及himly，tcc-transaction，ByteTCC等等，其中以seata应用最为广泛。

下面是dtm和seata的主要特性对比：

|  特性| DTM | SEATA |备注|
|:-----:|:----:|:----:|:----:|
| 支持语言 |<span style="color:green">Golang、python、php及其他</span>|<span style="color:orange">Java</span>|dtm可轻松接入一门新语言|
|异常处理| <span style="color:green">[子事务屏障自动处理](https://zhuanlan.zhihu.com/p/388444465)</span>|<span style="color:orange">手动处理</span> |dtm解决了幂等、悬挂、空补偿|
| TCC事务| <span style="color:green">✓</span>|<span style="color:green">✓</span>||
| XA事务|<span style="color:green">✓</span>|<span style="color:green">✓</span>||
|AT事务|<span style="color:red">✗</span>|<span style="color:green">✓</span>|AT与XA类似，性能更好，但有脏回滚|
| SAGA事务 |<span style="color:orange">简单模式</span> |<span style="color:green">状态机复杂模式</span> |dtm的状态机模式在规划中|
|事务消息|<span style="color:green">✓</span>|<span style="color:red">✗</span>|dtm提供类似rocketmq的事务消息|
|通信协议|HTTP|dubbo等协议，无HTTP|dtm后续将支持grpc类协议|
|star数量|<img src="https://img.shields.io/github/stars/yedf/dtm.svg?style=social" alt="github stars"/>|<img src="https://img.shields.io/github/stars/seata/seata.svg?style=social" alt="github stars"/>|dtm从20210604发布0.1，发展快|

从上面对比的特性来看，如果您的语言栈包含了Java之外的语言，那么dtm是您的首选。如果您的语言栈是Java，您也可以选择接入dtm，使用子事务屏障技术，简化您的业务编写。

## 使用示例

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
使用[mix/guzzle](https://github.com/mix-php/guzzle)为http客户端，支持swoole协程请求
