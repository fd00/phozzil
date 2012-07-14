<?php

spl_autoload_register(function($className)
{
    $replacements = array(
        '\\' => DIRECTORY_SEPARATOR,
    );
    $classPath = str_replace(array_keys($replacements), array_values($replacements), $className);
    $fileName = sprintf('%s%s%s.php', dirname(__DIR__), DIRECTORY_SEPARATOR, $classPath);

    if (is_file($fileName)) {
        require_once $fileName;
    }
});
