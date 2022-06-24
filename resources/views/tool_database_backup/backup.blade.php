<div>
    <div>备份数据库的原理并不是直接 dump sql 文件，而是通过 Laravel 的数据库填充方式导出到静态文件。</div>
    <div>这不仅有助于在异构数据库系统之间的数据快速迁移，也可以让运维人员减少数据库维护操作。</div>
    <a onclick="backup()" id="backup" class="btn btn-primary mt-1">备份</a>
</div>

<script>
    /**
     * 升级
     */
    function backup() {
        let dom = $('#backup');
        dom.addClass('disabled');
        dom.innerText = '正在备份';
        $.ajax({
            url: "/action/database_backup",
            success: function (res) {
                Dcat.success(res.message);
            },
            error: function (res) {
                Dcat.error('fail：' + res.data);
            }
        });
    }
</script>
