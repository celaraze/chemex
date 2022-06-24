<!DOCTYPE html>
<head lang="zh">
    <title>设备打印标签</title>
    <style type="text/css">
        .container {
            height: 40mm;
            width: 60mm;
        }

        .row {
            display: flex;
        }

        .elem {
            flex: 4;
            float: right;
        }

        .tall-elem {
            font-size: 1px;
            width: 65%;
        }

        .child {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
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


@foreach($data as $row)
    <div class="container">
        <div class="row">
            <div style="font-size:24px;">{{$row['asset_number']}}</div>
        </div>
        <div class="row">
            <div class="tall-elem" style="line-height: 1.5;">
                <div class="child" style="margin-bottom:10px;">IT资产管理标签</div>
                <div class="child">名称：{{$row['name']}}</div>
                <div class="child">类型：{{$row->category()->value('name')}}</div>
                <div class="child">人员：{{$row->admin_user()->value('name')}}</div>
            </div>
            <div class="elem tall-elem" style="width:35%;">
                <div id="qrcode-{{$item}}-{{$row['asset_number']}}" style="margin-top:40px"></div>
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

@endforeach

<script type="text/javascript">
    window.print();
    clearTimeout(timeout);
    timeout = setTimeout(window.print(), 1000); //留意window.print()的写法
</script>

</body>
</html>
