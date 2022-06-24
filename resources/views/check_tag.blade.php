<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IT资产管理系统 | {{$data->asset_number}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
	<link rel="stylesheet" href="{{asset("static/css/checktag/bootstrap.css")}}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset("static/css/checktag/main.css")}}" crossorigin="anonymous">
    <link id="new-stylesheet" rel="stylesheet">
</head>
<body>
    <div class="page login-page">
        <div class="container d-flex align-items-center">
            <div class="form-holder has-shadow">
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
                        <div class="form d-flex align-items-center">
                            <div class="content">
								<table>
                    <tbody>
                        <tr>
                            <th align='left' width='120px'>资产名称</th>
                            <td>{{$data->name}}</td>
                        </tr>
                        <tr>
                            <th align='left' width='120px'>资产类型</th>
                            <td>{{$data->categories}}</td>
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
                            <th align='left' width='120px'>使用部门</th>
                            <td>{{$data->departments}}</td>
                        </tr>
                        <tr>
							<th align='left' width='120px'>使用人员</th>
                            <td>{{$data->admin_users}}</td>
                        </tr>
                        <tr>
                            <th align='left' width='120px'>资产备注</th>
                            <td >{{$data->description}}</td>
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