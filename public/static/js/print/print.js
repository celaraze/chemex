<script>
    function encode(){
    $("#code").html('');
    var str=$('#txt').val();
    str=toUtf8(str);
    //$('#code').qrcode(str);
    $("#code").qrcode({
    render: "canvas", //table方式
    width: 100, //宽度
    height:100, //高度
    text: str //任意内容
});
}

    function toUtf8(str) {
    var out, i, len, c;
    out = "";
    len = str.length;
    for(i = 0; i < len; i++) {
    c = str.charCodeAt(i);
    if ((c >= 0x0001) && (c <= 0x007F)) {
    out += str.charAt(i);
} else if (c > 0x07FF) {
    out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
    out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));
    out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
} else {
    out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));
    out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
}
}
    return out;
}

    function print(){
    var img = document.getElementById("image"); /// get image element
    var canvas  = document.getElementsByTagName("canvas")[0];  /// get canvas element
    img.src = canvas.toDataURL();                     /// update image

    $("#image").jqprint({
    debug:false,
    importCSS:true,
    printContainer:true,
    operaSupport:false
});
}
</script>
