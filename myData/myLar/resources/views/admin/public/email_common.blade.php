<!DOCTYPE html>
<html>

{{--邮件公共模板--}}
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>框架测试 - 写信</title>
    <meta name="keywords" content="优雅的框架测试">
    <meta name="description" content="优雅的框架测试">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="{{asset('css/bootstrap.min14ed.css?v=3.3.6')}}" rel="stylesheet">
    <link href="{{asset('css/font-awesome.min93e3.css?v=4.4.0')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/summernote/summernote-bs3.css')}}" rel="stylesheet">
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.min862f.css?v=4.1.0')}}" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-content mailbox-content">
                    <div class="file-manager">
                        <a class="btn btn-block btn-primary compose-mail" href="mail_compose.blade.php">写信</a>
                        <div class="space-25"></div>
                        <h5>文件夹</h5>
                        <ul class="folder-list m-b-md" style="padding: 0">
                            <li>
                                <a href="mailbox.blade.php"> <i class="fa fa-inbox "></i> 收件箱 <span
                                            class="label label-warning pull-right">16</span>
                                </a>
                            </li>
                            <li>
                                <a href="mailbox.blade.php"> <i class="fa fa-envelope-o"></i> 发信</a>
                            </li>
                            <li>
                                <a href="mailbox.blade.php"> <i class="fa fa-certificate"></i> 重要</a>
                            </li>
                            <li>
                                <a href="mailbox.blade.php"> <i class="fa fa-file-text-o"></i> 草稿 <span
                                            class="label label-danger pull-right">2</span>
                                </a>
                            </li>
                            <li>
                                <a href="mailbox.blade.php"> <i class="fa fa-trash-o"></i> 垃圾箱</a>
                            </li>
                        </ul>
                        <h5>分类</h5>
                        <ul class="category-list" style="padding: 0">
                            <li>
                                <a href="mail_compose.blade.php#"> <i class="fa fa-circle text-navy"></i> 工作</a>
                            </li>
                            <li>
                                <a href="mail_compose.blade.php#"> <i class="fa fa-circle text-danger"></i> 文档</a>
                            </li>
                            <li>
                                <a href="mail_compose.blade.php#"> <i class="fa fa-circle text-primary"></i> 社交</a>
                            </li>
                            <li>
                                <a href="mail_compose.blade.php#"> <i class="fa fa-circle text-info"></i> 广告</a>
                            </li>
                            <li>
                                <a href="mail_compose.blade.php#"> <i class="fa fa-circle text-warning"></i> 客户端</a>
                            </li>
                        </ul>

                        <h5 class="tag-title">标签</h5>
                        <ul class="tag-list" style="padding: 0">
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 朋友</a>
                            </li>
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 工作</a>
                            </li>
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 家庭</a>
                            </li>
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 孩子</a>
                            </li>
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 假期</a>
                            </li>
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 音乐</a>
                            </li>
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 照片</a>
                            </li>
                            <li><a href="mail_compose.blade.php"><i class="fa fa-tag"></i> 电影</a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        @section('content')
        @show
    </div>
</div>
<script src="{{asset('js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{asset('js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{asset('js/content.min.js?v=1.0.0')}}"></script>
<script src="{{asset('js/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('js/plugins/summernote/summernote.min.js')}}"></script>
<script src="{{asset('js/plugins/summernote/summernote-zh-CN.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",});
        $(".summernote").summernote({lang: "zh-CN"})
    });
    var edit = function () {
        $(".click2edit").summernote({focus: true})
    };
    var save = function () {
        var aHTML = $(".click2edit").code();
        $(".click2edit").destroy()
    };
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
</body>


</html>