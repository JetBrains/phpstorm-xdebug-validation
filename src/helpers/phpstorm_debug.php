<?php
include 'phar://phpstorm_debug_validator.phar/common.php';

$content = XDebugValidator::run();
echo $content;
