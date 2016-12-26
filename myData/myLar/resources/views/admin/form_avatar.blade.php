@extends("admin.public.main")
@section('content')
<body class="gray-bg">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>管理员头像修改</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_editors.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_editors.html#">选项1</a>
                            </li>
                            <li><a href="form_editors.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <ul class="nav nav-tabs" id="avatar-tab">
                        <li class="active" id="upload"><a href="javascript:;">本地上传</a>
                        </li>
                        <li id="webcam"><a href="javascript:;">视频拍照</a>
                        </li>
                        <li id="albums"><a href="javascript:;">相册选取</a>
                        </li>
                    </ul>
                    <div id="uploader" class="wu-example">
                        <!--用来存放文件信息-->
                        <div id="thelist" class="uploader-list"></div>
                        <div class="btns">
                            <div id="picker">选择文件</div>
                            <button id="ctlBtn" class="btn btn-default">开始上传</button>
                        </div>
                    </div>
                    <div class="m-t m-b">
                        <div id="flash1">
                            <p id="swf1">本组件需要安装Flash Player后才可使用，请从<a
                                        href="http://www.adobe.com/go/getflashplayer">这里</a>下载安装。</p>
                        </div>
                        <div id="editorPanelButtons" style="display:none">
                            <p class="m-t">
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="src_upload">是否上传原图片？</label>
                            </p>
                            <p>
                                <a href="javascript:;" class="btn btn-w-m btn-primary button_upload"><i
                                            class="fa fa-upload"></i> 上传</a>
                                <a href="javascript:;" class="btn btn-w-m btn-white button_cancel">取消</a>
                            </p>
                        </div>
                        <p id="webcamPanelButton" style="display:none">
                            <a href="javascript:;" class="btn btn-w-m btn-info button_shutter"><i
                                        class="fa fa-camera"></i> 拍照</a>
                        </p>
                        <div id="userAlbums" style="display:none">
                            <a href="img/a1.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a1.jpg" alt="示例图片1"/>
                            </a>
                            <a href="img/a2.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a2.jpg" alt="示例图片2"/>
                            </a>
                            <a href="img/a3.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a3.jpg" alt="示例图片2"/>
                            </a>
                            <a href="img/a4.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a4.jpg" alt="示例图片2"/>
                            </a>
                            <a href="img/a5.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a5.jpg" alt="示例图片2"/>
                            </a>
                            <a href="img/a6.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a6.jpg" alt="示例图片2"/>
                            </a>
                            <a href="img/a7.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a7.jpg" alt="示例图片2"/>
                            </a>
                            <a href="img/a8.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a8.jpg" alt="示例图片2"/>
                            </a>
                            <a href="img/a9.jpg" class="fancybox" title="选取该照片">
                                <img src="img/a9.jpg" alt="示例图片2"/>
                            </a>>
                        </div>
                    </div>
                    <div class="alert alert-warning alert-dismissable m-t">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        - 本示例不提供图片上传功能，如果需要测试，请访问官网：<a href="http://www.fullavatareditor.com/demo.html" target="_blank">http://www.fullavatareditor.com/demo.html</a>
                        <br>- 测试 <b>视频拍照</b> 功能时，请注意允许<code>flash</code>和<code>浏览器</code>使用摄像头，否则可能会无法拍照。
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
