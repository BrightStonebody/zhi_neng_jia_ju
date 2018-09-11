<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"/home/wwwroot/zhi_neng_jia_ju/public/../application/index/view/index/index.html";i:1535619274;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>智能家居控制平台</title>

    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/static/css/style.css">

    <script src="/static/vendor/jquery-3.3.1.min.js"></script>
    <script src="/static/vendor/popper.min.js"></script>
    <script src="/static/vendor/bootstrap.min.js"></script>
    <script src="/static/vendor/bootstrap-switch.js"></script>
    <!-- 图表JS -->
    <script type="text/javascript" src="/static/vendor/echarts.common.min.js"></script>
</head>

<body>
<div class="main">
    <i class="fa fa-gear title" class="fa_title"> 开关控制</i>
    <div class="main_div">
        <div class="container row">
            <P class="choose_1">选择位置</P>
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" id="btn" data-toggle="dropdown">客厅
                </button>
                <div class="dropdown-menu">
                </div>
            </div>
        </div>

        <div class="btn_div row" id="submit-btn">
            <input id="validate" type="hidden" name="__token__" value="<?php echo \think\Request::instance()->token(); ?>"/>
            <button class="btn btn-success btn-lg" id="btn_send">提交</button>
        </div>
    </div>
    <br>
    <i class="fa fa-h-square title" class="fa_title"> 当前环境</i>
    <div class="en">
        <div class="en_fac">
            <i class="fa fa-thermometer" style="font-size:46px"></i>
            <p class="en_title">温度</p>
            <p class="shuju" id="temp">4713</p>
        </div>
        <div class="en_fac">
            <i class="fa fa-tint" style="font-size:46px"></i>
            <p class="en_title">湿度</p>
            <p class="shuju" id="humi">4713</p>
        </div>
        <div class="en_fac">
            <i class="fa fa-certificate" style="font-size:46px"></i>
            <p class="en_title">光照强度</p>
            <p class="shuju" id="light">4713</p>
        </div>
        <div class="en_fac">
            <i class="fa fa-dashboard" style="font-size:46px"></i>
            <p class="en_title">CO浓度</p>
            <p class="shuju" id="co">4713</p>
        </div>
    </div>
    <br>
    <i class="fa fa-line-chart title" class="fa_title"> 历史数据</i>
    <div class="rec_main">
        <div class="rec">
            <div class="rec_top" id="wd-chart"></div>
            <div class="rec_top" id="sd-chart"></div>
        </div>
        <div class="rec">
            <div class="rec_buttom" id="co-chart"></div>
            <div class="rec_buttom" id="gq-chart"></div>
        </div>
    </div>
</div>

</body>
<!--main-->
<script>
    $(function () {
        $("#switch-panel-2").hide();
        $("#dropdown-item-1").click(function () {
            $("#btn").html("客厅");
            $("#switch-panel-1").show();
            $("#switch-panel-2").hide();
        });
        $("#dropdown-item-2").click(function () {
            $("#btn").html("卧室");
            $("#switch-panel-1").hide();
            $("#switch-panel-2").show();
        });

        $(".checkbox").bootstrapSwitch({
            onText: '开启',
            offText: '关闭',
            size: "small",
            onSwitchChange: function (event, state) {
                if (state === true) {
                    $(this).val("1");
                } else {
                    $(this).val("2");
                }
            }
        });
    });
</script>
<!--chart-->
<script>

    var ROOT_PATH = '';
</script>

<script src="/static/js/switch.js"></script>
<script src="/static/js/chart.js"></script>

</html>