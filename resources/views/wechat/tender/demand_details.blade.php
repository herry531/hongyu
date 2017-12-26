@extends('wechat.layout.master')

@section('title','需求详情')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('asset/wechat/css/swiper-3.4.2.min.css')}}"/>
    <style>
        .swiper-container {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .XQbanner2 .swiper-pagination .swiper-pagination-bullet {
            width: 10px;
            height: 3px;
            background: rgba(0, 0, 0, 0.80) !important;
            border-radius: 13px;
        }

        .XQbanner2 .swiper-pagination .swiper-pagination-bullet-active {
            background: #F8525A !important;
            border-radius: 13px;
        }

        .XQbanner2 {
            overflow: hidden;
        }

        .XQbanner2 img {
            height: 100%;
        }
    </style>
@stop

@section('content')
    <div class="XQbanner2">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($goods->imgs as $v)
                    <div class="swiper-slide"><img src="{{$v->url}}"/></div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="clearfix Uneedtime XQtime">
        @if($demand->isCirculation())
            <div class="UneedButtonlanse fl ">
                <input type="button" value="循环红利">
            </div>
        @else
            @if($demand->data->is_issue)
                <div class="{{$demand->data->is_select?'UneedButtonhuise':'UneedButtonred'}} fl ">
                    <input type="button" value="{{$demand->data->is_select?'历史红利':'寻找红利'}}">
                </div>
            @else
                <div class="UneedButtonhuise fl ">
                    <input type="button" value="未发布">
                </div>
            @endif
        @endif

        {{--循环红利，时间的背景色"timespanBlue"--}}
        @if($demand->data->status==1||$demand->isCirculation())
            <div class="timespan fl {{$demand->isCirculation()?'timespanBlue':''}}" id="time1">
                <span class="day_show">0</span>&nbsp;<em>天</em>
                <span class="hour_show"><s id="h"></s>0</span>&nbsp;<em>时</em>
                <span class="minute_show"><s></s>0</span>&nbsp;<em>分</em>
                <span class="second_show"><s></s>0</span>&nbsp;<em>秒</em>
            </div>
        @endif
        <div class="Uneedtimejuli">{{$demand::countTimeInterval($demand->data->issue_time)}}</div>
    </div>

    <div class="Uneedtit clearfix XQdingdan">
        <span class="Uword2">订单号:{{$demand->data->order_number}}</span>
        <span class="Uorder">订单总额:<em class="Iprice">¥{{$demand->data->known_price}}</em></span>
        <span class="btnsqueryellow fr" onclick="location.href='{{url('wechat/about/explain-article/hotboom_assure_deal')}}'">担保交易</span>
    </div>

    <div class=" XQcont">
        <div class="XQcontwords">
            <div class="clearfix">
                <div class="fl orderLinkword">
                    <p class="Uneedcontwords1 ellipsis2">{{$goods->name}}</p>
                </div>
                @if($goods->type=='link')
                    @if(in_array($goods->domain,['tmall','taobao']))
                        <a class="fr orderLinkgreen" href="{{url('wechat/check-browse?url='.urlencode($goods->link))}}">
                            <input type="button" value="商品链接">
                        </a>
                    @else
                        <div class="fr orderLinkgreen" onclick="location.href='{{$goods->link}}'">
                            <input type="button" value="商品链接">
                        </div>
                    @endif
                @endif
            </div>
            <div class="clearfix">
                <div class="fl Uneedcontwords2"><em class="Uneedred1">¥{{$goods->known_unit_price}}</em> / *{{$goods->count}}{{$goods->unit}}</div>
                <div class="fr Uneedcontwords3">货源： <em class="Uneedred1">{{$goods->source}}</em></div>
            </div>
            <div class="beizu">备注：{{$goods->remark}}</div>
        </div>
    </div>
    @if($demand->data->demandGoods->count()>1)
        <p class="fabuing">本订单还包含以下商品</p>
        @foreach($demand->data->demandGoods as $v)
            @if($v->id!=$goods->id)
                <div class="clearfix XQgoods" onclick="location.href='{{url('wechat/tender/demand-details/'.$demand->data->id.'?goods_id='.$v->id)}}'">
                    <div class="fl XQgoodsimg">
                        <img src="{{$v->img->url or ''}}"/>
                    </div>
                    <div class="fl XQgoodsCont">
                        <p class="ellipsis1 XQgoodsword2">{{$v->name}}</p>
                        <p class="ellipsis1">
                            <span class="XQgoodsword1">¥<em class="Uneedred1">{{$v->known_unit_price}}</em>/ *{{$v->count}}{{$v->unit}}</span>
                            {{--<span class="XQgoodsword1">商品合计：¥<em class="XQgoodsword1red">{{$v->price}}</em></span>--}}
                        </p>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
    @if($goods->getSite())
        <div class="XQadress jiantou" onclick="location.href='{{url('wechat/tender/store-site/'.$goods->id)}}'">
            <p class="mapwrodd">需求实体店位置</p>
            <p class="XQadressicon ellipsis1">{{$goods->getSite()}}</p>
            <p class="XQadresword1">距您：{{$distance}}km</p>
        </div>
    @endif
    @if(isset($tender))
        <p class="fabuing">报价信息</p>
        <div class="XQTotal clearfix " style="margin-top: 0;">
            <div class="noyoufei">
                  <span class="XQTotalwrod1">
                报价总额：<em class="XQTotalred1">￥</em>
                <em class="XQTotalred2">{{$tender->getPrice()}}</em>
            </span>
                <span class="XQTotalwrod3">
                邮费：<em class="XQTotalred1">￥</em>
                    <em class="XQTotalred2">{{$tender->express_price}}</em>
            </span>
                <span class="XQTotalwrod2 ">
                较已知订单总额节省（不含邮费）<em class="XQTotalgreen1">￥</em>
                <em class="XQTotalgreen2">
                    {{$demand->data->known_price-$tender->getPrice()<0?0:$demand->data->known_price-$tender->getPrice()}}
                </em>
            </span>
            </div>
            @if($demand->isCirculation())
                <div class="peoplenumber clearfix">
                    <p class="XQmassegeword3 peoplenumber1">红利库存：<em class="green">{{$demand->getHotboomRepertory()}}</em></p>
                    <p class="XQmassegeword3 peoplenumber2">累计<em class="green">{{$demand->getCirculationCount()}}</em>人跟随</p>
                </div>
            @endif
        </div>
        <p class="fabuing">报价优势
            <span class="fabuingtips">(请注意报价货源，平台不允许代购无法溯源商家的产品)</span>
        </p>
        <ul class="XQadvantage clearfix">
            @if($tender->type=='other-hotboom')
                <li class="qustion" onclick="location.href='{{url('wechat/demand/hotboom-store-site?lng='.$tender->hotboom_lng.'&lat='.$tender->hotboom_lat.'&name='.$tender->hotboom_store_name)}}'">
                    其他商家
                </li>
            @else
                <li>原路代购</li>
            @endif
            @foreach($tender->quoteAdvantage as $v)
                @foreach(json_decode($v->label,true) as $v1)
                    <li>{{$v1}}</li>
                @endforeach
                @if($v->other)
                    <li>{{$v->other}}</li>
                @endif
            @endforeach
        </ul>

        @if($demand->data->is_select)
            <div class="XQmassegeall">
                <div class="jiantou jiantoubs" onclick="location.href='{{url('wechat/user/hotboom-info/'.$tender->id)}}'">

                    <div class="clearfix ">
                        <p style="    font-size: 10px; color: #333;">报价人</p>

                        <div class="fl clearfix XQmassegeR">
                            <div class="XQmassegeimg fl">
                                <img src="{{$demand->data->selectUser->img_url or ''}}">
                            </div>
                            <div class="fl XQmassegewordR" style="width: 63%;">
                                <div>
                            <span class="XQmassegeword1">
                                <em class="ellipsis1">{{$demand->data->selectUser->nickname or ''}}</em>
                            </span>
                                    <em class="XQmassegeicon1{{$demand->data->selectUser->mobile?'':'NO'}}"></em>
                                    <em class="XQmassegeicon2{{$demand->data->selectUser->is_auth?'':'NO'}}"></em>
                                </div>
                                @if($demand->data->selectUser->hide_mobile)
                                    <p class="XQmassegeword2">{{str_replace(substr($demand->data->selectUser->mobile,3,4),'****',$demand->data->selectUser->mobile)}}</p>
                                @else
                                    <p class="XQmassegeword2">{{$demand->data->selectUser->mobile}}</p>
                                @endif
                                <p class="XQmassegeword3">成交笔数：<em class="green">{{$demand->data->selectUser->daigou_over_demand or ''}}</em></p>
                            </div>
                        </div>

                        <div class="fr XQmassegeL  clearfix">
                            <div class="clearfix">

                                <em class="fr XQmassegestart Aisstart{{$demand->data->selectUser->evaluate_avg_grade or 0}}"></em>
                                <span class="XQhlword">红利分享信誉</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix chatselect">
                    @if($demand->data->is_select)
                        <span class="fr sButtongrey "><input type="button" value="已选择"/></span>
                    @endif
                    <span class="fr sButtongyellow mr10" onclick="location.href='{{url('wechat/chat/message/'.$demand->data->selectUser->id)}}'">
                            <input type="button" value="聊天"/></span>
                </div>
            </div>
        @endif

        {{--<div class="XQTotal clearfix " style="margin-top: 0;">--}}
        {{--<div class="noyoufei">--}}
        {{--<span class="XQTotalwrod1">--}}
        {{--报价总额：<em class="XQTotalred1">￥</em>--}}
        {{--<em class="XQTotalred2">{{$tender->getPrice()}}</em>--}}
        {{--</span>--}}
        {{--<span class="XQTotalwrod3">--}}
        {{--邮费：<em class="XQTotalred1">￥</em>--}}
        {{--<em class="XQTotalred2">{{$tender->express_price}}</em>--}}
        {{--</span>--}}
        {{--<span class="XQTotalwrod2 ">--}}
        {{--较已知订单总额节省（不含邮费）<em class="XQTotalgreen1">￥</em>--}}
        {{--<em class="XQTotalgreen2">--}}
        {{--{{$demand->data->known_price-$tender->getPrice()<0?0:$demand->data->known_price-$tender->getPrice()}}--}}
        {{--</em>--}}
        {{--</span>--}}
        {{--</div>--}}
        {{--@if($demand->isCirculation())--}}
        {{--<div class="peoplenumber clearfix">--}}
        {{--<p class="XQmassegeword3 peoplenumber1">红利库存：<em class="green">{{$demand->getHotboomRepertory()}}</em></p>--}}
        {{--<p class="XQmassegeword3 peoplenumber2">累计{{$demand->getCirculationCount()}}人跟随</p>--}}
        {{--</div>--}}
        {{--@endif--}}
        {{--</div>--}}

        @if(!request('tender_id'))
            <div class="clearfix evaluateall">
                <div class="fl evaluate">
                    收到的评价({{$evaluateCount}})
                </div>
                <a class="fr evaluateMore" href="{{url('wechat/user/all-evaluate/hotboom/'.$tender->user_id)}}">
                    更多评价
                </a>
            </div>
            <ul class="evaluateUl ">
                @foreach($evaluate as $v)
                    <li class="clearfix">
                        <div class="evaluateUlimg">
                            <img src="{{$v->evaluateUser->img_url or ''}}"/>
                        </div>
                        <div class="evaluateUlR">
                            <div class="clearfix evaluateUltit">
                                <div class="fl evaluateUltit1">
                                    <span class="ellipsis1">{{$v->evaluateUser->nickname or ''}}</span>
                                </div>
                                <div class="fr evaluateUltit2">
                                    <span class="ellipsis1">{{$v->create_time}}</span>
                                </div>
                            </div>
                            <div class="evaluateUltit3 ellipsis1">
                                {{$v->content}}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

    @endif
    <div class="XQmassegeall ">
        <a class="  XQa " style="" href="{{url('wechat/user/issue-demand/'.$demand->data->user_id.'/'.$demand->data->id)}}">
            <div class="jiantou jiantoubs">
                <div class="clearfix">
                    <p class="faqiren">需求发起人</p>
                    <div class="fl clearfix XQmassegeR">
                        <div class="XQmassegeimg fl"><img src="{{$demand->data->user->img_url or ''}}"></div>
                        <div class="fl XQmassegewordR">
                            <div><span class="XQmassegeword1"><em class="ellipsis1">{{$demand->data->user->nickname or ''}}</em></span></div>
                            <p class="Userword2">成交量：<em class="green">{{$demand->data->user->issue_over_demand?:0}}</em></p>
                            <p class="XQmassegeword3">{{$demand::countTimeInterval($demand->data->user->update_time).'来过'}}</p></div>
                    </div>

                    <div class="fr XQmassegeL clearfix " style="    margin-top: 4px;">
                        <div class="clearfix">
                            <em class="fr XQmassegestart Aisstart{{$demand->data->user->daigou_evaluate_avg_grade or '0'}}"></em>
                            <span class="XQhlword">红利需求信誉</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <div class="clearfix chatselect2 ">
        <span class="fl sButtongyellow mr10" onclick="location.href='{{url('wechat/chat/message/'.$demand->data->user_id)}}'">
                            <input type="button" value="聊天"/></span>
        </div>
    </div>
    <div class="baoadressall clearfix">
        <p class="baophone fl">{{str_replace(substr($demand->data->phone,3,4),'****',$demand->data->phone)}}</p>
        <div class="baoadress fr">
            <p class=" XQadressicon ellipsis1 ">{{$demand->hideAddress()}}</p>
        </div>
    </div>
    <div class="baolook">*订单收件人地址电话信息，报价被选中后才能查看</div>

    <div style="height: 45px;"></div>

    @if(request('tender_id'))
        @if($tender->status==1)
            <a class="Lbtnred" href="{{url('wechat/tender/edit/'.request('tender_id'))}}">
                修改报价
            </a>
        @else
            @if($tender->hotboom_type=='circulation'&&!$tender->isDelete())
                <a class="Lbtnred" href="{{url('wechat/tender/edit-repertory/'.request('tender_id'))}}">
                    修改库存
                </a>
            @endif
        @endif

    @else
        @if($demand->data->status==1&&$demand->data->userTender->count()<$tenderMaxCount)
            <div class="XQbottom clearfix">
                <a class="XQbottomchat" href="{{url('wechat/chat/message/'.$demand->data->user_id)}}">聊天</a>
                <a class="XQbottomenter" href="javascript:addCart({{$demand->data->id}})"><input type="button" value="加入代购车"/></a>
                <a class="XQbottomBJ" href="javascript:tenderCheck({{$demand->data->id}})">马上报价</a>
            </div>
        @endif
        @if($demand->isCirculation()&&$user->data->id!=$demand->data->select_user_id&&$demand->data->is_select)
            <a class="Lbtnblue" href="{{url('wechat/demand/copy-demand/'.$demand->data->id)}}">
                我要跟单
            </a>
        @endif
    @endif
    <div class="bg"></div>
    <div class="tan">
        <div class="tanclose">+</div>
        <div class="tanimg3" title="图片"></div>
        <p class="tanimgword1">发布需求前，需要认证个人信息!</p>
        <a class="redbtn90 tanbtnclose">
            <input type="button" value="去认证" onclick="location.href='{{url('wechat/user/bind-mobile?demand_id='.$demand->data->id)}}'"/>
        </a>
    </div>
@stop

@section('js')
    <script src="{{asset('asset/wechat/js/swiper-3.4.2.min.js')}}" type="text/javascript" charset="utf-8"></script>
    <script src="{{asset('asset/ext/layer/layer.min.js')}}"></script>
    <script>
        //投标验证
        function tenderCheck(id) {
            $.post("{{url('wechat/tender/tender-check')}}/" + id,
                {_token: '{{csrf_token()}}'},
                function (data, status) {
                    if (data.status != 1) {
                        if (data.status == -1) {
                            $(".tan,.bg").show();
                        } else {
                            layer.msg(data.message);
                        }
                    } else {
                        location.href = '{{url('wechat/tender/index?demand_id=')}}' + id;
                    }
                });
        }

        //添加代购车
        function addCart(id) {
            $.post("{{url('wechat/hotboom-cart/add')}}/" + id,
                {_token: '{{csrf_token()}}'},
                function (data, status) {
                    if (data.status != 1) {
                        layer.msg(data.message);
                    } else {
                        layer.msg('添加成功');
                    }
                });
        }

        //banner高度
        var bannerH = $(".XQbanner2").width();
        $(".XQbanner2").css("height", bannerH);

        $(".tanclose,.bg,.tanbtnclose").click(function () {
            $(".tan,.bg").hide();
        })

        var swiper = new Swiper('.swiper-container', {//banner
            pagination: '.swiper-pagination',
            paginationClickable: true,
        });

        /***********************************倒计时 开始**************************************************************/
        $(function () {
            @if($demand->isCirculation())
            timer(parseInt({{$demand->data->selectUserTender->hotboom_end_time-time()}}), "#time1");
            @else
            timer(parseInt({{$demand->data->end_time-time()}}), "#time1");
            @endif
        })
        function timer(intDiff, obj) {//倒计时
            window.setInterval(function () {
                var day = 0,
                    hour = 0,
                    minute = 0,
                    second = 0;//时间默认值
                if (intDiff > 0) {
                    day = Math.floor(intDiff / (60 * 60 * 24));
                    hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                    minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                    second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                }
                if (minute <= 9) minute = '0' + minute;
                if (second <= 9) second = '0' + second;
                $(obj + ' .day_show').html(day);
                $(obj + ' .hour_show').html('<s id="h"></s>' + hour);
                $(obj + ' .minute_show').html('<s></s>' + minute);
                $(obj + ' .second_show').html('<s></s>' + second);
                intDiff--;
            }, 1000);
        }
    </script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" charset="utf-8"></script>
    <script>
        //微信分享配置
        wx.config({!! $js->config(['onMenuShareTimeline','onMenuShareAppMessage'],false) !!});
        wx.ready(function () {
            //分享朋友圈
            wx.onMenuShareTimeline({
                @if($demand->data->is_select)
                title: '共享钱包，您挑商品我下单，立省¥{{$demand->getSaveprice()}}，上【红鱼】公众号体验购物新玩法！',
                                title: '(节省金额￥{{$demand->getSaveprice()}})我在【红鱼】找到了能更低价拿货的牛人，快来围观吧', // 分享标题
                        @else
                title: '(已知售价￥{{$goods->known_unit_price}})我在【红鱼】等待能更低价拿货的牛人，是你吗？', // 分享标题
                @endif
                link: '', // 分享链接
                imgUrl: '{{$goods->img->url or ''}}', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            //分享微信好友
            wx.onMenuShareAppMessage({
                @if($demand->data->is_select)
                title: '共享钱包，您挑商品我下单，立省¥{{$demand->getSaveprice()}}，上【红鱼】公众号体验购物新玩法！',
                                title: '(节省金额￥{{$demand->getSaveprice()}})我在【红鱼】找到了能更低价拿货的牛人，快来围观吧', // 分享标题
                desc: '{{$goods->name}}', // 分享描述
                @else
                title: '(已知售价￥{{$goods->known_unit_price}})我在【红鱼】等待能更低价拿货的牛人，是你吗？', // 分享标题
                desc: '{{$goods->name}}', // 分享描述
                @endif
                link: '', // 分享链接
                imgUrl: '{{$goods->img->url or ''}}', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        });

    </script>
@stop