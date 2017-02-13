/**
 * Created by Administrator on 2017-01-10.
 */

    $(function(){
        $('#myForm').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                username: {
                    validators: {
                        notEmpty: {
                            message: '登录名不能为空'  //非空验证
                        },
                        stringLength: {
                            min: 4,
                            max: 20,
                            message: '登录名长度必须在4到20之间'  //登录长度验证
                        },
                        threshold :  4 ,  //有6字符以上才发送ajax请求，
                        remote: {
                            url: '__MODULE__/Admin/checkuser',//验证地址
                            message: '用户已存在',//提示消息
                            delay :  2000,//2秒发送一次ajax
                            type: 'POST'//请求方式
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_\.]+$/,
                            message: '用户名由数字字母下划线和.组成'    //字符串格式验证
                        },
                        regexp1: {
                            regexp: /^[^`~!@#$%^&*()+=|\\\][\]\{\}:;'\,.<>/?]{1}[^`~!@$%^&()+=|\\\][\]\{\}:;'\,.<>?]{0,19}$/,
                            message: '真实姓名不要使用特殊符号'  //特殊符号验证
                        },
                        different: {
                            field: 'username',//需要进行比较的input name值
                            message: '不能和用户名相同'  //相同验证
                        },
                        identical: {
                            field: 'password',
                            message: '两次密码不一致'  //不同验证
                        }
                    }
                }
            },
            submitHandler: function(validator, form, submitButton) {
                // a)
                // Use Ajax to submit form data
                //$.post(form.attr('action'), form.serialize(), function(result) {
                // ... process the result ...
                //}, 'json');

                //b)
                // Do your task
                // ...
                // Submit the form
                validator.defaultSubmit();
            }

        });
    })
