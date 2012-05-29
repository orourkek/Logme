<?php

/**
 * LogRecord Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
 
namespace Logme;
use Serializable,
    JsonSerializable,
    OutOfBoundsException,
    InvalidArgumentException;

/**
 * LogRecord Class
 * 
 * @category     Logme
 * @package      Base
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class LogRecord implements LogRecordInterface
{
    /**
     * A key-value array storing log record properties
     * @var array
     */
    private $vals = array();
    
    /**
     * Initializes the value object from an associative array
     * 
     * The required array keys are:
     * 
     *  * msg
     *  * level
     *  * levelName
     *  * time
     * 
     * @param array $vals An array of log values to populate the object
     * 
     * @return void
     */
    public function __construct(array $vals)
    {
        $this->populateFromArr($vals);
    }
    
    /**
     * Property accessor method
     * 
     * @param string $prop An log record property name
     * 
     * @return mixed Returns the value of the requested property
     * @throws OutOfBoundsException If a nonexistent property is requested
     */
    public function fetch($prop)
    {
        if (isset($this->vals[$prop])
            || array_key_exists($prop, $this->vals)
        ) {
            return $this->vals[$prop];
        } else {
            throw new OutOfBoundsException(
                get_class($this) . "::\$$prop does not exist"
            );
        }
    }
    
    /**
     * Retrieves an array representation of the log record
     * 
     * @return array Returns an array of log record values
     */
    public function getValsArr()
    {
        return $this->vals;
    }
    
    /**
     * Serializes the object as an array of its properties
     * 
     * @return string Returns serialized object representation
     */
    public function serialize()
    {
        return serialize($this->vals);
    }
    
    /**
     * Instantiates a new LogRecord from a serialized representation
     * 
     * @param string $data A serialized representation of the object
     * 
     * @return void
     */
    public function unserialize($data)
    {
        $arr = unserialize($data);
        $this->populateFromArr($arr);
    }
    
    /**
     * Populates log record properties from a key-value array
     * 
     * @param array $vals A key-value array of log record properties
     * 
     * @return void
     * @throws InvalidArgumentException On missing required key
     */
    private function populateFromArr(array $vals)
    {
        $required = array('msg', 'time', 'level', 'levelName');
        
        foreach ($required as $key) {
            if (!isset($vals[$key])) {
                throw new InvalidArgumentException(
                    get_class($this) . '::__construct requires an array with '.
                    'the keys: ' . substr(implode(', ', $required), 0, -1)
                );
            }
        }
        
        $this->vals = $vals;
    }
}
