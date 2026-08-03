<?php

function activeMenu($pages)
{
    $current = basename($_SERVER['PHP_SELF']);

    return in_array($current,$pages)
        ? 'active'
        : '';
}