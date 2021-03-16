<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;

class LDAPForm extends Form
{
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        admin_setting($input);

        return $this
            ->response()
            ->success(trans('main.success'))
            ->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->switch('ad_enabled')
            ->default(admin_setting('ad_enabled'));
        $this->divider();
        $this->text('ad_host_primary')
            ->help(admin_trans_label('Host Primary Help'))
            ->required()
            ->default(admin_setting('ad_host_primary'));
        $this->text('ad_host_secondary')
            ->help(admin_trans_label('Host Secondary Help'))
            ->default(admin_setting('ad_host_secondary'));
        $this->number('ad_port_primary')
            ->help(admin_trans_label('Port Primary Help'))
            ->max(65535)
            ->required()
            ->default(admin_setting('ad_port_primary'));
        $this->number('ad_port_secondary')
            ->help(admin_trans_label('Port Secondary Help'))
            ->max(65535)
            ->default(admin_setting('ad_port_secondary'));
        $this->text('ad_base_dn')
            ->help(admin_trans_label('Base DN'))
            ->required()
            ->default(admin_setting('ad_base_dn'));
        $this->text('ad_username')
            ->attribute('autocomplete', 'off')
            ->required()
            ->default(admin_setting('ad_username'));
        $this->password('ad_password')
            ->attribute('autocomplete', 'new-password')
            ->required()
            ->default(admin_setting('ad_password'));
        $this->switch('ad_use_ssl')
            ->default(admin_setting('ad_use_ssl'));
        $this->switch('ad_use_tls')
            ->default(admin_setting('ad_use_tls'));
        $this->divider();
        $this->switch('ad_login')
            ->help(admin_trans_label('Login'))
            ->default(admin_setting('ad_login'));
        $this->email('ad_bind_administrator')
            ->help(admin_trans_label('Bind Administrator'))
            ->required()
            ->default(admin_setting('ad_bind_administrator'));
        $this->html(function () {
            $test_connection = admin_trans_label('Test Connection');
            $connect_success = admin_trans_label('Connect Success');
            $connect_missing_password = admin_trans_label('Connect Missing Password');
            $connect_missing_username = admin_trans_label('Connect Missing Username');
            $ldap_disabled = admin_trans_label('LDAP Disabled');
            $connect_fail = admin_trans_label('Connect Fail');
            $connect_error = admin_trans_label('Connect Error');

            return <<<HTML
<a class='btn btn-success' style='color: #FFFFFF;' onclick="test()">{$test_connection}</a>
<script>
function test(){
    $.ajax({
            url: '/ldap/test',
            success: function (res) {
                res *=1;
                switch (res){
                    case 1:
                        Dcat.success("{$connect_success}");
                    break;
                    case -1:
                        Dcat.error("{$connect_missing_password}");
                    break;
                    case -2:
                        Dcat.error("{$connect_missing_username}");
                    break;
                    case -3:
                        Dcat.error("{$ldap_disabled}");
                    break;
                    default:
                        Dcat.error("{$connect_fail}");
                }
            },
            error: function (res) {
                console.log(res);
                Dcat.error("{$connect_error}" + res);
            }
        });
}
</script>
HTML;
        })->help(admin_trans_label('Test Connection Help'));
    }
}
