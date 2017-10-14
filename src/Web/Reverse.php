<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Reverse as PReverse;

class Reverse extends PReverse
{
    static public function mkUrl(string $func, array $params=array(), bool $https=false)
    {
        $uri = self::init()->buildUri($action, $params);

        $host = Config::get('host.web');

        if ($https) {
            return 'https://' . $host . $uri;
        }

        return 'http://' . $host . $uri;
    }
}
