@extends("admin.public.email_common")
@section("content")


    <div class="col-sm-9 animated fadeInRight">
        <div class="mail-box-header">
            <div class="pull-right tooltip-demo">
                <a href="mail_compose.blade.php" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top"
                   title="回复"><i class="fa fa-reply"></i> 回复</a>
                <a href="mail_detail.blade.php#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top"
                   title="打印邮件"><i class="fa fa-print"></i> </a>
                <a href="mailbox.blade.php" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top"
                   title="标为垃圾邮件"><i class="fa fa-trash-o"></i> </a>
            </div>
            <h2>
                查看邮件
            </h2>
            <div class="mail-tools tooltip-demo m-t-md">


                <h3>
                    <span class="font-noraml">主题： </span>幼儿园亲子班（园中园）项目方案
                </h3>
                <h5>
                    <span class="pull-right font-noraml">2014年10月28日(星期二) 晚上8:20</span>
                    <span class="font-noraml">发件人： </span>i@zi-han.net
                </h5>
            </div>
        </div>
        <div class="mail-box">


            <div class="mail-body">
                <h4>尊敬的幼儿园园长朋友：</h4>
                <p>
                    贝贝聪教育，因您而精彩！由于婴幼教育一体化更符合婴幼儿成长需求，是全球早教专家、心理学家普遍推崇的一种办园模式。在美国、日本、英国、意大利、新加坡等国家及我国香港、台湾等地区，幼儿园普遍开设了亲子班，美国幼儿园开亲子班的比率为87%，意大利比率为83%。香港、台湾地区分别为74%、76%。2003年3月4日，国务院办公厅转发了教育部等10部门（单位）《关于幼儿教育改革与发展的指导意见》，强调发展0－6岁婴幼儿教育。在《幼儿园教育指导纲要（试行）》中已明确指出幼儿园教育要与0-3岁教育做好衔接。北京、上海等地要求在2013年，65%的公立一级幼儿园开设亲子班或园中园。
                </p>

                <p class="text-right">
                    贝贝聪教育科技发展有限公司
                </p>
            </div>
            <div class="mail-attachment">
                <p>
                    <span><i class="fa fa-paperclip"></i> 2 个附件 - </span>
                    <a href="mail_detail.blade.php#">下载全部</a> |
                    <a href="mail_detail.blade.php#">预览全部图片</a>
                </p>

                <div class="attachment">
                    <div class="file-box">
                        <div class="file">
                            <a href="mail_detail.blade.php#">
                                <span class="corner"></span>

                                <div class="icon">
                                    <i class="fa fa-file"></i>
                                </div>
                                <div class="file-name">
                                    Document_2014.doc
                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="file-box">
                        <div class="file">
                            <a href="mail_detail.blade.php#">
                                <span class="corner"></span>

                                <div class="image">
                                    <img alt="image" class="img-responsive" src="img/p1.jpg">
                                </div>
                                <div class="file-name">
                                    Italy street.jpg
                                </div>
                            </a>

                        </div>
                    </div>
                    <div class="file-box">
                        <div class="file">
                            <a href="mail_detail.blade.php#">
                                <span class="corner"></span>

                                <div class="image">
                                    <img alt="image" class="img-responsive" src="img/p2.jpg">
                                </div>
                                <div class="file-name">
                                    My feel.png
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="mail-body text-right tooltip-demo">
                <a class="btn btn-sm btn-white" href="mail_compose.blade.php"><i class="fa fa-reply"></i> 回复</a>
                <a class="btn btn-sm btn-white" href="mail_compose.blade.php"><i class="fa fa-arrow-right"></i> 下一封</a>
                <button title="" data-placement="top" data-toggle="tooltip" type="button" data-original-title="打印这封邮件"
                        class="btn btn-sm btn-white"><i class="fa fa-print"></i> 打印
                </button>
                <button title="" data-placement="top" data-toggle="tooltip" data-original-title="删除邮件"
                        class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> 删除
                </button>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@stop

