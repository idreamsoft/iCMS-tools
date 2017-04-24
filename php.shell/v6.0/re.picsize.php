#!/usr/local/php/bin/php -c /usr/local/etc/php-cli.ini
<?php
define('iPHP_DEBUG',true);
require dirname(__file__).'/../iCMS.php';
set_time_limit(0);

ini_set('memory_limit','512M');

$preNum = 1000;
$pn     = 100;
$image  = new Gmagick();
$width  = 800;
$height = 600;

for ($i=0; $i < $pn; $i++) {
    $offset    = $i*$preNum;
    $where     = " a.postype='5' and a.status ='1' and a.cid in(11,9)";
    $ids_array = iDB::all("
        SELECT id FROM #iCMS@__article a
        WHERE {$where}
        order by id desc
        LIMIT {$offset},{$preNum}
    ");
    $ids       = iCMS::get_ids($ids_array);
    $ids       = $ids?$ids:'0';
    $where_sql = "WHERE a.id IN({$ids})";

    $article   = iDB::all("
        SELECT a.id,d.body,a.pic
        FROM #iCMS@__article as a
        LEFT JOIN #iCMS@__article_data AS d
        ON a.id = d.aid
        {$where_sql}
        order by a.id desc
    ");

    foreach ((array)$article as $rkey => $row) {
        $aid  = $row['id'];
        $pic  = $row['pic'];
        $body = stripslashes($row['body']);
        $img  = array();
        preg_match_all("/<img.*?src\s*=[\"|'|\s]*(http:\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$body,$pic_array);
        $_array = array_unique($pic_array[1]);

        foreach($_array as $key =>$value) {
            if(stripos($value, '.gif')!==false){
                continue;
            }
            $src = iFS::fp($value,'http2iPATH');
            list($owidth, $oheight, $otype) = @getimagesize($src);
                    // var_dump($owidth, $oheight, $otype);
            if(($owidth>=$width || $oheight>=$height) && $otype){
                $im = bitscale(
                    array(
                        "tw" => $width,
                        "th" => $height,
                        "w"  => $owidth,
                        "h"  => $oheight
                    )
                );

                try {
                    $image->readImage($src);
                } catch (Exception $e) {
                    // exec("gm convert ".$src." -depth 8 \
                    //     -thumbnail ".$im['w']."x".$im['h']." \
                    //     -background gray -gravity center \
                    //     -extent 100x100 output_5.jpg");
                    // echo 'error: ' .$src."\n";
                    // echo $e->getMessage();
                    // exit;
                    continue;
                }

                // var_dump($owidth, $oheight,$im);
                $image->resizeImage($im['w'],$im['h'], null, 1);
                $srcData = $image->current();
                $oSrcData = file_get_contents($src);
                $nlen = strlen($srcData);
                $olen = strlen($oSrcData);
                if($nlen<($olen*0.6)){
                    file_put_contents($src, $srcData);
                    echo round ($nlen/$olen,2)." @ ";
                    echo $value."\n";
                    // file_put_contents('resizeImage.log',$value."\n",FILE_APPEND);
                    // exit;
                }
                $image->destroy();
                unset($srcData,$oSrcData,$nlen,$olen);
                unset($im,$owidth, $oheight, $otype);
            }
    	}
        unset($_array);
        echo $aid."...finish\n";
    }
    unset($article);
}

