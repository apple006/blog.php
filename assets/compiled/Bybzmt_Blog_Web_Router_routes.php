<?php
// This file is automatically generated. Please do not edit it!
// 这个文件是自动生成的，请不要编辑它!
// 这个路由规则缓存文件!
return array(
    'GET' => array(
        '#map#' => array(
            '/' => array(
                'Bybzmt\\Blog\\Web\\Controller\\Article\\list',
                'run',
                array(),
                'Article.list',
            ),
        ),
        'article' => array(
            '#regex#' => array(
                '#^/(\\d+)$#' => array(
                    'Bybzmt\\Blog\\Web\\Controller\\Article\\show',
                    'run',
                    array(
                        array('','id',false),
                    ),
                    'Article.show',
                ),
            ),
        ),
        'tag' => array(
            '#regex#' => array(
                '#^/(\\d+)$#' => array(
                    'Bybzmt\\Blog\\Web\\Controller\\Article\\tag',
                    'run',
                    array(
                        array('','id',false),
                    ),
                    'Article.tag',
                ),
            ),
        ),
    ),
);
