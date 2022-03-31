# 使用方式
这只是一个demo，根据具体业务扩展
```
use interceptor\InterceptorManage;
use interceptor\Interceptor;
class Test implements Interceptor {

    public function handle()
    {
        echo 'test handle<br/>';
    }
}

class Test2 implements Interceptor {

    public function handle()
    {
        echo 'test2 handle<br/>';
    }
}

$manage = new InterceptorManage;

$manage->attachEvent('before',Test::class);
$manage->attachEvent('after',Test2::class);

$manage->action();
/*
test handle
center
test2 handle
*/
```
