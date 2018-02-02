<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class Dashboard extends AuthWeb
{
    public function show()
    {
        $this->render(array(
            'sidebarMenu' => 'Dashboard',
        ));
    }
}
