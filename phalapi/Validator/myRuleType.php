<?php
/*
 * @Author: Brightness
 * @Date: 2022-03-30 09:25:12
 * @LastEditors: Brightness
 * @LastEditTime: 2022-03-30 09:51:26
 * @Description:  扩展的验证类型
 */
 Validator_Lib::extend([
        //$value 是要验证的值；$rule 参数是 checkName 冒号后面的值，比如 checkName:kk, $rule 就是 kk
        'checkAge'=> function ($value,$rule) {
        return $rule == $value ? true : "年龄必须是{$rule}";
    },
    'checkStatus'=> [$this,'checkStatus'] //调用当前类的checkStatus方法，需要在类中定义checkStatus方法，扩展一般采用上面的方法，不用修改原代码
]);
