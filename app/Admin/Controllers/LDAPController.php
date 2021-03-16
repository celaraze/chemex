<?php

namespace App\Admin\Controllers;

use Adldap\Auth\BindException;
use Adldap\Auth\PasswordRequiredException;
use Adldap\Auth\UsernameRequiredException;
use App\Admin\Forms\LDAPForm;
use App\Support\LDAP;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;

class LDAPController extends AdminController
{
    /**
     * 页面.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description(admin_trans_label('description'))
            ->body(new Card(new LDAPForm()));
    }

    public function title()
    {
        return admin_trans_label('title');
    }

    /**
     * AD登录验证
     *
     * @return bool|string
     */
    public function test()
    {
        try {
            if (!admin_setting('ad_enabled')) {
                return -3;
            }

            return LDAP::auth();
        } catch (BindException $e) {
            return $e->getMessage();
        } catch (PasswordRequiredException $e) {
            return -1;
        } catch (UsernameRequiredException $e) {
            return -2;
        }
    }
}
