<?php

//echo '<pre>';

$filename = '/data/www/blogbackup.xml';

$content = file_get_contents($filename);




preg_match_all('/http:\/\/images201[0-9].cnblogs.com\/blog\/[^>]+\/([^\/]+)\.(?:jpg|png)/', $content, $match);


foreach($match[0] as $img){
    shell_exec('curl -O '.$img);
}
