<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Framework\Table;
use Bybzmt\Framework\TableRowCache;

class User extends Table
{
    use TableRowCache;

    protected $_dbName = 'blog';
    protected $_tableName = 'users';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'user',
        'pass',
        'nickname',
        'addtime',
        'status',
    ];

    public function findByUsername($username)
    {
        list($sql, $vals) = $this->getHelper("SQLBuilder")->select($this->_columns, $this->_tableName, ['user'=>$username, 'status'=>1]);

        return $this->query($sql, $vals)->fetch();
    }
}
