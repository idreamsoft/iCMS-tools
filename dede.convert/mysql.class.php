<?php
/**
* iPHP - i PHP Framework
* Copyright (c) 2012 iiiphp.com. All rights reserved.
*
* @author coolmoo <iiiphp@qq.com>
* @site http://www.iiiphp.com
* @licence http://www.iiiphp.com/license
* @version 1.0.1
* @package iDB
* @$Id: iMysql.class.php 2412 2014-05-04 09:52:07Z coolmoo $
*/

define('OBJECT', 'OBJECT');
define('ARRAY_A', 'ARRAY_A');
define('ARRAY_N', 'ARRAY_N');

defined('SAVEQUERIES') OR define('SAVEQUERIES', true);
defined('iPHP_DB_PORT') OR define('iPHP_DB_PORT', '3306');

class iMysql_DEDE{
    public $show_errors = false;
    public $num_queries = 0;
    public $last_query;
    public $col_info;
    public $queries;
    public $func_call;
    public $last_result;
    public $num_rows;
    public $insert_id;
    public $config = null;
    public $dbFlag = 'iPHP_DB';

    private $collate;
    private $time_start;
    public $last_error ;
    private $link;
    private $result;

    public function __construct($config=null,$flag='iPHP_DB') {
        extension_loaded('mysql') OR die('您的 PHP 环境看起来缺少 MySQL 数据库部分，这对 iPHP 来说是必须的。');

        defined('iPHP_DB_COLLATE') &&$this->collate = iPHP_DB_COLLATE;

        $this->config = $config;
        $this->dbFlag = $flag;

        if(isset($GLOBALS[$flag])){
            $this->link = $GLOBALS[$flag];
            if($this->link){
                if(mysql_ping($this->link))
                    return $this->link;
            }
        }

        empty($this->config) && $this->config = array(
            'HOST'       => iPHP_DB_HOST,
            'USER'       => iPHP_DB_USER,
            'PASSWORD'   => iPHP_DB_PASSWORD,
            'DB'         => iPHP_DB_NAME,
            'CHARSET'    => iPHP_DB_CHARSET,
            'PORT'       => iPHP_DB_PORT,
            'PREFIX'     => iPHP_DB_PREFIX,
            'PREFIX_TAG' => iPHP_DB_PREFIX_TAG
        );
    }

    public function connect($flag=null){
        $this->link = @mysql_connect($this->config['HOST'].':'.$this->config['PORT'], $this->config['USER'], $this->config['PASSWORD'],true);
        if($flag==='link'){
            return $this->link;
        }

        $this->link OR $this->bail("<h1>数据库连接失败</h1><p>请检查 <em><strong>config.php</strong></em> 的配置是否正确!</p><ul><li>请确认主机支持MySQL?</li><li>请确认用户名和密码正确?</li><li>请确认主机名正确?(一般为localhost)</li></ul><p>如果你不确定这些情况,请询问你的主机提供商.如果你还需要帮助你可以随时浏览 <a href='http://www.iiiphp.com'>iPHP 支持论坛</a>.</p>");

        $GLOBALS[$this->dbFlag] = $this->link;
        $this->pre_set();
        if($flag===null){
            $this->select_db();
        }
    }

    public function pre_set() {
        $this->query("SET NAMES '".$this->config['CHARSET']."'");
        $this->query("SET @@sql_mode = ''");
    }
    public function select_db($var=false) {
        $sel = @mysql_select_db($this->config['DB'], $this->link);
        if($var) return $sel;
        $sel OR $this->bail("<h1>数据库连接失败</h1><p>我们能连接到数据库服务器（即数据库用户名和密码正确） ，但是不能链接到<em><strong> ".iPHP_DB_NAME." </strong></em>数据库.</p><ul><li>你确定<em><strong> ".iPHP_DB_NAME." </strong></em>存在?</li></ul><p>如果你不确定这些情况,请询问你的主机提供商.如果你还需要帮助你可以随时浏览 <a href='http://www.iiiphp.com'>iPHP 支持论坛</a>.</p>");
    }
    // ==================================================================
    //  Basic Query - see docs for more detail

    public function query($query,$QT=NULL) {
        if(empty($query)){
            if ($this->show_errors) {
                $this->bail("SQL IS EMPTY");
            } else {
                return false;
            }
        }

        $this->link OR $this->connect();

        // filter the query, if filters are available
        // NOTE: some queries are made before the plugins have been loaded, and thus cannot be filtered with this method
        $query  = str_replace($this->config['PREFIX_TAG'],$this->config['PREFIX'], trim($query));

        // initialise return
        $return_val = 0;
        $this->flush();

        // Log how the function was called
        $this->func_call = __CLASS__.'::query("'.$query.'")';

        // Keep track of the last query for debug..
        $this->last_query = $query;

        // Perform the query via std mysql_query function..
        SAVEQUERIES && $this->timer_start();

        $this->result = @mysql_query($query, $this->link);

        if(!$this->result){
            // If there is an error then take note of it..
            return $this->print_error();
        }
        $this->num_queries++;

        SAVEQUERIES && $this->queries[] = array( $query, $this->timer_stop());

        if($QT=='get'){
            return $this->result;
        }
        $QH = strtoupper(substr($query,0,strpos($query, ' ')));
        if (in_array($QH,array('INSERT','DELETE','UPDATE','REPLACE','SET','CREATE','DROP','ALTER'))) {
            $rows_affected = mysql_affected_rows($this->link);
            // Take note of the insert_id
            if (in_array($QH,array("INSERT","REPLACE"))) {
                $this->insert_id = mysql_insert_id($this->link);
            }
            // Return number of rows affected
            $return_val = $rows_affected;
        } else {
            if($QT=="field") {
                $i = 0;
                while ($i < @mysql_num_fields($this->result)) {
                    $this->col_info[$i] = mysql_fetch_field($this->result);
                    $i++;
                }
            }else {
                $num_rows = 0;
                while ( $row = @mysql_fetch_object($this->result) ) {
                    $this->last_result[$num_rows] = $row;
                    $num_rows++;
                }
                // Log number of rows the query returned
                $this->num_rows = $num_rows;

                // Return number of rows selected
                $return_val = $num_rows;
            }
            @mysql_free_result($this->result);
        }

        return $return_val;
    }
    public function get($output = OBJECT) {
        if ( $output == OBJECT ) {
            return mysql_fetch_object($this->result,MYSQL_ASSOC);
        }else{
            return mysql_fetch_array($this->result,MYSQL_ASSOC);
        }
    }
    /**
     * Insert an array of data into a table
     * @param string $table WARNING: not sanitized!
     * @param array $data should not already be SQL-escaped
     * @return mixed results of $this->query()
     */
    public function insert($table, $data) {
//      $data = add_magic_quotes($data);
        $fields = array_keys($data);
        $this->query("INSERT INTO ".iPHP_DB_PREFIX_TAG."{$table} (`" . implode('`,`',$fields) . "`) VALUES ('".implode("','",$data)."')");
        return $this->insert_id;
    }

    /**
     * Update a row in the table with an array of data
     * @param string $table WARNING: not sanitized!
     * @param array $data should not already be SQL-escaped
     * @param array $where a named array of WHERE column => value relationships.  Multiple member pairs will be joined with ANDs.  WARNING: the column names are not currently sanitized!
     * @return mixed results of $this->query()
     */
    public function update($table, $data, $where) {
//      $data = add_magic_quotes($data);
        $bits = $wheres = array();
        foreach ( array_keys($data) as $k ){
            $bits[] = "`$k` = '$data[$k]'";
        }
        if ( is_array( $where ) ){
            foreach ( $where as $c => $v )
                $wheres[] = "$c = '" . addslashes( $v ) . "'";
        }else{
            return false;
        }
        return $this->query("UPDATE ".iPHP_DB_PREFIX_TAG."{$table} SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres ) . ' LIMIT 1;' );
    }
    /**
     * Get one variable from the database
     * @param string $query (can be null as well, for caching, see codex)
     * @param int $x = 0 row num to return
     * @param int $y = 0 col num to return
     * @return mixed results
     */
    public function value($query=null, $x = 0, $y = 0) {
        $this->func_call = __CLASS__."::value(\"$query\",$x,$y)";
        $query && $this->query($query);
        // Extract var out of cached results based x,y vals
        if ( !empty( $this->last_result[$y] ) ) {
            $values = array_values(get_object_vars($this->last_result[$y]));
        }
        // If there is a value return it else return null
        return (isset($values[$x]) && $values[$x]!=='') ? $values[$x] : null;
    }

    /**
     * Get one row from the database
     * @param string $query
     * @param string $output ARRAY_A | ARRAY_N | OBJECT
     * @param int $y row num to return
     * @return mixed results
     */
    public function row($query = null, $output = OBJECT, $y = 0) {
        $this->func_call = __CLASS__."::row(\"$query\",$output,$y)";
        $query && $this->query($query);

        if ( !isset($this->last_result[$y]) )
            return null;

        if ( $output == OBJECT ) {
            return $this->last_result[$y] ? $this->last_result[$y] : null;
        } elseif ( $output == ARRAY_A ) {
            return $this->last_result[$y] ? get_object_vars($this->last_result[$y]) : null;
        } elseif ( $output == ARRAY_N ) {
            return $this->last_result[$y] ? array_values(get_object_vars($this->last_result[$y])) : null;
        } else {
            $this->print_error(__CLASS__."::row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N");
        }
    }

    /**
     * Return an entire result set from the database
     * @param string $query (can also be null to pull from the cache)
     * @param string $output ARRAY_A | ARRAY_N | OBJECT
     * @return mixed results
     */
    public function all($query = null, $output = ARRAY_A) {
        $this->func_call = __CLASS__."::array(\"$query\", $output)";

        $query && $this->query($query);

        // Send back array of objects. Each row is an object
        if ( $output == OBJECT ) {
            return $this->last_result;
        } elseif ( $output == ARRAY_A || $output == ARRAY_N ) {
            if ( $this->last_result ) {
                $i = 0;
                foreach( (array) $this->last_result as $row ) {
                    if ( $output == ARRAY_N ) {
                        // ...integer-keyed row arrays
                        $new_array[$i] = array_values( get_object_vars( $row ) );
                    } else {
                        // ...column name-keyed row arrays
                        $new_array[$i] = get_object_vars( $row );
                    }
                    ++$i;
                }
                return $new_array;
            } else {
                return null;
            }
        }
    }

    /**
     * Gets one column from the database
     * @param string $query (can be null as well, for caching, see codex)
     * @param int $x col num to return
     * @return array results
     */
    public function col($query = null , $x = 0) {
        $query && $this->query($query);
        $new_array = array();
        // Extract the column values
        for ( $i=0; $i < count($this->last_result); $i++ ) {
            $new_array[$i] = $this->value(null, $x, $i);
        }
        return $new_array;
    }

    /**
     * Grabs column metadata from the last query
     * @param string $info_type one of name, table, def, max_length, not_null, primary_key, multiple_key, unique_key, numeric, blob, type, unsigned, zerofill
     * @param int $col_offset 0: col name. 1: which table the col's in. 2: col's max length. 3: if the col is numeric. 4: col's type
     * @return mixed results
     */
    public function col_info($query = null ,$info_type = 'name', $col_offset = -1) {
        $query && $this->query($query,"field");
        if ( $this->col_info ) {
            if ( $col_offset == -1 ) {
                $i = 0;
                foreach($this->col_info as $col ) {
                    $new_array[$i] = $col->{$info_type};
                    $i++;
                }
                return $new_array;
            } else {
                return $this->col_info[$col_offset]->{$info_type};
            }
        }
    }
    public function version() {
        $this->link OR $this->connect();
        // Make sure the server has MySQL 4.0
        $mysql_version = preg_replace('|[^0-9\.]|', '', @mysql_get_server_info($this->link));
        if ( version_compare($mysql_version, '4.0.0', '<') )
            $this->bail('database_version<strong>ERROR</strong> iPHP %s requires MySQL 4.0.0 or higher');
        else
            return $mysql_version;
    }
    public function debug($show=false){
        if(!$this->show_errors) return false;

        if(!$show) echo '<!--';
        echo $this->last_query."\n";
        $explain    = $this->row('EXPLAIN EXTENDED '.$this->last_query);
        var_dump($explain);
        if(!$show) echo "-->\n";
    }

    // ==================================================================
    //  Kill cached query results

    public function flush() {
        $this->last_result  = array();
        $this->col_info     = null;
        $this->last_query   = null;
    }
    /**
     * Starts the timer, for debugging purposes
     */
    public function timer_start() {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $this->time_start = $mtime[1] + $mtime[0];
        return true;
    }

    /**
     * Stops the debugging timer
     * @return int total time spent on the query, in milliseconds
     */
    public function timer_stop() {
        $mtime      = microtime();
        $mtime      = explode(' ', $mtime);
        $time_end   = $mtime[1] + $mtime[0];
        $time_total = $time_end - $this->time_start;
        return $time_total;
    }
    // ==================================================================
    //  Print SQL/DB error.

    public function print_error($error = '') {
        $this->last_error = mysql_error($this->link);
        $error OR $error      = $this->last_error;

        $error    = htmlspecialchars($error, ENT_QUOTES);
        $query  = htmlspecialchars($this->last_query, ENT_QUOTES);
        // Is error output turned on or not..
        if ( $this->show_errors ) {
            $this->bail("<strong>iPHP database error:</strong> [$error]<br /><code>$query</code>");
        } else {
            return false;
        }
    }
    /**
     * Wraps fatal errors in a nice header and footer and dies.
     * @param string $message
     */
    public function bail($message){ // Just wraps errors in a nice header and footer
        if ( !$this->show_errors ) {
            return false;
        }
        trigger_error($message,E_USER_ERROR);
    }
}
