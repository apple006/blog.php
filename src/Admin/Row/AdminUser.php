<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Blog\Admin;

class AdminUser extends Admin\Row
{
    public $id;
    public $user;
    public $pass;
    public $nickname;
    public $addtime;
    public $user_id;
    public $isroot;
    public $status;

    protected function init(array $row)
    {
        $this->id = (int)$row['id'];
        $this->user = $row['user'];
        $this->pass = $row['pass'];
        $this->nickname = $row['nickname'];
        $this->addtime = strtotime($row['addtime']);
        $this->user_id = (int)$row['user_id'];
        $this->isroot = (bool)$row['isroot'];
        $this->status = (int)$row['status'];
    }

    public function encryptPass($pass)
    {
        //密码摘要，密钥确定后不可更改
        return hash_hmac('md5', $pass, $this->id.'encryptkey');
    }

    public function setPass($pass)
    {
        $saved = $this->encryptPass($pass);

        $ok = $this->_context->getTable("AdminUser")->update($this->id, array('pass'=>$saved));
        if ($ok) {
            $this->pass = $saved;
        }
        return $ok;
    }

    public function setRoot(bool $bool)
    {
        $ok = $this->_context->getTable("AdminUser")->update($this->id, array('isroot'=>(int)$bool));
        if ($ok) {
            $this->isroot = $bool;
        }
        return $ok;
    }

    public function setNickname($nickname)
    {
        $ok = $this->_context->getTable("AdminUser")->update($this->id, array('nickname'=>$nickname));
        if ($ok) {
            $this->nickname = $nickname;
        }
        return $ok;
    }

    public function del()
    {
        $ok = $this->_context->getTable("AdminUser")->update($this->id, array('status'=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function auditPass()
    {
        $ok = $this->_context->getTable("AdminUser")->update($this->id, array('status'=>2));
        if ($ok) {
            $this->status = 2;
        }
        return $ok;
    }

    public function validPass($pass)
    {
        return $this->encryptPass($pass) == $this->pass;
    }

    /**
     * 得到用户己有的权限标识
     */
    public function getPermissions()
    {
        $table = $this->_context->getTable('AdminUser');

        $permissions1 = $table->getUserPermissions($this->id);
        $permissions2 = $table->getUserRolesPermissions($this->id);

        return array_unique(array_merge($permissions1, $permissions2));
    }

    public function getUserPermissions()
    {
        return $this->_context->getTable('AdminUser')->getUserPermissions($this->id);
    }

    public function setUserPermissions($permissions)
    {
        return $this->_context->getTable('AdminUser')->setUserPermissions($this->id, $permissions);
    }

    public function getRoles()
    {
        $table = $this->_context->getTable("AdminUser");
        $role_ids = $table->getUserRoleIds($this->id);

        $roles = [];
        foreach ($role_ids as $role_id) {
            $roles[] = $this->_context->getLazyRow("AdminRole", $role_id);
        }

        return $roles;
    }

    public function setRoles($roles)
    {
        $role_ids = array();
        foreach ($roles as $role) {
            $role_ids[] = $role->id;
        }

        return $this->_context->getTable("AdminUser")->setUserRoleIds($this->id, $role_ids);
    }
}