<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IT资产管理系统 | {{$data->asset_number}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{asset("static/CSS/bootstrap.css")}}" crossorigin="anonymous">
    <style>
        table {
            color: #666;
            font-size: .9em
        }

        .page {
            background: #eef5f9
        }

        .has-shadow {
            -webkit-box-shadow: 2px 2px 2px rgba(0, 0, 0, .1), -1px 0 2px rgba(0, 0, 0, .05);
            box-shadow: 2px 2px 2px rgba(0, 0, 0, .1), -1px 0 2px rgba(0, 0, 0, .05)
        }

        .login-page {
            position: relative
        }

        .tag-page .container {
            position: relative;
            z-index: 999;
            padding: 20px;
            min-height: 100vh
        }

        .tag-page .tag-holder {
            overflow: hidden;
            margin-bottom: 50px;
            width: 100%;
            border-radius: 5px
        }

        .tag-page .tag-holder .tag, .tag-page .tag-holder .info {
            padding: 40px;
            height: 100%;
            min-height: 40vh
        }

        .tag-page .tag-holder div[class*=col-] {
            padding: 0
        }

        .tag-page .tag-holder .info {
            background: #c9261d;
            color: #fff
        }

        .tag-page .tag-holder .info h1 {
            font-weight: 600;
            font-size: 2.5em
        }

        .tag-page .tag-holder .info p {
            font-weight: 300
        }

        body {
            background-color: #fff;
            color: #212529;
            font-weight: 400;
            font-size: 1rem;
            font-family: Poppins, sans-serif
        }

    </style>
    <link id="new-stylesheet" rel="stylesheet">
</head>
<body>
<div class="page tag-page">
    <div class="container d-flex align-items-center">
        <div class="tag-holder has-shadow">
            <div class="row">
                <div class="col-lg-6">
                    <div class="info d-flex align-items-center">
                        <div class="content">
                            <div class="logo">
                                <h1>{{$data->asset_number}}</h1>
                            </div>
                            <p>IT资产管理系统 | 资产明细</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 bg-white">
                    <div class="tag d-flex align-items-center">
                        <div class="content">
                            <table>
                                <tbody>
                                <tr>
                                    <th align='left' width='120px'>资产名称</th>
                                    <td>{{$data->name}}</td>
                                </tr>
                                <tr>
                                    <th align='left' width='120px'>资产类型</th>
                                    <td>{{$data->category()->value('name')}}</td>
                                </tr>

                                <!--
                        <tr>
                            <th align='left' width='120px'>资产规格</th>
                            <td >{{$data->model}}</td>
                        </tr>
                        -->

                                <tr>
                                    <th align='left' width='120px'>网络地址</th>
                                    <td>{{$data->ip}}</td>
                                </tr>
                                <tr>
                                    <th align='left' width='120px'>购保日期</th>
                                    <td>{{$data->purchased}} --> {{$data->expired}}</td>
                                </tr>
                                <tr>
                                    <th align='left' width='120px'>资产状态</th>
                                    <td>{!!$data->status()[0]!!}</td>
                                </tr>
                                <tr>
                                    <th align='left' width='120px'>使用部门</th>
                                    <td>{{$data->department()}}</td>
                                </tr>
                                <tr>
                                    <th align='left' width='120px'>使用人员</th>
                                    <td>{{$data->userName()}}</td>
                                </tr>
                                <tr>
                                    <th align='left' width='120px'>资产备注</th>
                                    <td>{{$data->description}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
