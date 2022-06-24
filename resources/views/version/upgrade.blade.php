<a id="upgrade" class="btn btn-primary" style="width: 100%;color: white"
   onclick="upgrade()">{{trans('main.upgrade')}}</a>

<script>
    /**
     * 升级
     */
    function upgrade() {
        let dom = $('#upgrade');
        dom.addClass('disabled');
        dom.innerText = '正在更新';
        $.ajax({
            url: "/action/upgrade",
            success: function (res) {
                Dcat.warning(res.message);
            },
            error: function (res) {
                Dcat.error('fail：' + res.data);
            }
        });
    }
</script>
