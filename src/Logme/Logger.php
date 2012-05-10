<?php

/**
 * Logger Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme;

use Logme\Handlers\HandlerInterface,
    BadMethodCallException,
    InvalidArgumentException,
    RangeException,
    Exception,
    DomainException,
    ReflectionClass;

/**
 * Logger Class
 *
 * @category     Logme
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class Logger implements LoggerInterface
{
    use EmitterTrait;
    
    /**
     * Critical log event integer constant
     * @var int
     */
    const CRITICAL = 1;
    
    /**
     * Error log event integer constant
     * @var int
     */
    const ERROR = 16;
    
    /**
     * Warning log event integer constant
     * @var int
     */
    const WARNING = 32;
    
    /**
     * Info log event integer constant
     * @var int
     */
    const INFO = 64;
    
    /**
     * Debug log event integer constant
     * @var int
     */
    const DEBUG = 128;
    
    /**
     * A key-value list of built-in error levels that cannot be overridden
     * @var array
     */
    private $builtinLevels = [
        self::CRITICAL => 'critical',
        self::ERROR    => 'error',
        self::WARNING  => 'warning',
        self::INFO     => 'info',
        self::DEBUG    => 'debug'
    ];
    
    /**
     * A key-value list of attached log event handlers
     * @var array
     */
    private $handlers = [];
    
    /**
     * A key-value list of supported log event levels
     * @var array
     */
    private $levels;
    
    /**
     * Which debug backtrace stack frame to use for debug info
     * 
     * If the log method is invoked through the magic __call method using
     * the name of a log level we pull information from stack frame 3. If
     * the log method is called directly we use frame zero. This value
     * is automatically assigned by the relevant methods as needed.
     * 
     * @var int
     */
    private $traceFrame = 0;
    
    /**
     * Exposes log-level specific magic methods for logging
     * 
     * @param string $method The specified method name
     * @param array  $args   Arguments specified for the called method
     * 
     * @return Logger Returns object instance for method chaining
     * @throws InvalidArgumentException On missing log argument
     * @throws BadMethodCallException On method name with no corresponding log level
     */
    public function __call($method, $args)
    {
        if ($level = array_search($method, $this->levels)) {
            if ($args) {
                $msg   = array_shift($args);
                $extra = $args ? $args[0] : [];
                $methodArgs = [$level, $msg, $extra];
                $this->traceFrame = 3;
                return call_user_func_array([$this, 'log'], $methodArgs);
            } else {
                throw new InvalidArgumentException(
                    get_class($this) . "::$method expects a log message at" .
                    ' argument 2: none specified'
                );
            }
        }
        throw new BadMethodCallException(
            'Invalid method: ' . get_class($this) . "::$method does not exist "
            .'or is not callable in the current scope'
        );
    }
    
    /**
     * Initialize instance with optional severity level mask
     * 
     * @param int             $mask   An instance-wide mask for all attached
     *                                handlers
     * @param FilterInterface $filter An optional log record emission filter
     * 
     * @return void
     */
    public function __construct(FilterInterface $filter = NULL)
    {
        $this->filter    = $filter;
        $this->levels    = $this->builtinLevels;
        $this->threshold = self::DEBUG;
    }
    
    /**
     * Attach a handler to the logger
     * 
     * @param HandlerInterface $handler A logging handler object instance
     * 
     * @return HandlerInterface Returns the attached handler instance
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;
        return $handler;
    }
    
    /**
     * Add a custom error reporting level
     * 
     * @param int    $level     The integer log level
     * @param string $levelName The name of the custom log reporting level
     * 
     * @return void
     * @throws DomainException If a reserved integer log level is specified
     */
    public function addLevel($level, $levelName)
    {
        $level = (int) $level;
        if (isset($this->builtinLevels[$level])) {
            throw new DomainException(
                "Invalid custom logging level: $level ("
                .$this->builtinLevels[$level] . ') cannot be overridden'
            );
        }
        $this->levels[$level] = $levelName;
    }
    
    /**
     * Notify registered handlers of a log event
     * 
     * @param int    $level The integer log level
     * @param string $msg   The log event message
     * @param int    $extra Optional extra fields for logging
     * 
     * @return LogRecord Returns the LogRecord that was handled
     */
    public function log($level, $msg, array $extra = [])
    {
        if (!isset($this->levels[$level])) {
            throw new RangeException(
                "Invalid logging level: level $level not specified"
            );
        }
        
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[$this->traceFrame];
        $this->traceFrame = 0;
        
        $vals = [
            'msg'       => $msg,
            'level'     => $level,
            'levelName' => $this->levels[$level],
            'file'      => isset($trace['file']) ? $trace['file'] : NULL,
            'line'      => isset($trace['line']) ? $trace['line'] : NULL,
            'function'  => isset($trace['function']) ? $trace['function'] : NULL,
            'class'     => isset($trace['class']) ? $trace['class'] : NULL
        ];
        $vals['time'] = isset($vals['time']) ? $vals['time'] : time();
        $vals = array_merge($extra, $vals);
        
        $logRec = $this->makeLogRecord($vals);
        
        if(!$this->canHandle($logRec)) {
            return 0;
        }
        
        $emitCount = 0;
        foreach ($this->handlers as $handler) {
            try {
                $emitCount += $handler->handle($logRec) ? 1 : 0;
            } catch (Exception $e) {
                // do something
            }
        }
        
        return $emitCount;
    }
    
    /**
     * Factory method for creating LogRecord instances
     * 
     * @param array $vals An array of log values to populate the log record
     * 
     * @return LogRecord Returns an instantiated LogRecord
     */
    public function makeLogRecord(array $vals)
    {
        return new LogRecord($vals);
    }
}
