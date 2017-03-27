<?php
/**
 * User: Liu
 * Date: 2017/1/9
 * Time: 14:39
 */
/**
 * 载入admin目录模板
 * @param $template
 * @return mixed
 */
function admin_view($template)
{
    $params = func_get_args();//获取函数传入的参数列表 数组
    $params[0] = 'admin.'.$params[0];
    return call_user_func_array('view' ,$params );//调用回调函数，并把一个数组参数作为回调函数的参数
}

/**
 * 载入home目录模板
 * @param $template
 * @return mixed
 */
function home_view($template)
{
    $params = func_get_args();//获取函数传入的参数列表 数组
    $params[0] = 'home.'.$params[0];
    return call_user_func_array('view' ,$params );//调用回调函数，并把一个数组参数作为回调函数的参数
}

/**
 * 检测是否是手机号
 * @param $mobile
 * @return int
 */
function is_mobile($mobile)
{
    return preg_match('/1[3|4|5|7|8]{1}\d{9}/' , $mobile);
}

/**
 * 检测是否是邮箱
 * @param $email
 * @return int
 */
function is_email($email)
{
    return preg_match('/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/' , $email);
}

/**
 * 上传base64编码的缩略图
 * @param $thumb
 * @return string
 */
function upload_base64_thumb($thumb)
{
    if( empty($thumb) ) return '';
    if( strpos($thumb ,'data:image') === false ) return $thumb;
    $filepath = '/uploads/thumbs/'.date('Ym').'/';//缩略图按月划分
    $fileroot = public_path().$filepath;
    if( !is_dir($fileroot) ) mkdir($fileroot ,0777,true);

    $filename = time().rand(100000,999999);
    $fileext = str_replace('data:image/' , '' , strstr($thumb , ';' ,true));
    in_array($fileext , ['jpg','png','gif','bmp']) or $fileext = 'jpg';//jpeg->jpg
    $filename .= '.' . $fileext;

    if( preg_match('/^(data:\s*image\/(\w+);base64,)/' , $thumb ,$result) )
    {
        $result = file_put_contents($fileroot.$filename , base64_decode(str_replace($result[1] , '' , $thumb)));

        if( $result )
        {
            $thumb = $filepath.$filename;
        }
        else
        {
            $thumb = '';
        }
    }

    return $thumb;
}

function save_book_thumb($thumb,$dir = 'books')
{
    if( empty($thumb) ) return '';

    $filepath = '/uploads/' . $dir . '/'.date('Ym/d').'/';//缩略图按月划分
    $fileroot = public_path().$filepath;
    if( !is_dir($fileroot) ) mkdir($fileroot ,0777,true);

    $filename = time().rand(100000,999999);
    $fileext = str_replace('data:image/' , '' , strstr($thumb , ';' ,true));
    in_array($fileext , ['jpg','png','gif','bmp']) or $fileext = 'jpg';//jpeg->jpg
    $filename .= '.' . $fileext;

    if( preg_match('/^(data:\s*image\/(\w+);base64,)/' , $thumb ,$result) )
    {
        $result = file_put_contents($fileroot.$filename , base64_decode(str_replace($result[1] , '' , $thumb)));

        if( $result )
        {
            $thumb = $filepath.$filename;
        }
        else
        {
            $thumb = '';
        }
    }

    return $thumb;
}
/**
 * validate.js
 * @return string
 */
function jquery_validate_js()
{
    return <<<php
    <script src="/skin/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/skin/js/plugins/validate/messages_zh.min.js"></script>
php;
}

/**
 * 生成jquery.validate的默认设置
 * @return string
 */
function jquery_validate_default()
{
    $js = <<<php
    $.validator.setDefaults({
        highlight: function(a) {
            $(a).closest(".form-group").removeClass("has-success").addClass("has-error")
        },
        success: function(a) {
            a.closest(".form-group").removeClass("has-error").addClass("has-success")
        },
        errorElement: "span",
        errorPlacement: function(a, b) {
            if (b.is(":radio") || b.is(":checkbox")) {
                a.appendTo(b.parent().parent().parent())
            } else {
                a.appendTo(b.parent())
            }
        },
        errorClass: "help-block m-b-none",
        validClass: "help-block m-b-none"
    });
php;
    return $js;

}

/**
 * obj转为数组
 * @param $obj
 * @return mixed
 */
function obj2arr($obj)
{
    if(is_object($obj))
    {
        return json_decode(json_encode($obj) , true);
    }
    return $obj;
}

/**
 * 根据route名称返回URL
 * @param $route
 * @return string
 */
function route2url($route = '')
{
    if(empty($route)) return '/';
    try{
        return route($route);
    }catch (\Exception $exception) {
        return '';
    }
}


/**
 * @param $id
 * @param string $editor
 * @param string $extends
 * @return bool
 */
function seditor($content = '' , $name = 'content', $editor = 'ueditor', $extends = '')
{

    if( $editor == 'kindeditor' )
    {
        $url = "/plugins/editor/kindeditor/kindeditor.js";
        $lang = "/plugins/editor/kindeditor/lang/zh_CN.js";
        echo "<script charset='utf-8' src='$url'></script>";
        echo "<script charset='utf-8' src='$lang'></script>";
        echo "<script>";
        echo " KindEditor.ready(function(K) { window.editor = K.create('#$name',{width:'100%',cssPath : '/plugins/editor/kindeditor/plugins/code/new.css',resizeMode:0});});";
        echo "</script>";

    }
    else if( $editor == 'ueditor' )
    {
        echo "<script id='content' type='text/plain' style='width:100%;height:500px;' name='{$name}' {$extends}>".$content."</script>";
        echo "<script type='text/javascript' src='/skin/plugins/editor/ueditor/ueditor.config.js'></script>";
        echo "<script type='text/javascript' src='/skin/plugins/editor/ueditor/ueditor.all.js'></script>";
        echo "<script type='text/javascript'> var ue = UE.getEditor('{$name}',{elementPathEnabled:false,contextMenu:[],enableAutoSave: false,saveInterval:500000});</script>";

    }
    else if( $editor == 'markdown' )
    {
        echo "<textarea name='".$name."' data-provide='markdown' {$extends}>".$content."</textarea>";
        echo "<link rel='stylesheet' type='text/css' href='/skin/plugins/editor/markdown/bootstrap-markdown.min.css' />";
        echo "<script type='text/javascript' src='/skin/plugins/editor/markdown/markdown.js'></script>";
        echo "<script type='text/javascript' src='/skin/plugins/editor/markdown/to-markdown.js'></script>";
        echo "<script type='text/javascript' src='/skin/plugins/editor/markdown/bootstrap-markdown.js'></script>";
        echo "<script type='text/javascript' src='/skin/plugins/editor/markdown/bootstrap-markdown.zh.js'></script>";

    }
    return false;
}

/**
 *
 * @param string $img
 * @return string
 */
function imgurl($img = '')
{
    if(!$img)
    {
        return '/skin/manager/images/nopic.png';
    }
    return $img;
}