//图表显示
$(document).ready(function () {
    show_current_data();

    show_history_chart();
});

function show_current_data(){
    $.get(ROOT_PATH + '/index/table/get_current_data',function (data) {
        data = data['info'];
        $('#temp').text(data['temp']);
        $('#humi').text(data['humi']);
        $('#light').text(data['light']);
        $('#co').text(data['co']);
    });
}

function show_history_chart(){
    //图表显示
    function draw_chart(url, element_id, title, y_label) {
        $.get(url, function (data) {
            var dom = document.getElementById(element_id);
            var myChart = echarts.init(dom);
            var app = {};
            var option = {
                title: {
                    text: title
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: data['x']
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    data: data['y'],
                    type: 'line',
                    areaStyle: {}
                }]
            };
            if (option && typeof option === "object") {
                myChart.setOption(option,true);
            }
        })
    }

    draw_chart(ROOT_PATH + '/index/table/get_data?type=temp', 'wd-chart', '历史数据-温度', '温度');
    draw_chart(ROOT_PATH + '/index/table/get_data?type=humi', 'sd-chart', '历史数据-湿度', '湿度');
    draw_chart(ROOT_PATH + '/index/table/get_data?type=co', 'co-chart', '历史数据-CO', 'CO');
    draw_chart(ROOT_PATH + '/index/table/get_data?type=light', 'gq-chart', '历史数据-光照', '光照');

}
