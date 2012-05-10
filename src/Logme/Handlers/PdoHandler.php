<?php

/**
 * Artax PDO Log Handler Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;

use Logme\FormatterInterface,
    Logme\FilterInterface,
    PDO;

/**
 * A database log handler that logs events using the specified PDO instance
 * 
 * ### Basic Usage
 * 
 * ```php
 * <?php
 * 
 * use Logme\Logger,
 *     Logme\Handlers\PdoHandler;
 * 
 * // Set up our PdoHandler
 * $pdo    = new PDO('sqlite:/path/to/log_db.sqlite');
 * $fields = ['time', 'msg', 'level'];
 * $pdoh   = new PdoHandler($pdo, 'my_log_table', $fields);
 * 
 * // We probably only want to log important stuff to the database
 * $pdoh->setThreshold(Logger::WARNING);
 * 
 * // Create a logger and attache the PdoHandler instance
 * $log = new Logger;
 * $log->addHandler($pdoh);
 * 
 * $log->warning('warning message');
 * ```
 * 
 * The above code will insert a new row into `my_log_table` with the following
 * SQL syntax, replacing the prepared statement placeholders:
 * 
 * ```sql
 * INSERT INTO my_log_table (msg, level) VALUES (?, ?);
 * ```
 * 
 * You must specify the column names the handler should write to by passing
 * a `$fields` array to the constructor like so:
 * 
 * ```php
 * $fields = ['msg', 'extra_field'];
 * $pdoh   = new PdoHandler(Logger::DEBUG, $pdo, 'my_log_table', $fields);
 * $log->addHandler($dbh);
 * 
 * $log->debug('debug message', ['extra_field' => 42]);
 * ```
 * 
 * The above code will insert a new row into `my_log_table` with the following
 * SQL syntax using a prepared statement:
 * 
 * ```sql
 * INSERT INTO my_log_table (msg, extra_field) VALUES (?, ?);
 * ```
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class PdoHandler extends HandlerAbstract
{
    /**
     * An array specifying which log fields to write to the database table
     * 
     * This can be a simple indexed array like `['time', 'msg', 'level']` or
     * an associative array that uses key names to map log record fields
     * to alias database column names. Consider:
     * 
     * ```php
     * $fields = ['fmtTime'=>'logged_at', 'fmtMsg'=>'msg', 'level'];
     * ```
     * 
     * The resulting SQL statement will look like the code below:
     * 
     * ```sql
     * INSERT INTO my_log_table (logged_at, msg, level) VALUES (?, ?, ?);
     * ```
     * The corresponding parameters will be the formatted time and message
     * values (fmtTime, fmtMsg) and the log's severity level.
     * 
     * @var array
     */
    private $fields;
    
    /**
     * The PDO connection instance used to write log events to the database
     * @var PDO
     */
    private $pdo;
    
    /**
     * The name of the table to which log events will be written
     * @var string
     */
    private $table;
    
    /**
     * Constructs a PdoHandler instance
     * 
     * @param PDO                $pdo       The PDO connection instance
     * @param string             $table     The DB table in which to insert
     * @param array              $fields    The log record fields to insert
     * @param FormatterInterface $formatter An optional formatting object
     * @param FilterInterface    $filter    An optional filtering object
     * 
     * @return void
     */
    public function __construct(PDO $pdo, $table, array $fields,
        FormatterInterface $formatter = NULL, FilterInterface $filter = NULL
    )
    {
        parent::__construct($formatter, $filter);
        
        $this->fields = $fields;
        $this->pdo    = $pdo;
        $this->table  = $table;
    }
    
    /**
     * Log a message to the specified table
     * 
     * @param array $vals A key-value array of formatted log record values
     * 
     * @return void
     */
    protected function emit(array $vals)
    {
        $data = [];
        foreach ($this->fields as $field) {
            if (is_array($field)) {
                reset($field);
                $data[current($field)] = $vals[key($field)];
            } else {
                $data[$field] = $vals[$field];
            }
        }
        
        $cols = array_keys($data);
        
        $q = 'INSERT INTO '.$this->table.' ('. implode(',', $cols) .') ' .
             'VALUES ('. substr(str_repeat('?,', count($data)), 0, -1) .')';
        
        $stmt = $this->pdo->prepare($q);
        $stmt->execute(array_values($data));
    }
}
