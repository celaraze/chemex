<!DOCTYPE html>
<head lang="zh">
    <title>设备打印标签</title>
    <style type="text/css">
        @page {
            size：A4;
        }

        .A4 {
            box-sizing: border-box;
            margin: 0 auto;
            width: 210mm;
            height: 300mm;
            overflow: hidden;
            word-break: break-all;
        }

        .label-page {
            height: 280mm;
            width: 189mm;
            box-sizing: border-box;
            margin: 8mm 12mm 0mm 12mm;
        }

        .label {
            box-sizing: border-box;
            height: 40mm;
            width: 60mm;
            margin: 0 1mm 0 1mm;
            display: inline-block;
        }

        .label-data {
            height: 38mm;
            width: 58mm;
        }

        .assetnumber {
            height: 20%;
            display: flex;
            font-size: 24px;
            margin-left: 1mm;
        }

        .qr {
            position: absolute;
            right: 0;
            bottom: 0
        }

        .row {
            height: 80%;
            display: flex;
        }

        .elem {
            flex: 4;
            float: right;
        }

        .tall-elem {
            font-size: 4px;
            width: 70%;
            position: relative;
        }

        .child {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            margin-left: 2mm;
        }
    </style>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>
    <script type="text/javascript" src="/static/js/print/qr/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="/static/js/print/qrcode.js"></script>
    <script type="text/javascript" src="/static/js/print/qr/jquery.jqprint-0.3.js"></script>
    <script type="text/javascript" src="/static/js/print/qr/jquery-migrate-1.1.0.js"></script>
</head>
<body>
<script type="text/javascript">
    function prints(o1) {
        $(o1).remove();
        window.print();
    }

    function initbody() {

    }
</script>
@php
    $Count = 0;
@endphp
@foreach($data as $row)

    @if($loop->index == $Count)
        <div class="A4">
            <div class="label-page">
                @endif

                <div class="label">
                    <div class="label-data">
                        <div class="assetnumber">
                            <div>{{$row['asset_number']}}</div>
                        </div>
                        <div class="row">
                            <div class="tall-elem" style="line-height: 1.5;">
                                <div class="child" style="margin-bottom:10px;">IT资产管理标签</div>
                                <div class="child">名称：{{$row['name']}}</div>
                                <div class="child">类型：{{$row->category()->value('name')}}</div>
                                <div class="child">部门：{{$row->department()}}</div>
                                <div class="child">人员：{{$row->admin_user()->value('name')}}</div>
                            </div>
                            <div class="elem tall-elem" style="width:60px;">
                                <div id="qrcode-{{$item}}-{{$row['asset_number']}}" class="qr"></div>
                                <script type="text/javascript">
                                    var qrcode = new QRCode(document.getElementById("qrcode-{{$item}}-{{$row['asset_number']}}"), {
                                        width: 60,
                                        height: 60,
                                    });

                                    function makeCode() {
                                        qrcode.makeCode('{{$item}}:{{$row['asset_number']}}');
                                    }

                                    makeCode();
                                    $("#text").on("blur", function () {
                                        makeCode();
                                    }).on("keydown", function (e) {
                                        if (e.keyCode === 13) {
                                            makeCode();
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>

                @if($loop->index == $Count+20)
            </div>
        </div>
        @php
            $Count = $Count+21;
        @endphp
    @endif

@endforeach

<script type="text/javascript">
    window.print();
    clearTimeout(timeout);
    timeout = setTimeout(window.print(), 1000); //留意window.print()的写法
</script>

</body>
</html>
