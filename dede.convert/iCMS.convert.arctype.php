<?php
defined('iPATH') OR exit('What are you doing?');

if(empty($total)){
    $total   = $dedeDB->value('
        SELECT count(*)
        FROM `#dede@__arctype`
    ');
    // $total   = 10000;
}
$multi        = iCMS::page(array('total'=>$total,'perpage'=>$maxperpage,'nowindex'=>$_GET['page']));
$offset       = $multi->offset;

if($offset=="0"){
    $dede_config['TRUNCATE'] && iDB::query('TRUNCATE TABLE `#iCMS@__category`');

    if(iDB::value("SELECT `cid` FROM `#iCMS@__category` limit 1 ")){
        iPHP::alert("转换出错! 请确保 iCMS 栏目表[category]为空!");
    }
}

$limit        = "LIMIT {$offset},{$maxperpage}";
$archives_ids = $dedeDB->all('
    SELECT `id` FROM `#dede@__arctype`
    '.$limit.'
');


$ids       = iPHP::get_ids($archives_ids);
$ids       = $ids?$ids:'0';
$where_sql = "WHERE `id` IN({$ids})";
$arctype  = $dedeDB->all('
    SELECT * FROM `#dede@__arctype`
    '.$where_sql.'
');


foreach ((array)$arctype as $key => $row) {
    $data = array(
     'cid'             => $row['id'],
     'rootid'          => $row['reid'],
     'appid'           => '1',
     'name'            => $row['typename'],
     'ordernum'        => $row['sortrank'],
     'status'          => empty($row['ishidden'])?1:0,
     'domain'          => $row['moresite']?$row['siteurl']:'',
     // 'id'           => $row['defaultname'],
     'mode'            => '1',
     'issend'          => $row['issend'],
     'isucshow'        => $row['issend']?'1':'0',
     'indexTPL'        => $row['tempindex'],
     'listTPL'         => $row['templist'],
     'contentTPL'      => $row['temparticle'],
     // 'categoryRule' => $row['namerule2'],
     'categoryRule'    => '/{CDIR}/',
     // 'contentRule'  => $row['namerule'],
     'description'     => $row['description'],
     'keywords'        => $row['keywords'],
     'title'           => $row['seotitle'],
    );
    if($row['isdefault']=='-1'){
        $data['mode']='2';
    }

    $data['dir'] = str_replace('{cmspath}', '', $row['typedir']);
    $data['dir'] = str_replace($sysconfig['cfg_arcdir'], '', $data['dir']);
    $data['dir'] = trim($data['dir'],'/');

    if(stripos($data['dir'], 'http://')!==false){
        $data['url'] = $data['dir'];
        $data['dir'] = '';
    }

    if($row['defaultname']!='index.html'){
        $data['categoryRule'] = '/{CDIR}/'.$row['defaultname'];
    }
    $data['contentRule'] = str_replace(
        array('{aid}','{typedir}','{Y}','{M}','{D}','{timestamp}','{pinyin}'),
        array('{ID}','{CDIR}','{YYYY}','{MM}','{DD}','{TIME}','{LINK}/{ID}'),
        $row['namerule']
    );
    $data['indexTPL']   = str_replace('{style}', $sysconfig['cfg_df_style'], $data['indexTPL']);
    $data['listTPL']    = str_replace('{style}', $sysconfig['cfg_df_style'], $data['listTPL']);
    $data['contentTPL'] = str_replace('{style}', $sysconfig['cfg_df_style'], $data['contentTPL']);

    $cid = iDB::insert('category',$data);
}
