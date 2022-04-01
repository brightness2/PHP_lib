# 基于phalapi的拦截器

## 使用方法
1、在PhalApi 应用类中或其派生类的response方法添加如下代码
```
 $rs = DI()->response;
 $api    = PhalApi_ApiFactory::generateService();
 $InterceptorManage = new Interceptor_Lib;
 $data = $InterceptorManage->action($api);
 $rs->setData($data);

```
如果response方法有其它的逻辑，自行调整。

2、创建一个拦截器并实现Interceptor_Handle 接口
```
class Interceptor_Test implements Interceptor_Handle{

    public function handle(PhalApi_Api $api,$data=null)
    {
        echo 'test interceptor<br/>';
    }
}
```
3、config文件夹中增加配置文件interceptor.php
```
/**
 * 拦截器的执行顺序，按照数组排序的顺序执行
 */
return array(
    //所有全局前置
    'beforeInterceptors'=>[
        Interceptor_Test::class,
    ],
    //所有全局后置拦截器,
    'afterInterceptors'=>[
        //Interceptor_Other::class,
    ],
);
```
注：后置拦截器执行在数据输出前
## api级别的拦截器
api级别的拦截器，只有请求当前api类的方法时才会触发

在 Phalapi_Api 类或其派生类中增加 $beforeInterceptors属性和$afterInterceptors属性
```
class Api_Test extends MTS_ZApi
{
 
    // api级别的前置拦截器
    protected $beforeInterceptors=[
        Interceptor_Before::class,
    ];
    //api级别的后置拦截器
    protected $afterInterceptors=[
        // Interceptor_After::class,
    ];
    
   //to do ...
}
```

