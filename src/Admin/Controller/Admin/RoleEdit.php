<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Admin;
use Bybzmt\Blog\Admin\Helper\Permissions;

class Admin_RoleEdit extends AuthWeb
{
    public $role_id;
    public $role;

    public function init()
    {
        $this->role_id = isset($_GET['id']) ? $_GET['id'] : 0;
    }

    public function valid()
    {
        $this->role = $this->_context->getRow('AdminRole', $this->role_id);

        if (!$this->role) {
            return false;
        }

        return true;
    }

    public function show()
    {
        //所有权限
        //当前角色所有权限
        $permissions = $this->role->getPermissions();

        //重新整理下格式
        $rows = Permissions::reorganize($this->_context->getTable("AdminPermission"), $permissions);

        $data = array(
            'sidebarMenu' => '角色管理',
            'role' => $this->role,
            'permissions' => $rows,
        );
        $this->render($data);
    }


}