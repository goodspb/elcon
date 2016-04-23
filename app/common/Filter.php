<?php
namespace Common;

use Phalcon\Di;
use Phalcon\DiInterface;

class Filter
{

    public static function register(DiInterface $di)
    {
        $filters = require ROOT_PATH. '/app/filter.php';
        /* @var $pf \Phalcon\Filter */
        $pf = $di->getShared('filter');
        if (is_array($filters) && !empty($filters)) foreach ($filters as $name => $filter) {
            if (!is_string($name)) continue;
            if ($filter instanceof \Closure) {
                $pf->add($name, $filter);
            } elseif (is_string($filter) || is_array($filter)) {
                $pf->add($name, function($value) use ($filter){
                    $replace = '';
                    if (is_array($filter)) list($filter, $replace) = $filter;
                    return preg_replace($filter, $replace, $value);
                });
            }
        }
    }

    public static function sanitize($value, $filters, $noRecursive = false)
    {
        /* @var $filter \Phalcon\Filter */
        $filter = Di::getDefault()->getShared('filter');
        return $filter->sanitize($value, $filters, $noRecursive);
    }

}