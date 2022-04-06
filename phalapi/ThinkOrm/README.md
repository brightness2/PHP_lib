# think-orm

## 在phalapi使用
注册ThinkOrm， init.php 文件中添加
```php
//注册ThinkOrm
DI()->thinkorm = new ThinkOrm_Lib();
```
配置
新建 config/database.php 文件
```php
return [
    // 数据库类型
    'type'            => 'mysql',
    // 服务器地址
    'hostname'        => '127.0.0.1',
    // 数据库名
    'database'        => 'test',
    // 用户名
    'username'        => 'root',
    // 密码
    'password'        => 'root',
    // 端口
    'hostport'        => '',
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => '',
    // 数据库调试模式
    'debug'           => false,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 是否严格检查字段是否存在
    'fields_strict'   => true,
    // 数据集返回类型
    'resultset_type'  => '',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,
    
];
```
Db类用法 
```php
//select 查询
ThinkOrm_Db::table('admin')
            ->where('id','>',0)
            ->order('id','desc')
            ->limit(10)
            ->select();
```
Model用法
```php
class TModel_Admin extends ThinkOrm_Model
{
    /**
     * 模型名称,必须设置，值为省略表前缀的表名
     * @var string
     */
    protected $name = 'admin';
}
//根据主键获取一条数据
return TModel_Admin::get(2);
```
为了适配phalapi，必须使用ThinkOrm_Db类，或继承ThinkOrm_Model
更多用法可以参考5.1完全开发手册的[模型](https://www.kancloud.cn/manual/thinkphp5_1/354041)章节
