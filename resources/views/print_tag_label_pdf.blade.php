<style>
div.lable{
    margin: 3mm;
}
div.asset_nember{
    padding-top: 1mm;
    font-size: 150%;
    width: {{$width-4}}mm;
    height:7mm;
    overflow:hidden;
}
div.title{
    padding-bottom: 4mm;
    font-size: 80%;
}
div.data{
    font-size: 80%;
    width: {{($width-($width*0.39))}}mm;
    height:5mm;
    overflow:hidden;
    text-overflow:ellipsis;
}
div.qr{
    position: absolute;
    right:3mm;
    bottom:3mm;
    width:{{$width*0.25}}mm;
    height: {{$width*0.25}}mm;
}
</style>
<page>
    <div class="lable">
        <div class="asset_nember" >{{$data['asset_number']}}</div>
        <div class="title" >Chemex IT资产管理系统</div>
        <div class="data" >名称：{{$data['name']}}</div>
        <div class="data" >类型：{{$data['category']}}</div>
        <div class="data" >型号：{{$data['asset_number']}}</div>
    </div>
    <div class="qr">
        <qrcode value="{{$_SERVER["HTTP_HOST"];}}/api/asset_card/device/{{$data['asset_number']}}" ec="L" style="border: none; width:{{$width*0.25}}mm; height: {{$width*0.25}}mm;"></qrcode>
    </div>
</page>
