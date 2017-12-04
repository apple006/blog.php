<?php
namespace Bybzmt\Blog\Admin\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

abstract class Web extends Common\Controller
{
    use Admin\Loader;

    public function init()
    {
    }

    public function valid()
    {
        return true;
    }

    public function exec()
    {
        return true;
    }

    public function fail()
    {
    }

    public function onException($e)
    {
        throw $e;
    }

    public function render(array $data, string $name=null)
    {
        $dir = __DIR__;

        if (!$name) {
            $controller = substr(static::class, strlen(__NAMESPACE__)+1);
            $name = str_replace('_', '/', $controller);
        }
        $file = $name . '.tpl';

        $loader = new Twig_Loader_Filesystem($dir);
        $twig = new Twig_Environment($loader, array(
            'cache' => VAR_PATH . '/cache/templates',
            'auto_reload' => true,
        ));
        $twig->addExtension(new Admin\Helper\TwigExtension($twig));

        echo $twig->render($file, $data);
    }

}
