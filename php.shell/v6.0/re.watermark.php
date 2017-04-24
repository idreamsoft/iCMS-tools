#!/usr/local/php/bin/php
<?php
require dirname(__file__).'/../iCMS.php';
iCMS::$config['watermark']['width']  = 90;
iCMS::$config['watermark']['height'] = 90;

$where  = "1=1";
$start  = 0;
$preNum = 100;
$max    = iDB::value("
    SELECT count(id) FROM `#iCMS@__filedata`
    WHERE {$where} order by id desc;"
);
$page   = ceil($max/$preNum);

for ($i=0; $i <=$page; $i++) {
    $offset = $i*$preNum;
    run($offset,$preNum);
}

function run($offset=0,$preNum=10){
    global $where;
    echo "LIMIT {$offset},{$preNum}\n";
    $ids_array   = iDB::all("
        SELECT id FROM #iCMS@__filedata
        WHERE {$where}
        order by id desc
        LIMIT {$offset},{$preNum}
    ");
    $ids       = iCMS::get_ids($ids_array);
    $ids       = $ids?$ids:'0';
    $where_sql = "WHERE a.id IN({$ids})";

    $filedata   = iDB::all("
        SELECT id,path,filename,ext FROM `#iCMS@__filedata` {$where_sql};
    ");

    foreach ((array)$filedata as $key => $row) {
        $filepath     = $row['path'].$row['filename'].'.'.$row['ext'];
        $FileRootPath = iFS::fp($filepath,"+iPATH");
        iFS::watermark($row['ext'],$FileRootPath);
        echo $FileRootPath."\n";
        echo $row['id']." ....ok!\n";
    }
}

