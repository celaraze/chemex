<div id="chart"></div>
<script>
    Dcat.ready(function () {
        let array = {!! $worth !!};
        let m = 0, series = [];
        while (m < array.length) {
            let d = 0;
            let single = {};
            single.name = m + 1;
            single.data = [];
            while (d < array[m].length) {
                single.data.push({
                    x: d + 1,
                    y: array[m][d]
                });
                d++;
            }
            series.push(single);
            m++;
        }

        const options = {
            series: series,
            chart: {
                height: 215,
                type: 'heatmap',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }

            },
            plotOptions: {
                heatmap: {
                    shadeIntensity: 0.5,
                    radius: 2,
                    distributed: true,
                    colorScale: {
                        ranges: [
                            {
                                from: 0,
                                to: 0,
                                name: '当天无价值',
                                color: '#E4F2FE'
                            },
                        ],
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: -1
            },
            colors: ["#008FFB"],
            tooltip: {
                custom: function ({series, seriesIndex, dataPointIndex, w}) {
                    let m = seriesIndex + 1;
                    let d = dataPointIndex + 1
                    return (
                        m + "-" + d + "：" + series[seriesIndex][dataPointIndex]
                    );
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    })
</script>
