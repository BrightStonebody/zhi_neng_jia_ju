var place_num = 0;


$(document).ready(function () {
    get_data();

    $('#btn_send').click(function () {

        var selected_place = $('.btn_div.row').attr('id');
        selected_place = Number(selected_place.substring(selected_place.length - 1));
        set_data(selected_place);

    });

});

function dropdown_click(index) {
    $("#btn").html('place-' + index);
    $("#switch-panel-" + index).show();
    for (var j = 1; j <= place_num; j++) {
        if (j !== index)
            $("#switch-panel-" + j).hide();
    }
    //修改提交按钮的id,使提交发送时能够区分选中的place
    $('#submit-btn').attr('id', 'submit-btn-' + index);

}

var need_verify = false;

function refreshVerify() {
    // $(\"#verify_image\").attr('src','{:captcha_src()}?tm='+Math.random());\n" +
    $("#verify-image").attr('src', ROOT_PATH + "/index/switches/verify_image?tm=" + Math.random());
}

function set_data(i) { // i表示place_id
    var data = {
        'dengguang': $("#switch-panel-" + i + " #dg").bootstrapSwitch('state'),
        'chuanglian': $("#switch-panel-" + i + " #cl").bootstrapSwitch('state'),
        'menjin': $("#switch-panel-" + i + " #mj").bootstrapSwitch('state'),
        'place': i,
        '__token__': $('#validate').attr('value'),
    };
    if (need_verify) {
        data['captcha'] = $('#captcha').val()
    }
    console.log(data);
    $.post(ROOT_PATH + '/index.php/index/switches/onOff',
        data,
        function (data) {
            if (data['status'] === 'ok') {
                alert('修改成功');
                need_verify = false;
                $('#verify-block').hide();
                refreshVerify();
            }
            else if (data['status'] === 'need_verify') {
                //当短时间内访问频率过高，必须要验证或者等待
                need_verify = true;
                $('#verify-block').html("           " +
                    "                <input name='captcha' id=\"captcha\" type=\"text\" style=\"width: 100px;\"/>\n" +
                    "                <img id=\"verify-image\" src=\"" + ROOT_PATH + "/index/switches/verify_image?tm=" + Math.random() + " alt=\"captcha\" />\n" +
                    "                <a id=\"kanbuq\" href=\"javascript:refreshVerify();\">换一张</a>");
                $('#verify-block').show();
            }
            else {
                if (data['verify'] === false) {
                    alert('验证码错误');
                    // $('#verify-image').attr('src','data:image/png;base64,'+data['verify_image']);
                    // console.log($('#verify-image').attr('src'))
                }
                else
                    alert('修改失败')
            }
        });
}

var PATH_SWITCHES = ROOT_PATH + '/index.php/index/switches/read_switches';

function get_data() {
    $.get(PATH_SWITCHES, function (data) {

        data = data['switches_info'];

        place_num = 0;
        $.each(data, function (i, detail) {
            //添加新位置
            place_num += 1;
            add_new_position(i, detail);
        });

        //设置dropdown点击事件
        //使用闭包保存变量
        for (var i = 1; i <= place_num; i++) {
            (function () {
                var index = i;
                $('#dropdown-item-' + index).click(function () {
                    dropdown_click(index);
                });
            })()
        }

        //设置选中第一个place
        dropdown_click(1);
        //在初始化数据之后才开始开启定时刷新, 避免冲突
        first_get = false;
        flush_data();


    });
}

function add_new_position(id, detail) {

    var pos_name = "place-" + id;
    $('.dropdown-menu').append("<p class=\"choose_2\" id=\"dropdown-item-" + id + "\">" + pos_name + "</p>");
    var html = "\n" +
        "        <div class=\"switch-panel\" id=\"switch-panel-" + id + "\">\n" +
        "            <div class=\"row cho\">\n" +
        "                <div class=\"choc\">\n" +
        "                    <p class=\"choose_p\">灯光</p>\n" +
        "                </div>\n" +
        "                <div class=\"choc2\"><input type=\"checkbox\" checked id=\"dg\"/></div>\n" +
        "            </div>\n" +
        "            <div class=\"row cho\">\n" +
        "                <div class=\"choc\">\n" +
        "                    <p class=\"choose_p\">窗帘</p>\n" +
        "                </div>\n" +
        "                <div class=\"choc2\"><input type=\"checkbox\" checked id=\"cl\"/></div>\n" +
        "            </div>\n" +
        "            <div class=\"row cho\">\n" +
        "                <div class=\"choc\">\n" +
        "                    <p class=\"choose_p\">门禁</p>\n" +
        "                </div>\n" +
        "                <div class=\"choc2\"><input type=\"checkbox\" checked id=\"mj\"/></div>\n" +
        "            </div>\n" +
        "        </div>";

    $('#submit-btn').before(html);

    $("#switch-panel-" + id + " #dg").bootstrapSwitch('state', detail['dengguang']);
    $("#switch-panel-" + id + " #cl").bootstrapSwitch('state', detail['chuanglian']);
    $("#switch-panel-" + id + " #mj").bootstrapSwitch('state', detail['menjin']);

}

var first_get = true;


function flush_data() {

    var old_status = [];
    var new_status = [];
    setInterval(function () {
        var id_list = ['#dg', '#cl', '#mj'];
        for (var i = 0; i < place_num; i++) {
            var parent = "#switch-panel-" + (i + 1);
            old_status[i] = "";
            for (var j = 0; j < id_list.length; j++) {
                var dom = $(parent + ' ' + id_list[j]);
                var value = dom.bootstrapSwitch('state') === true ? 1 : 0;
                old_status[i] += "_" + value;
            }
        }


        $.get(PATH_SWITCHES, function (data) {
            data = data['switches_info'];
            var i = 0;
            for (var item in data) {
                new_status[i] = "_" + data[item]['dengguang'] + "_" + data[item]['chuanglian'] + "_" + data[item]['menjin'];
                i++;
            }

            if (!first_get) {
                var same = true;
                for (i = 0; i < place_num; i++) {
                    if (old_status[i] !== new_status[i])
                        same = false;
                }
                if (same === false) {
                    alert('检测到状态发生改变,请更新页面');
                    window.location.reload();
                }
            }
            old_status = new_status;
        });
    }, 3000);
}

