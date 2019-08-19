<?php
function iterate()
{
    $curDirectory = opendir('../../../');
    while($file = readdir($curDirectory) !== false)
    {
        echo $file . PHP_EOL;
    }
}
?>