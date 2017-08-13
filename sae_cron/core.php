<?php
define('LUSER_START',microtime(TRUE)); //程序启动时间.
define('LUSER','luser');
define('PHP_FILE','.php');
define('ROUTE_DIR', 'routes');
define('ROUTE_PHP_FILE', '.routes.php');
//默认 路径 conf文件配置为空是 自动生成目录结构
define('APP','app');
define('CNOTROL_PATH',ROOT.DS.APP.DS.'controllers');
define('MODEL_PATH',ROOT.DS.APP.DS.'models');
define('LIB_PATH',ROOT.DS.LUSER.DS.'libs');

class url {

    static function baseUrl($uri = '') {

        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';

        $baseUrl.= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');

        $baseUrl.= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));

        $baseUrl = str_replace('/public', '', $baseUrl);

        //echo $base_url.$uri;
		//echo "alsdkjf = ".$base_url;
		//exit();
        return $baseUrl . $uri;

    }

}


class Mysql
{
    /**
     * Static instance of self
     *
     * @var MysqliDb
     */
    protected static $_instance;
    /**
     * Table prefix
     * 
     * @var string
     */
    protected static $_prefix;
    /**
     * MySQLi instance
     *
     * @var mysqli
     */
    protected $_mysqli;
    /**
     * The SQL query to be prepared and executed
     *
     * @var string
     */
    protected $_query;
    /**
     * The previously executed SQL query
     *
     * @var string
     */
    protected $_lastQuery;
    /**
     * An array that holds where joins
     *
     * @var array
     */
    protected $_join = array();
    /**
     * An array that holds where conditions 'fieldname' => 'value'
     *
     * @var array
     */
    protected $_where = array();
    /**
     * Dynamic type list for order by condition value
     */
    protected $_orderBy = array();
    /**
     * Dynamic type list for group by condition value
     */
    protected $_groupBy = array();
    /**
     * Dynamic array that holds a combination of where condition/table data value types and parameter referances
     *
     * @var array
     */
    protected $_bindParams = array('');
    // Create the empty 0 index
    /**
     * Variable which holds an amount of returned rows during get/getOne/select queries
     *
     * @var string
     */
    public $count = 0;
    /**
     * Variable which holds last statement error
     *
     * @var string
     */
    protected $_stmtError;
    /**
     * Database credentials
     *
     * @var string
     */
    protected $host;
    protected $username;
    protected $password;
    protected $db;
    protected $port;
    /**
     * Is Subquery object
     *
     */
    protected $isSubQuery = false;
    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     * @param int $port
     */
    public function __construct($host = NULL, $username = NULL, $password = NULL, $db = NULL, $port = NULL)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
        if ($port == NULL) {
            $this->port = ini_get('mysqli.default_port');
        } else {
            $this->port = $port;
        }
        if ($host == null && $username == null && $db == null) {
            $this->isSubQuery = true;
            return;
        }
        // for subqueries we do not need database connection and redefine root instance
        $this->connect();
        $this->setPrefix();
        self::$_instance = $this;
    }
    public function like($table, $field, $like)
    {
        $q = "SELECT * FROM `{$table}` WHERE `{$field}`  LIKE  '%{$like}%'";
        //echo $q;
        //print_r($this->rawQuery($q));
        return $this->rawQuery($q);
    }
    //ADD 	DROP change
    public function alter($q)
    {
        $this->rawQuery($q);
    }
    public function search($q)
    {
        return $this->rawQuery($q);
    }
    public function drop($table)
    {
        $this->rawQuery("DROP TABLE {$table}");
    }
    public function truncate($table)
    {
        $this->rawQuery("TRUNCATE TABLE {$table}");
    }
    public function create($name, $data = null)
    {
        //$q = "CREATE TABLE $name (id INT(9) UNSIGNED PRIMARY KEY NOT NULL";
        $q = "CREATE TABLE `{$name}` (id INT(9) UNSIGNED PRIMARY KEY AUTO_INCREMENT";
        if ($data) {
            foreach ($data as $k => $v) {
                $q .= ", {$k} {$v}";
            }
        }
        $q .= ') CHARACTER SET utf8';
        //echo $q;
        $this->rawQuery($q);
    }
    /**
     * A method to connect to the database
     *
     */
    public function connect()
    {
        if ($this->isSubQuery) {
            return;
        }
        $this->_mysqli = new mysqli($this->host, $this->username, $this->password, $this->db, $this->port) or die('There was a problem connecting to the database');
        $this->_mysqli->set_charset('utf8');
    }
    /**
     * A method of returning the static instance to allow access to the
     * instantiated object from within another class.
     * Inheriting this class would require reloading connection info.
     *
     * @uses $db = MySqliDb::getInstance();
     *
     * @return object Returns the current instance.
     */
    public static function getInstance()
    {
        return self::$_instance;
    }
    /**
     * Reset states after an execution
     *
     * @return object Returns the current instance.
     */
    protected function reset()
    {
        $this->_where = array();
        $this->_join = array();
        $this->_orderBy = array();
        $this->_groupBy = array();
        $this->_bindParams = array('');
        // Create the empty 0 index
        $this->_query = null;
        $this->count = 0;
    }
    /**
     * Method to set a prefix
     * 
     * @param string $prefix     Contains a tableprefix
     */
    public function setPrefix($prefix = '')
    {
        self::$_prefix = $prefix;
        return $this;
    }
    /**
     * Pass in a raw query and an array containing the parameters to bind to the prepaird statement.
     *
     * @param string $query      Contains a user-provided query.
     * @param array  $bindParams All variables to bind to the SQL statment.
     * @param bool   $sanitize   If query should be filtered before execution
     *
     * @return array Contains the returned rows from the query.
     */
    public function rawQuery($query, $bindParams = null, $sanitize = true)
    {
        $this->_query = $query;
        if ($sanitize) {
            $this->_query = filter_var($query, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        $stmt = $this->_prepareQuery();
        if (is_array($bindParams) === true) {
            $params = array('');
            // Create the empty 0 index
            foreach ($bindParams as $prop => $val) {
                $params[0] .= $this->_determineType($val);
                array_push($params, $bindParams[$prop]);
            }
            call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));
        }
        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $this->reset();
        return $this->_dynamicBindResults($stmt);
    }
    /**
     *
     * @param string $query   Contains a user-provided select query.
     * @param int    $numRows The number of rows total to return.
     *
     * @return array Contains the returned rows from the query.
     */
    public function query($query, $numRows = null)
    {
        $this->_query = filter_var($query, FILTER_SANITIZE_STRING);
        $stmt = $this->_buildQuery($numRows);
        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $this->reset();
        return $this->_dynamicBindResults($stmt);
    }
    /**
     * A convenient SELECT * function.
     *
     * @param string  $tableName The name of the database table to work with.
     * @param integer $numRows   The number of rows total to return.
     *
     * @return array Contains the returned rows from the select query.
     */
    public function get($tableName, $numRows = null, $columns = '*')
    {
        if (empty($columns)) {
            $columns = '*';
        }
        $column = is_array($columns) ? implode(', ', $columns) : $columns;
        $this->_query = "SELECT {$column} FROM `" . self::$_prefix . $tableName . '`';
        // echo $this->_query;exit();
        $stmt = $this->_buildQuery($numRows);
        if ($this->isSubQuery) {
            return $this;
        }
        //echo $this->_query;
        //echo $this->_query;
        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $this->reset();
        return $this->_dynamicBindResults($stmt);
    }
    /**
     * A convenient SELECT * function to get one record.
     *
     * @param string  $tableName The name of the database table to work with.
     *
     * @return array Contains the returned rows from the select query.
     */
    public function getOne($tableName, $columns = '*')
    {
        $res = $this->get($tableName, 1, $columns);
        if (is_object($res)) {
            return $res;
        }
        if (isset($res[0])) {
            return $res[0];
        }
        return null;
    }
    /**
     *
     * @param <string $tableName The name of the table.
     * @param array $insertData Data containing information for inserting into the DB.
     *
     * @return boolean Boolean indicating whether the insert query was completed succesfully.
     */
    public function insert($tableName, $insertData)
    {
        if ($this->isSubQuery) {
            return;
        }
        $this->_query = 'INSERT into `' . self::$_prefix . $tableName . '`';
        $stmt = $this->_buildQuery(null, $insertData);
        //echo $this->_query;
        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $this->reset();
        return $stmt->affected_rows > 0 ? $stmt->insert_id : false;
    }
    /**
     * Update query. Be sure to first call the "where" method.
     *
     * @param string $tableName The name of the database table to work with.
     * @param array  $tableData Array of data to update the desired row.
     *
     * @return boolean
     */
    public function update($tableName, $tableData)
    {
        if ($this->isSubQuery) {
            return;
        }
        $this->_query = 'UPDATE `' . self::$_prefix . $tableName . '` SET ';
        $stmt = $this->_buildQuery(null, $tableData);
        $status = $stmt->execute();
        $this->reset();
        $this->_stmtError = $stmt->error;
        $this->count = $stmt->affected_rows;
        return $status;
    }
    /**
     * Delete query. Call the "where" method first.
     *
     * @param string  $tableName The name of the database table to work with.
     * @param integer $numRows   The number of rows to delete.
     *
     * @return boolean Indicates success. 0 or 1.
     */
    public function delete($tableName, $numRows = null)
    {
        if ($this->isSubQuery) {
            return;
        }
        $this->_query = 'DELETE FROM `' . self::$_prefix . $tableName . '`';
        $stmt = $this->_buildQuery($numRows);
        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $this->reset();
        return $stmt->affected_rows > 0;
    }
    /**
     * This method allows you to specify multiple (method chaining optional) AND WHERE statements for SQL queries.
     *
     * @uses $MySqliDb->where('id', 7)->where('title', 'MyTitle');
     *
     * @param string $whereProp  The name of the database field.
     * @param mixed  $whereValue The value of the database field.
     *
     * @return MysqliDb
     */
    public function where($whereProp, $whereValue = null, $operator = null)
    {
        if ($operator) {
            $whereValue = array($operator => $whereValue);
        }
        $this->_where[] = array('AND', $whereValue, $whereProp);
        return $this;
    }
    /**
     * This method allows you to specify multiple (method chaining optional) OR WHERE statements for SQL queries.
     *
     * @uses $MySqliDb->orWhere('id', 7)->orWhere('title', 'MyTitle');
     *
     * @param string $whereProp  The name of the database field.
     * @param mixed  $whereValue The value of the database field.
     *
     * @return MysqliDb
     */
    public function orWhere($whereProp, $whereValue = null, $operator = null)
    {
        if ($operator) {
            $whereValue = array($operator => $whereValue);
        }
        $this->_where[] = array('OR', $whereValue, $whereProp);
        return $this;
    }
    /**
     * This method allows you to concatenate joins for the final SQL statement.
     *
     * @uses $MySqliDb->join('table1', 'field1 <> field2', 'LEFT')
     *
     * @param string $joinTable The name of the table.
     * @param string $joinCondition the condition.
     * @param string $joinType 'LEFT', 'INNER' etc.
     *
     * @return MysqliDb
     */
    public function join($joinTable, $joinCondition, $joinType = '')
    {
        $allowedTypes = array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER');
        $joinType = strtoupper(trim($joinType));
        $joinTable = filter_var($joinTable, FILTER_SANITIZE_STRING);
        if ($joinType && !in_array($joinType, $allowedTypes)) {
            die('Wrong JOIN type: ' . $joinType);
        }
        $this->_join[$joinType . ' JOIN ' . self::$_prefix . $joinTable] = $joinCondition;
        return $this;
    }
    /**
     * This method allows you to specify multiple (method chaining optional) ORDER BY statements for SQL queries.
     *
     * @uses $MySqliDb->orderBy('id', 'desc')->orderBy('name', 'desc');
     *
     * @param string $orderByField The name of the database field.
     * @param string $orderByDirection Order direction.
     *
     * @return MysqliDb
     */
    public function orderBy($orderByField, $orderbyDirection = 'DESC')
    {
        $allowedDirection = array('ASC', 'DESC');
        $orderbyDirection = strtoupper(trim($orderbyDirection));
        $orderByField = preg_replace('/[^-a-z0-9\\.\\(\\),_]+/i', '', $orderByField);
        if (empty($orderbyDirection) || !in_array($orderbyDirection, $allowedDirection)) {
            die('Wrong order direction: ' . $orderbyDirection);
        }
        $this->_orderBy[$orderByField] = $orderbyDirection;
        return $this;
    }
    /**
     * This method allows you to specify multiple (method chaining optional) GROUP BY statements for SQL queries.
     *
     * @uses $MySqliDb->groupBy('name');
     *
     * @param string $groupByField The name of the database field.
     *
     * @return MysqliDb
     */
    public function groupBy($groupByField)
    {
        $groupByField = preg_replace('/[^-a-z0-9\\.\\(\\),_]+/i', '', $groupByField);
        $this->_groupBy[] = $groupByField;
        return $this;
    }
    /**
     * This methods returns the ID of the last inserted item
     *
     * @return integer The last inserted item ID.
     */
    public function getInsertId()
    {
        return $this->_mysqli->insert_id;
    }
    /**
     * Escape harmful characters which might affect a query.
     *
     * @param string $str The string to escape.
     *
     * @return string The escaped string.
     */
    public function escape($str)
    {
        return $this->_mysqli->real_escape_string($str);
    }
    /**
     * Method to call mysqli->ping() to keep unused connections open on
     * long-running scripts, or to reconnect timed out connections (if php.ini has
     * global mysqli.reconnect set to true). Can't do this directly using object
     * since _mysqli is protected.
     *
     * @return bool True if connection is up
     */
    public function ping()
    {
        return $this->_mysqli->ping();
    }
    /**
     * This method is needed for prepared statements. They require
     * the data type of the field to be bound with "i" s", etc.
     * This function takes the input, determines what type it is,
     * and then updates the param_type.
     *
     * @param mixed $item Input to determine the type.
     *
     * @return string The joined parameter types.
     */
    protected function _determineType($item)
    {
        switch (gettype($item)) {
            case 'NULL':
            case 'string':
                return 's';
                break;
            case 'boolean':
            case 'integer':
                return 'i';
                break;
            case 'blob':
                return 'b';
                break;
            case 'double':
                return 'd';
                break;
        }
        return '';
    }
    /**
     * Helper function to add variables into bind parameters array
     *
     * @param string Variable value
     */
    protected function _bindParam($value)
    {
        $this->_bindParams[0] .= $this->_determineType($value);
        array_push($this->_bindParams, $value);
    }
    /**
     * Helper function to add variables into bind parameters array in bulk
     *
     * @param Array Variable with values
     */
    protected function _bindParams($values)
    {
        foreach ($values as $value) {
            $this->_bindParam($value);
        }
    }
    /**
     * Helper function to add variables into bind parameters array and will return
     * its SQL part of the query according to operator in ' $operator ?' or
     * ' $operator ($subquery) ' formats
     *
     * @param Array Variable with values
     */
    protected function _buildPair($operator, $value)
    {
        if (!is_object($value)) {
            $this->_bindParam($value);
            return ' ' . $operator . ' ? ';
        }
        $subQuery = $value->getSubQuery();
        $this->_bindParams($subQuery['params']);
        return ' ' . $operator . ' (' . $subQuery['query'] . ')';
    }
    /**
     * Abstraction method that will compile the WHERE statement,
     * any passed update data, and the desired rows.
     * It then builds the SQL query.
     *
     * @param int   $numRows   The number of rows total to return.
     * @param array $tableData Should contain an array of data for updating the database.
     *
     * @return mysqli_stmt Returns the $stmt object.
     */
    protected function _buildQuery($numRows = null, $tableData = null)
    {
        $this->_buildJoin();
        $this->_buildTableData($tableData);
        $this->_buildWhere();
        $this->_buildGroupBy();
        $this->_buildOrderBy();
        $this->_buildLimit($numRows);
        //echo $this->_query;
        //print_r( $this->_bindParams);
        $this->_lastQuery = $this->replacePlaceHolders($this->_query, $this->_bindParams);
        //echo $this->_lastQuery;
        if ($this->isSubQuery) {
            return;
        }
        /*echo "\r\n";
        
        		echo "thisquery = ".$this->_query;	
        
        		echo "\r\n";
        
        		echo "lastquery = ".$this->_lastQuery;
        
        		echo "\r\n";
        
        		echo "thisquery = ".$this->_query;	
        
        		echo "\r\n";*/
        // Prepare query
        $stmt = $this->_prepareQuery();
        //print_r($stmt);
        //print_r($this->_bindParams);
        // Bind parameters to statement if any
        if (count($this->_bindParams) > 1) {
            call_user_func_array(array($stmt, 'bind_param'), $this->refValues($this->_bindParams));
        }
        return $stmt;
    }
    /**
     * This helper method takes care of prepared statements' "bind_result method
     * , when the number of variables to pass is unknown.
     *
     * @param mysqli_stmt $stmt Equal to the prepared statement object.
     *
     * @return array The results of the SQL fetch.
     */
    protected function _dynamicBindResults(mysqli_stmt $stmt)
    {
        $parameters = array();
        $results = array();
        $meta = $stmt->result_metadata();
        // if $meta is false yet sqlstate is true, there's no sql error but the query is
        // most likely an update/insert/delete which doesn't produce any results
        if (!$meta && $stmt->sqlstate) {
            return array();
        }
        $row = array();
        while ($field = $meta->fetch_field()) {
            $row[$field->name] = null;
            $parameters[] =& $row[$field->name];
        }
        // avoid out of memory bug in php 5.2 and 5.3
        // https://github.com/joshcam/PHP-MySQLi-Database-Class/pull/119
        if (version_compare(phpversion(), '5.4', '<')) {
            $stmt->store_result();
        }
        call_user_func_array(array($stmt, 'bind_result'), $parameters);
        while ($stmt->fetch()) {
            $x = array();
            foreach ($row as $key => $val) {
                $x[$key] = $val;
            }
            $this->count++;
            array_push($results, $x);
        }
        return $results;
    }
    /**
     * Abstraction method that will build an JOIN part of the query
     */
    protected function _buildJoin()
    {
        if (empty($this->_join)) {
            return;
        }
        foreach ($this->_join as $prop => $value) {
            $this->_query .= ' ' . $prop . ' on ' . $value;
        }
    }
    /**
     * Abstraction method that will build an INSERT or UPDATE part of the query
     */
    protected function _buildTableData($tableData)
    {
        if (!is_array($tableData)) {
            return;
        }
        $isInsert = strpos($this->_query, 'INSERT');
        $isUpdate = strpos($this->_query, 'UPDATE');
        if ($isInsert !== false) {
            $this->_query .= '(`' . implode(array_keys($tableData), '`, `') . '`)';
            $this->_query .= ' VALUES(';
        }
        foreach ($tableData as $column => $value) {
            if ($isUpdate !== false) {
                $this->_query .= '`' . $column . '` = ';
            }
            // Subquery value
            if (is_object($value)) {
                $this->_query .= $this->_buildPair('', $value) . ', ';
                continue;
            }
            // Simple value
            if (!is_array($value)) {
                $this->_bindParam($value);
                $this->_query .= '?, ';
                continue;
            }
            // Function value
            $key = key($value);
            $val = $value[$key];
            switch ($key) {
                case '[I]':
                    $this->_query .= $column . $val . ', ';
                    break;
                case '[F]':
                    $this->_query .= $val[0] . ', ';
                    if (!empty($val[1])) {
                        $this->_bindParams($val[1]);
                    }
                    break;
                case '[N]':
                    if ($val == null) {
                        $this->_query .= '!' . $column . ', ';
                    } else {
                        $this->_query .= '!' . $val . ', ';
                    }
                    break;
                default:
                    die('Wrong operation');
            }
        }
        $this->_query = rtrim($this->_query, ', ');
        if ($isInsert !== false) {
            $this->_query .= ')';
        }
    }
    /**
     * Abstraction method that will build the part of the WHERE conditions
     */
    protected function _buildWhere()
    {
        if (empty($this->_where)) {
            return;
        }
        //Prepair the where portion of the query
        $this->_query .= ' WHERE ';
        //print_r($this->_where);
        // Remove first AND/OR concatenator
        $this->_where[0][0] = '';
        foreach ($this->_where as $cond) {
            list($concat, $wValue, $wKey) = $cond;
            //print_r($cond);
            $this->_query .= ' ' . $concat . ' ' . $wKey;
            //echo $this->_query;
            //echo "nalishi<br>";
            //print_r( $wValue);
            // Empty value (raw where condition in wKey)
            if ($wValue === null) {
                continue;
            }
            // Simple = comparison
            if (!is_array($wValue)) {
                $wValue = array('=' => $wValue);
            }
            $key = key($wValue);
            $val = $wValue[$key];
            switch (strtolower($key)) {
                case '0':
                    $this->_bindParams($wValue);
                    break;
                case 'not in':
                case 'in':
                    $comparison = ' ' . $key . ' (';
                    if (is_object($val)) {
                        $comparison .= $this->_buildPair('', $val);
                    } else {
                        foreach ($val as $v) {
                            $comparison .= ' ?,';
                            $this->_bindParam($v);
                        }
                    }
                    $this->_query .= rtrim($comparison, ',') . ' ) ';
                    break;
                case 'not between':
                case 'between':
                    $this->_query .= " {$key} ? AND ? ";
                    $this->_bindParams($val);
                    break;
                default:
                    $this->_query .= $this->_buildPair($key, $val);
            }
        }
    }
    /**
     * Abstraction method that will build the GROUP BY part of the WHERE statement
     *
     */
    protected function _buildGroupBy()
    {
        if (empty($this->_groupBy)) {
            return;
        }
        $this->_query .= ' GROUP BY ';
        foreach ($this->_groupBy as $key => $value) {
            $this->_query .= $value . ', ';
        }
        $this->_query = rtrim($this->_query, ', ') . ' ';
    }
    /**
     * Abstraction method that will build the LIMIT part of the WHERE statement
     *
     * @param int   $numRows   The number of rows total to return.
     */
    protected function _buildOrderBy()
    {
        if (empty($this->_orderBy)) {
            return;
        }
        $this->_query .= ' ORDER BY ';
        foreach ($this->_orderBy as $prop => $value) {
            $this->_query .= $prop . ' ' . $value . ', ';
        }
        $this->_query = rtrim($this->_query, ', ') . ' ';
    }
    /**
     * Abstraction method that will build the LIMIT part of the WHERE statement
     *
     * @param int   $numRows   The number of rows total to return.
     */
    protected function _buildLimit($numRows)
    {
        if (!isset($numRows)) {
            return;
        }
        if (is_array($numRows)) {
            $this->_query .= ' LIMIT ' . (int) $numRows[0] . ', ' . (int) $numRows[1];
        } else {
            $this->_query .= ' LIMIT ' . (int) $numRows;
        }
    }
    /**
     * Method attempts to prepare the SQL query
     * and throws an error if there was a problem.
     *
     * @return mysqli_stmt
     */
    protected function _prepareQuery()
    {
        //echo "end query = ".$this->_query;
        //echo "\r\n";
        if (!($stmt = $this->_mysqli->prepare($this->_query))) {
            trigger_error("Problem preparing query ({$this->_query}) " . $this->_mysqli->error, E_USER_ERROR);
        }
        return $stmt;
    }
    /**
     * Close connection
     */
    public function __destruct()
    {
        if (!$this->isSubQuery) {
            return;
        }
        if ($this->_mysqli) {
            $this->_mysqli->close();
        }
    }
    /**
     * @param array $arr
     *
     * @return array
     */
    protected function refValues($arr)
    {
        //Reference is required for PHP 5.3+
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach ($arr as $key => $value) {
                $refs[$key] =& $arr[$key];
            }
            return $refs;
        }
        return $arr;
    }
    /**
     * Function to replace ? with variables from bind variable
     * @param string $str
     * @param Array $vals
     *
     * @return string
     */
    protected function replacePlaceHolders($str, $vals)
    {
        $i = 1;
        $newStr = '';
        while ($pos = strpos($str, '?')) {
            $val = $vals[$i++];
            if (is_object($val)) {
                $val = '[object]';
            }
            $newStr .= substr($str, 0, $pos) . $val;
            $str = substr($str, $pos + 1);
        }
        return $newStr;
    }
    /**
     * Method returns last executed query
     *
     * @return string
     */
    public function getLastQuery()
    {
        return $this->_lastQuery;
    }
    /**
     * Method returns mysql error
     * 
     * @return string
     */
    public function getLastError()
    {
        return $this->_stmtError . ' ' . $this->_mysqli->error;
    }
    /**
     * Mostly internal method to get query and its params out of subquery object
     * after get() and getAll()
     * 
     * @return array
     */
    public function getSubQuery()
    {
        if (!$this->isSubQuery) {
            return null;
        }
        array_shift($this->_bindParams);
        $val = array('query' => $this->_query, 'params' => $this->_bindParams);
        $this->reset();
        return $val;
    }
    /* Helper functions */
    /**
     * Method returns generated interval function as a string
     *
     * @param string interval in the formats:
     *        "1", "-1d" or "- 1 day" -- For interval - 1 day
     *        Supported intervals [s]econd, [m]inute, [h]hour, [d]day, [M]onth, [Y]ear
     *        Default null;
     * @param string Initial date
     *
     * @return string
     */
    public function interval($diff, $func = 'NOW()')
    {
        $types = array('s' => 'second', 'm' => 'minute', 'h' => 'hour', 'd' => 'day', 'M' => 'month', 'Y' => 'year');
        $incr = '+';
        $items = '';
        $type = 'd';
        if ($diff && preg_match('/([+-]?) ?([0-9]+) ?([a-zA-Z]?)/', $diff, $matches)) {
            if (!empty($matches[1])) {
                $incr = $matches[1];
            }
            if (!empty($matches[2])) {
                $items = $matches[2];
            }
            if (!empty($matches[3])) {
                $type = $matches[3];
            }
            if (!in_array($type, array_keys($types))) {
                trigger_error("invalid interval type in '{$diff}'");
            }
            $func .= ' ' . $incr . ' interval ' . $items . ' ' . $types[$type] . ' ';
        }
        return $func;
    }
    /**
     * Method returns generated interval function as an insert/update function
     *
     * @param string interval in the formats:
     *        "1", "-1d" or "- 1 day" -- For interval - 1 day
     *        Supported intervals [s]econd, [m]inute, [h]hour, [d]day, [M]onth, [Y]ear
     *        Default null;
     * @param string Initial date
     *
     * @return array
     */
    public function now($diff = null, $func = 'NOW()')
    {
        return array('[F]' => array($this->interval($diff, $func)));
    }
    /**
     * Method generates incremental function call
     * @param int increment amount. 1 by default
     */
    public function inc($num = 1)
    {
        return array('[I]' => '+' . (int) $num);
    }
    /**
     * Method generates decrimental function call
     * @param int increment amount. 1 by default
     */
    public function dec($num = 1)
    {
        return array('[I]' => '-' . (int) $num);
    }
    /**
     * Method generates change boolean function call
     * @param string column name. null by default
     */
    public function not($col = null)
    {
        return array('[N]' => (string) $col);
    }
    /**
     * Method generates user defined function call
     * @param string user function body
     */
    public function func($expr, $bindParams = null)
    {
        return array('[F]' => array($expr, $bindParams));
    }
    /**
     * Method creates new mysqlidb object for a subquery generation
     */
    public static function subQuery()
    {
        return new MysqliDb();
    }
    /**
     * Method returns a copy of a mysqlidb subquery object
     *
     * @param object new mysqlidb object
     */
    public function copy()
    {
        return clone $this;
    }
    /**
     * Begin a transaction
     *
     * @uses mysqli->autocommit(false)
     * @uses register_shutdown_function(array($this, "_transaction_shutdown_check"))
     */
    public function startTransaction()
    {
        $this->_mysqli->autocommit(false);
        $this->_transaction_in_progress = true;
        register_shutdown_function(array($this, '_transaction_status_check'));
    }
    /**
     * Transaction commit
     *
     * @uses mysqli->commit();
     * @uses mysqli->autocommit(true);
     */
    public function commit()
    {
        $this->_mysqli->commit();
        $this->_transaction_in_progress = false;
        $this->_mysqli->autocommit(true);
    }
    /**
     * Transaction rollback function
     *
     * @uses mysqli->rollback();
     * @uses mysqli->autocommit(true);
     */
    public function rollback()
    {
        $this->_mysqli->rollback();
        $this->_transaction_in_progress = false;
        $this->_mysqli->autocommit(true);
    }
    /**
     * Shutdown handler to rollback uncommited operations in order to keep
     * atomic operations sane.
     *
     * @uses mysqli->rollback();
     */
    public function _transaction_status_check()
    {
        if (!$this->_transaction_in_progress) {
            return;
        }
        $this->rollback();
    }
}

class Model {
    protected static $db = null;
    protected $table;
    public function __construct($tb = null) {
        if ($tb) $this->table = $tb;
        self::$db = new Mysql(SAE_MYSQL_HOST_M, SAE_MYSQL_USER, SAE_MYSQL_PASS, 'app_lyrxf', SAE_MYSQL_PORT);
    }
    public function query($q) {
        return self::$db->search($q);
    }
    public function getBetween($id) {
        $this->orderBy("id", "desc");
        $data_prev_page = $this->get($this->table, 'id', $id, '<');
        $data_cur_page = $this->get($this->table, 'id', $id);
        $this->orderBy("id", "asc");
        $data_next_page = $this->get($this->table, 'id', $id, '>');
        if (isset($data_prev_page[0])) $data_cur_page[0]['prev'] = $data_prev_page[0];
        else $data_cur_page[0]['prev'] = null;
        if (isset($data_next_page[0])) $data_cur_page[0]['next'] = $data_next_page[0];
        else $data_cur_page[0]['next'] = null;
        return $data_cur_page;
    }
    public function like($table, $field, $like = null) {
        if (isset($like)) {
            return self::$db->like($table, $field, $like);
        } else {
            return self::$db->like($this->table, $table, $field);
        }
    }
    public function getInsertId() {
        return self::$db->getInsertId();
    }
    public function where($whereProp, $whereValue) {
        self::$db->where($whereProp, $whereValue);
    }
    public function get($whereProp = null, $whereValue = null, $arg3 = null, $arg4 = null) {
        if ($arg3) {
            self::$db->where($whereValue, $arg3, $arg4);
            return self::$db->get($whereProp);
        }
        if ($whereProp != null && $whereValue != null) {
            self::$db->where($whereProp, $whereValue);
        } elseif ($whereProp) {
            return self::$db->get($whereProp);
        }
        return self::$db->get($this->table);
    }
    public static function Load($array) {
        $app = Application::getInstance();
        if (is_array($array)) {
            print_r($array);
        } else {
            if (file_exists($app->conf('app') .DS. $app->conf('model') .DS. $array . '.php')) {
                require $app->conf('app') .DS. $app->conf('model') .DS. $array . '.php';
            } else DEBUG::printf('模型不存在 - %s',$array.'.php');
        }
    }
    //$sql="ALTER TABLE Users ADD startDate DATE";
    public function AddColumn($name, $field, $type, $size, $isnull) {
        echo $type . "\n";
        if (strcasecmp($type, 'VARCHAR') == 0) $q = "ALTER TABLE `$name` ADD `$field` $type($size) CHARACTER SET utf8 COLLATE utf8_unicode_ci $isnull";
        elseif (strcasecmp($type, 'TEXT') == 0) $q = "ALTER TABLE `$name` ADD `$field` $type CHARACTER SET utf8 COLLATE utf8_unicode_ci $isnull";
        elseif (strcasecmp($type, 'DATETIME') == 0) $q = "ALTER TABLE `$name` ADD `$field` $type $isnull";
        else $q = "ALTER TABLE `$name` ADD `$field` $type($size) $isnull";
        self::$db->alter($q);
    }
    //$sql="ALTER TABLE Users DROP data";
    public function DropColumn($name, $field) {
        $q = "ALTER TABLE `$name` DROP `$field`";
        self::$db->alter($q);
    }
    //$sql="ALTER TABLE Users CHANGE startDate startDate DATE NOT NULL";
    public function ChangeColumn($name, $field, $type, $isnull) {
        $q = "ALTER TABLE `$name` CHANGE `$field` $type $isnull ";
        self::$db->alter($q);
    }
    public function drop($table) {
        self::$db->drop($table);
    }
	/*
	* truncate 清空数据而不删除表.
	*/
    public function truncate($table = null) {
        if (empty($table)) self::$db->truncate($this->table);
        else self::$db->truncate($table);
    }
    public function create($table, $data = null) {
        self::$db->create($table, $data);
    }
    public function save($data) {
        self::$db->insert($this->table, $data);
    }
    public function insert($table, $data) {
        self::$db->insert($table, $data);
    }
    public function orderBy($orderByField, $orderbyDirection = "DESC") {
        self::$db->orderBy($orderByField, $orderbyDirection);
    }
    public function update($arg1, $arg2, $arg3 = null) {
        if (isset($arg3)) {
            $table = $arg1;
            $data = $arg2;
            $primary = $arg3;
            self::$db->where('id', $primary);
            self::$db->update($table, $data);
        } else {
            $data = $arg1;
            $primary = $arg2;
            self::$db->where('id', $primary);
            self::$db->update($this->table, $data);
        }
    }
    public function del($expression = null, $table = null) {
        if ($expression) self::$db->where($expression);
        self::$db->delete($table ? $table : $this->table);
    }
}


//模板类
class View{
    protected static $var = array();
	
	/* 直接执行 php 不使用 模板引擎*/
    public static function display($template, $array = null) {
		$instance = Application::getInstance();
 		$templateFile = $instance->conf['app'] .DS. $instance->conf['view'] .DS. $template;

		if(count(self::$var)){
				foreach(self::$var as $var_arr){
					foreach($var_arr as $k){
						extract($k, EXTR_SKIP);
					}
				}
			}
		if(count($array)) extract($array, EXTR_SKIP);		

        if (file_exists($templateFile)) require $templateFile;
		else DEBUG::printf( "未找到模版文件 - %s",$templateFile);

	}
	
	public static function getFileExt( $file_name )
	{
		while ( $dot = strpos ( $file_name , “.” ))
		{
		$file_name = substr ( $file_name , $dot +1);
		}
		return $file_name ;
	} 
	
	
	/*			使用模板引擎,		*/
    public static function make($template, $array = null) {
		$instance = Application::getInstance();
 		$templateFile = $instance->conf['app'] .DS. $instance->conf['view'] .DS. $template;
		//DEBUG::printf($templateFile);
      
		if(count(self::$var)){
				foreach(self::$var as $var_arr){
					foreach($var_arr as $k){
						extract($k, EXTR_SKIP);
					}
				}
			}
		if(count($array)) extract($array, EXTR_SKIP);
			
			//DEBUG::printf($cacheFile);exit();
			//compile php file
		ob_start();
		if (file_exists($templateFile)) include $templateFile;
		else DEBUG::printf( "未找到模版文件 - %s",$templateFile);
		
		//$buffer = ob_get_contents();
		$buffer = ob_get_clean();
		$str = view::conver($buffer,"@",";");			
		
		//到这里模版语言都变成PHP代码了,写入缓存,然后还得运行一下.

		//创建目录
		if(!is_dir($instance->conf['html']))//目录存在.
			mkdir($instance->conf['html'],0700);
		if(!is_dir(realpath($instance->conf['html']) . DS . $instance->uri_string(1)))
			mkdir(realpath($instance->conf['html']) . DS . $instance->uri_string(1),0700);
		if(!is_dir(realpath($instance->conf['html']) . DS . $instance->uri_string(1) . DS . "cache"))
			mkdir(realpath($instance->conf['html']) . DS . $instance->uri_string(1) . DS . "cache",0700);		

		$cacheFile = realpath($instance->conf['html']) . DS . $instance->uri_string(1) . DS . "cache" . DS . md5(basename($template)) .".cache";

		//写入缓存
		file_put_contents($cacheFile, $str);
		//执行 解析PHP代码 并得到内容
		ob_start();
		include $cacheFile;	
		$htmlBuffer = ob_get_clean();
		//构造静态文件路径
		$htmlFile = str_replace(".php",".html",realpath($instance->conf['html']) . DS . $instance->uri_string(1) . DS . basename($template));
		//写入静态html文件
		file_put_contents($htmlFile, $htmlBuffer);
		
		//输出内容
		echo $htmlBuffer;
    }

    public static function setTemplateCacheDir($templateCacheDir) {

        self::$cacheDir = $templateCacheDir;

    }

	//文件进行MD5比较;
	/*
	$result = md5FileCheck('001.rmvb', '002.rmvb');
	if($result['compare'] == 1){
	echo '文件相同。<br />'.$result['intro'];
	}else{
	echo '文件不同。<br />'.$result['intro'];
	}
	*/
	public static function md5FileCheck($filename1, $filename2)
	{
		$m1 = md5_file($filename1);
		$m2 = md5_file($filename2);
		$result     = array();
		
		if($m1 == $m2)
		{
		     $result['compare'] = 1;
			 $result['intro']   = $filename1.' md5:'.$m1.'<br />'.$filename2.' md5:'.$m2;
		  
		}else{
			 $result['compare'] = 0;
			 $result['intro']   = $filename1.' md5:'.$m1.'<br />'.$filename2.' md5:'.$m2;
		}
		return $result;
	
	}

	public static function bindParam($value){

		self::$var[] = array($value);

	}	

	public static function conver($str, $left, $right) {
		/*$matches = array();
		echo preg_replace("/".$left."(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)".$right."/","<?php foreach(\\1  as $value); echo \\1[\\2]  ?>",$str);
		print_r($matches);
		return;*/
        //if操作
        /*$str = preg_replace( "/".$left."if([^{]+?)".$right."/", "<?php if \\1 { ?>", $str );
        $str = preg_replace( "/".$left."else".$right."/", "<?php } else { ?>", $str );
        $str = preg_replace( "/".$left."elseif([^{]+?)".$right."/", "<?php } elseif \\1 { ?>", $str );
        //foreach操作
        $str = preg_replace("/".$left."foreach([^{]+?)".$right."/","<?php foreach \\1 { ?>",$str);
        $str = preg_replace("/".$left."\/foreach".$right."/","<?php } ?>",$str);
        //for操作
        $str = preg_replace("/".$left."for([^{]+?)".$right."/","<?php for \\1 { ?>",$str);
        $str = preg_replace("/".$left."\/for".$right."/","<?php } ?>",$str);*/
        //输出变量
        $str = preg_replace( "/".$left."([a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)".$right."/", "<?php echo $\\1; ?>", $str );
        //常量输出
       /* $str = preg_replace( "/".$left."([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)".$right."/s", "<?php echo \\1;?>", $str );
        //标签解析
        $str = preg_replace ( "/".$left."\/if".$right."/", "<?php } ?>", $str );
		//数组解析
		$str =  preg_replace("/".$left."(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)".$right."/",
		"<?php foreach(\\1  as \$value); echo \$value[\\2]  ?>",$str);*/

        /*$pattern = array('/'.$left.'/', '/'.$right.'/');
        $replacement = array('<?php ', ' ?>');
        return preg_replace($pattern, $replacement, $str);*/
		return $str;
     }

}

class Controller{
	private static $app;
	
	public function display($template, $array = null){
		$this->bindParam(array('base_url'=>url::baseUrl()));
		if(isset($this->layout))
			view::display($this->layout.DS.$template,$array);//添加 layout;
		else
			view::display($template,$array);//没有设置layout
	}	
	
	public function make($template, $array = null){
		$this->bindParam(array('base_url'=>url::baseUrl()));
		if(isset($this->layout))
			view::make($this->layout.DS.$template,$array);//添加 layout;
		else
			view::make($template,$array);//没有设置layout
	}

	public function bindParam($value){
		view::bindParam($value);
	}		
	
	public function load($file){
		self::$app 		 = realpath(BASE_PATH.'/app/');
         if (file_exists(self::$app . DIRECTORY_SEPARATOR .'library/'.$file . '.php')) {
                require  self::$app . DIRECTORY_SEPARATOR .'library/'.$file . '.php';
				//echo "success load model->".self::$app . DIRECTORY_SEPARATOR . $file . '.php'."<br>";
          } 
	} 		
}



class Http{
	
	public static function post($field){
		if(isset($_POST[$field]))
			return $_POST[$field];
		else
			return $_FILES[$field];		
	}
	
	public static function postAll($array){
		$data = array();

		if(is_array($array)){
			for($i = 0;$i < count($array); $i++){
				 $data[$array[$i]] = isset($_POST[$array[$i]]) ? $_POST[$array[$i]] : 0;
			}
		}
		return $data;		
	}
	
	public static function get($field){
		if(isset($_GET[$field]))
			return $_GET[$field];
		else
			return $_GET[$field];	
	}
	
}

class Input{
	public static function get($field){
		if (array_key_exists($key, $this->items))
		{
			return $this->items[$key];
		}
		return value($default);
	}
	
	public static function has(){
	}
	
	
	public static function all(){
	}
	public static function except(){
	}
}

class DEBUG{
	public static function printf($string,array $args = null){
		Application::$__instance->output($string,$args); 
	}
	
	public static function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	} 
	
	public static function runtime(){ 
		return number_format((self::microtime_float() - LUSER_START)*1000, 4).'ms'; ;
	}
	
}
class ExceptionL extends DEBUG{
	
	public function output($string,array $args = null){
		ob_start();
		header("Content-type: text/html; charset=utf-8");
		echo '
			<style>		
			.blockquote {font-size: 14px;color: #333;font-family: "Microsoft YaHei","simsun","Helvetica Neue",Arial,Helvetica,sans-serif;
			border-width: 1px 1px 1px 6px;
			border-style: solid;
			border-color: #dedede;
			-moz-border-top-colors: none;
			-moz-border-right-colors: none;
			-moz-border-bottom-colors: none;
			-moz-border-left-colors: none;
			border-image: none;
			padding: 20px;
			border-radius: 4px;}
			.blockquote strong {
				display: block;
				font-size: 16px;
				margin-bottom: 10px;
				color: #dedede;
			}
			</style>
			<body><blockquote class="quote border-green doc-quote blockquote"><strong>LUSER PHP FRAMEWORK DEBUG MODE OUTPUT STRING</strong> 
		';
		if(is_array($args))
			echo vsprintf($string,$args);
		else
			echo $string;
			
		echo '
		</blockquote></body>
		';	
		$buffer = ob_get_contents();
		ob_end_clean();	
		echo $buffer;	
	}
}

class Uri extends ExceptionL{
	
	public function uri_string($index = null){
			$uri_segment = explode('/',$this->request_uri);
			return $uri_segment[$index]; 
	}
	
}

class Route extends Uri{
    protected static $__routes = array();
    protected static $__filters = array();
    protected static $__groups = array();
    protected static $__groupName = null;
	protected static $__locomotive = null; //火车头

    static function get($uri, $arg2 = null, $arg3 = null) {
        if ($arg3) {
            self::$__routes[] = array(
                'groupName' => self::$__groupName,
                'method' => 'GET',
                'uri' => self::$__locomotive.$uri,
                'callback' => $arg3,
                'filter' => $arg2
            );
        } else {
            self::$__routes[] = array(
                'groupName' => self::$__groupName,
                'method' => 'GET',
                'uri' => self::$__locomotive.$uri,
                'callback' => $arg2
            );
        }
    }

    static function post($uri, $arg2 = null, $arg3 = null) {
        if ($arg3)
        {
            self::$__routes[] = array(
                'groupName' => self::$__groupName,
                'method' => 'POST',
                'uri' => self::$__locomotive.$uri,
                'callback' => $arg3,
                'filter' => $arg2
            );
        } else {
            self::$__routes[] = array(
                'groupName' => self::$__groupName,
                'method' => 'POST',
                'uri' => self::$__locomotive.$uri,
                'callback' => $arg2
            );
        }
    }
    static function filter($funcName, $callback = null) {
        self::$__filters[] = array(
            $funcName,
            $callback
        );
    }
    static function group($filter, $callback) {
        self::$__groupName = $filter;
        echo $callback();
        self::$__groupName = null;
    }
    public function getRoutes() {
        return self::$__routes;
    }
    public function getFilter() {
        return self::$__filters;
    }
    public function getGroup() {
        return self::$__groups;
    }

    public function removeSlash($uri) {
        return preg_replace('/^(\/*)|(\/*)$/','',$uri);
    }

    public function ReplaceRouteVar($routes, $uri) {
        $inputRouteArr = explode('/', $uri); //分割
        $RoutesAr = explode('/', $routes);
        for ($i = 0; $i < count($RoutesAr); $i++) {
            if (preg_match('/\{[0-9a-zA-Z]*\}/', $RoutesAr[$i]) || !strcasecmp($RoutesAr[$i], $inputRouteArr[$i])) {
                if (preg_match('/\{[0-9a-zA-Z]*\}/', $RoutesAr[$i])) {
                    $params[] = $inputRouteArr[$i];
                    $RoutesAr[$i] = $inputRouteArr[$i];
                }
            } else return array(
                'uri' => $routes,
                'var' => null
            );
        }
        $ReplacedUri = implode('/', $RoutesAr);
        return array(
            'uri' => $ReplacedUri,
            'var' => $params
        );
    }
    public function excuteFilter($filterName) {

        $filters = $this->getFilter();
        foreach ($filters as $filter) {
      
            if (strcmp($filter[0], $filterName) == 0) {
          
                echo $filter[1]();
            }
        }
    }
    public function dispath_process($router, $var) {
        if (isset($router['callback'])) {
            if (isset($router['filter'])) {
                $this->excuteFilter($router['filter']);
            }
            if (isset($router['groupName'])) {
                $this->excuteFilter($router['groupName']);
            }
            if (is_callable($router['callback'])) {
                if (isset($var)) {
                    echo call_user_func_array($router['callback'], $var);
                } else {
                    echo call_user_func($router['callback']);
                }
            } else {
                $action = explode('@', $router['callback']);
                $className = $action[0];
                $classFile = $action[0] . ".php";
                $methodName = $action[1];
				
				//判断配置文件是否存在
				if(empty($this->conf['app']))
				{
					
					$this->output('app 配置项不存在');
					
					exit();
				}

                if (file_exists($this->conf['app'] .DS. $this->conf['controller']  .DS. $classFile)) {
                    require $this->conf['app']  .DS. $this->conf['controller']  .DS. $classFile;
                    $rc = new ReflectionClass($className);
                    if (!$rc->hasMethod($methodName)) {
                   
                        $this->output("控制器方法不存在 - %s",array($methodName));
                  
                        return;
                    } else {
                        $instance = $rc->newInstance();
                        $method = $rc->getMethod($methodName);
                        $method->invokeArgs($instance, $var);
                    }
                } else $this->output("控制器文件不存在 - %s",array($classFile));
            }
        }
    }
    public function compareSegment($route, $uri) {
        return count(explode('/', $route)) == count(explode('/', $uri)) ? TRUE : FALSE;
    }
    public function compareRoute($route, $uri) {
        return !strcasecmp($route, $uri);
    }
    public function isVariableRoute($route) {
        return preg_match('/\{[0-9a-zA-Z]*\}/', $route, $result);
    }
    public function compareMethod($method) {
        return $method == $_SERVER['REQUEST_METHOD']; //如果这里不判断的话 下面多了没必要的判断 
    }
	/**
	*Route Dispatch
	*@param 
	*/
    public function dispath() {
		$routes = $this->getRoutes();

        foreach ($routes as $router) {
			
            if ($this->compareMethod($router['method'])) {
				
               
				//$this->output($router['uri']);
				//$this->output($this->request_uri);				   
			   
			    $uri = $this->removeSlash( $router['uri']);
				
                $cur = $this->removeSlash($this->request_uri); 

				//$this->output($uri);
				//$this->output($cur);
				
                if ($this->compareSegment($cur, $uri)) {
					
                    $var = array();
					
                    if ($this->isVariableRoute($uri)) { //判断是否带参数的 routes
					
                        $varinfo = $this->ReplaceRouteVar($uri, $cur);
						
                        $uri = $varinfo['uri'];
						
                        $var = $varinfo['var'];
						
                    }
					
                    if ($this->compareRoute($uri, $cur)) {
						
                        $this->dispath_process($router, $var);
						
                        exit();
                    }
                }
            }
        }
		
		$this->output("未找到匹配的路由");//header('HTTP/1.1 404 Not Found');
		
    }
}

class autoloader {

    public static $loader;

    public static function init()
    {
        if (self::$loader == NULL)
            self::$loader = new self();

        return self::$loader;
    }

    public function __construct()
    {
        spl_autoload_register(array($this,'model'));
        spl_autoload_register(array($this,'helper'));
        spl_autoload_register(array($this,'controller'));
        spl_autoload_register(array($this,'library'));
    }

    public function library($class)
    {
        set_include_path(get_include_path().PATH_SEPARATOR.'/lib/');
        spl_autoload_extensions('.library.php');
        spl_autoload($class);
    }

    public function controller($class)
    {
        $class = preg_replace('/_controller$/ui','',$class);
       
        set_include_path(get_include_path().PATH_SEPARATOR.'/controller/');
        spl_autoload_extensions('.controller.php');
        spl_autoload($class);
    }

    public function model($class)
    {
        $class = preg_replace('/_model$/ui','',$class);
       
        set_include_path(get_include_path().PATH_SEPARATOR.'/model/');
        spl_autoload_extensions('.model.php');
        spl_autoload($class);
    }

    public function helper($class)
    {
        $class = preg_replace('/_helper$/ui','',$class);

        set_include_path(get_include_path().PATH_SEPARATOR.'/helper/');
        spl_autoload_extensions('.helper.php');
        spl_autoload($class);
    }

}


class Application extends Route {
    protected $base_url;
	protected $request_uri;
    protected $path = array();
	public    $conf = array();
    public static $__instance;
	
    public function __construct() {
		
        self::$__instance = $this;
    }
    public static function getInstance() {
        return self::$__instance;
    }
	
	public function _bindParams($conf){
		$this->conf = $conf;
	}
	
	public function load(){
		
	}
	
    public function run() {

		//init loader;
		autoloader::init();
		
        $this->request_uri = $_SERVER['REQUEST_URI'];

		$s1 = $this->uri_string(1);
		//echo ROOT.DS.ROUTE_DIR.DS.$s1.ROUTE_PHP_FILE;
		if(file_exists(ROOT.DS.ROUTE_DIR.DS.$s1.ROUTE_PHP_FILE)){
			//$this->output($s1);
			self::$__locomotive = $s1.DS;//后面加个斜杠 比如 xxx.com/ or xx.com/blog/  
			require ROOT.DS.ROUTE_DIR.DS.$s1.ROUTE_PHP_FILE;
		}
		else{
			if(file_exists(ROOT.DS.ROUTE_DIR.DS.ROUTE_DIR.PHP_FILE)){//加载基本路由配置文件
				require ROOT.DS.ROUTE_DIR.DS.ROUTE_DIR.PHP_FILE;
			}
			else{
				$this->output("未找到基本路由配置文件 routes.php not Found FILE_PATH = %s",array(ROOT.DS.ROUTE_DIR.DS.ROUTE_DIR.PHP_FILE));
			}
		}

        $this->dispath();
		
    }
}

  
?>