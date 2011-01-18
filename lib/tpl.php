<?php

require_once 'utils.php';

function tpl($file, $ctx=array(), $safe=array()) {
    foreach ($ctx as $k => $v) {
        if (is_string($v) && ! in_array($k, $safe)) {
            $ctx[$k] = h($v);
        }
    }
    extract($ctx);
    ob_start();
    include("templates/$file.php");
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

