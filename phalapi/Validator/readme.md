# 验证器 迁移于thinkphp5
具体使用参考 ThinkPHP5 验证 文档 https://www.kancloud.cn/manual/thinkphp5/129319

# 使用方法

创建一个类继承 Validator_Lib
```
class Validate_Test extends Validator_Lib{
    //验证规则
    protected $rule = [
        "name|姓名"=>"require|max:6",
        "age|年龄"=>"gt:10",
    ];
    //错误信息
    protected $message = [
        'name.require'=>'姓名必填',
        'name.max'=>'姓名最长6个字符',
        'age.gt'=>"年龄必须大于10岁",

    ];

    //验证场景，具体用法参考thinkphp5文档
    protected $scene = [];
}
```
验证数据
```
$validate = new Validate_Test();
$data = [
    'name'=>'test',
    'age'=>2,
];
$bool = $validate->check($data);
if(!$bool){
    return $validate->getError();
}
```
# 内置的验证规则
```
  confirm(mixed $field, string $msg = '') static 验证是否和某个字段的值一致
  different(mixed $field, string $msg = '') static 验证是否和某个字段的值是否不同
  egt(mixed $value, string $msg = '') static 验证是否大于等于某个值
  gt(mixed $value, string $msg = '') static 验证是否大于某个值
  elt(mixed $value, string $msg = '') static 验证是否小于等于某个值
  lt(mixed $value, string $msg = '') static 验证是否小于某个值
  eg(mixed $value, string $msg = '') static 验证是否等于某个值
  in(mixed $values, string $msg = '') static 验证是否在范围内
  notIn(mixed $values, string $msg = '') static 验证是否不在某个范围
  between(mixed $values, string $msg = '') static 验证是否在某个区间
  notBetween(mixed $values, string $msg = '') static 验证是否不在某个区间
  length(mixed $length, string $msg = '') static 验证数据长度
  max(mixed $max, string $msg = '') static 验证数据最大长度
  min(mixed $min, string $msg = '') static 验证数据最小长度
  after(mixed $date, string $msg = '') static 验证日期
  before(mixed $date, string $msg = '') static 验证日期
  expire(mixed $dates, string $msg = '') static 验证有效期
  allowIp(mixed $ip, string $msg = '') static 验证IP许可
  denyIp(mixed $ip, string $msg = '') static 验证IP禁用
  regex(mixed $rule, string $msg = '') static 使用正则验证数据
  token(mixed $token, string $msg = '') static 验证表单令牌
  is(mixed $rule = null, string $msg = '') static 验证字段值是否为有效格式
  isRequire(mixed $rule = null, string $msg = '') static 验证字段必须
  isNumber(mixed $rule = null, string $msg = '') static 验证字段值是否为数字
  isArray(mixed $rule = null, string $msg = '') static 验证字段值是否为数组
  isInteger(mixed $rule = null, string $msg = '') static 验证字段值是否为整形
  isFloat(mixed $rule = null, string $msg = '') static 验证字段值是否为浮点数
  isMobile(mixed $rule = null, string $msg = '') static 验证字段值是否为手机
  isIdCard(mixed $rule = null, string $msg = '') static 验证字段值是否为身份证号码
  isChs(mixed $rule = null, string $msg = '') static 验证字段值是否为中文
  isChsDash(mixed $rule = null, string $msg = '') static 验证字段值是否为中文字母及下划线
  isChsAlpha(mixed $rule = null, string $msg = '') static 验证字段值是否为中文和字母
  isChsAlphaNum(mixed $rule = null, string $msg = '') static 验证字段值是否为中文字母和数字
  isDate(mixed $rule = null, string $msg = '') static 验证字段值是否为有效格式
  isBool(mixed $rule = null, string $msg = '') static 验证字段值是否为布尔值
  isAlpha(mixed $rule = null, string $msg = '') static 验证字段值是否为字母
  isAlphaDash(mixed $rule = null, string $msg = '') static 验证字段值是否为字母和下划线
  isAlphaNum(mixed $rule = null, string $msg = '') static 验证字段值是否为字母和数字
  isAccepted(mixed $rule = null, string $msg = '') static 验证字段值是否为yes, on, 或是 1
  isEmail(mixed $rule = null, string $msg = '') static 验证字段值是否为有效邮箱格式
  isUrl(mixed $rule = null, string $msg = '') static 验证字段值是否为有效URL地址
  activeUrl(mixed $rule = null, string $msg = '') static 验证是否为合格的域名或者IP
  ip(mixed $rule = null, string $msg = '') static 验证是否有效IP
  fileExt(mixed $ext, string $msg = '') static 验证文件后缀
  fileMime(mixed $mime, string $msg = '') static 验证文件类型
  fileSize(mixed $size, string $msg = '') static 验证文件大小
  image(mixed $rule, string $msg = '') static 验证图像文件
  method(mixed $method, string $msg = '') static 验证请求类型
  dateFormat(mixed $format, string $msg = '') static 验证时间和日期是否符合指定格式
  unique(mixed $rule, string $msg = '') static 验证是否唯一
  behavior(mixed $rule, string $msg = '') static 使用行为类验证
  filter(mixed $rule, string $msg = '') static 使用filter_var方式验证
  requireIf(mixed $rule, string $msg = '') static 验证某个字段等于某个值的时候必须
  requireCallback(mixed $rule, string $msg = '') static 通过回调方法验证某个字段是否必须
  requireWith(mixed $rule, string $msg = '') static 验证某个字段有值的情况下必须
  must(mixed $rule = null, string $msg = '') static 必须验证
 
```
