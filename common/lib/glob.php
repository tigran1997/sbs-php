<?php
/**
 * Created by PhpStorm.
 * User: Tigran
 * Date: 11/29/2017
 * Time: 12:39 PM
 */

function p($array)
{
    echo "<pre>";
    print_r(empty($array)?$_POST:$array);
    echo "</pre>";
}
