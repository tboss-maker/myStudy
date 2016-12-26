@extends("admin.public.email_common")
@section("content")

    <div class="col-sm-9 animated fadeInRight">
        <div class="mail-box-header">
            <div class="pull-right tooltip-demo">
                <a href="{{url('admin/email/mailbox')}}" class="btn btn-white btn-sm" data-toggle="tooltip"
                   data-placement="top" title="存为草稿"><i class="fa fa-pencil"></i> 存为草稿</a>
                <a href="{{url('admin/email/mailbox')}}" class="btn btn-danger btn-sm" data-toggle="tooltip"
                   data-placement="top" title="放弃"><i class="fa fa-times"></i> 放弃</a>
            </div>
            <h2>
                写信
            </h2>
        </div>
        <div class="mail-box">


            <div class="mail-body">

                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">发送到：</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="i@zi-han.net">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">主题：</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="">
                        </div>
                    </div>
                </form>

            </div>

            <div class="mail-text h-200">

                <div class="summernote">
                    <h2>H+ 后台主题</h2>
                    <p>
                        H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术，她提供了诸多的强大的可以重新组合的UI组件，并集成了最新的jQuery版本，当然，也集成了很多功能强大，用途广泛的国内外jQuery插件及其他组件，她可以用于所有的Web应用程序，如<b>网站管理后台</b>，<b>网站会员中心</b>，<b>CMS</b>，<b>CRM</b>，<b>OA</b>等等，当然，您也可以对她进行深度定制，以做出更强系统。
                    </p>
                    <p>
                        <b>当前版本：</b>v4.1.0
                    </p>
                    <p>
                        <b>定价：</b><span class="label label-warning">&yen;988（不开发票）</span>
                    </p>

                </div>
                <div class="clearfix"></div>
            </div>
            <div class="mail-body text-right tooltip-demo">
                <a href="{{url('admin/email/mailbox')}}" class="btn btn-sm btn-primary" data-toggle="tooltip"
                   data-placement="top" title="Send"><i class="fa fa-reply"></i> 发送</a>
                <a href="{{url('admin/email/mailbox')}}" class="btn btn-white btn-sm" data-toggle="tooltip"
                   data-placement="top" title="Discard email"><i class="fa fa-times"></i> 放弃</a>
                <a href="{{url('admin/email/mailbox')}}" class="btn btn-white btn-sm" data-toggle="tooltip"
                   data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> 存为草稿</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@stop

