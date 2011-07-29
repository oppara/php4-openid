<?php

$path = get_include_path() . ':' . dirname(dirname(__FILE__));
set_include_path($path);

require_once('simpletest/autorun.php');

