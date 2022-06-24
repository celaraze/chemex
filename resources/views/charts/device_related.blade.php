<script src="/static/js/echarts-5.0.2.min.js"></script>
<div id="main" style="height: 350px"></div>

<script>
    Dcat.ready(function () {
        const chartDom = document.getElementById('main');
        const myChart = echarts.init(chartDom);
        let option;

        let data = {!! $related !!}

        myChart.setOption(option = {
            tooltip: {
                trigger: 'item',
                triggerOn: 'mousemove'
            },
            series: [
                {
                    left: '20%',
                    type: 'tree',
                    data: [data],
                    symbolSize: 12,
                    label: {
                        position: 'left',
                        verticalAlign: 'middle',
                        align: 'right',
                        fontSize: 10
                    },
                    leaves: {
                        label: {
                            position: 'right',
                            verticalAlign: 'middle',
                        }
                    },
                    emphasis: {
                        focus: 'descendant'
                    },
                    expandAndCollapse: true,
                    animationDuration: 500,
                    animationDurationUpdate: 500
                }
            ]
        });

        myChart.setOption(option);
    });

</script>
