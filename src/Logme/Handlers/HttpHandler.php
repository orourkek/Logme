<?php

/**
 * Http Logging Handler Class File
 * 
 * PHP version 5.4
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */

namespace Logme\Handlers;
use Logme\LogRecordInterface;

/**
 * A handler that logs messages to a web server resource
 * 
 * @category     Logme
 * @package      Handlers
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class HttpHandler extends HandlerAbstract
{    
    /**
     * An optional array of headers to submit with the request
     * 
     * @var headers
     */
    private $headers;
    
    /**
     * The HTTP method to use when submitting log data to a web server
     * 
     * @var string
     */
    private $method;
    
    /**
     * The URL to which the log data should be submitted
     * 
     * @var string
     */
    private $url;
    
    /**
     * Assigns the remote URL and HTTP method to use for logging
     * 
     * @param string             $url       
     * @param string             $method    
     * @param array              $headers   
     * @param FormatterInterface $formatter 
     * @param FilterInterface    $filter    
     * 
     * @return void
     */
    public function __construct($url, $method = 'POST', array $headers = NULL,
        FormatterInterface $formatter = NULL, FilterInterface $filter = NULL
    )
    {
        parent::__construct($formatter, $filter);
        
        $this->headers = $headers;
        $this->method  = $method;
        $this->url     = $url;
    }
    
    /**
     * Log an event to a web server
     * 
     * @param array $vals A key-value array of formatted log record values
     * 
     * @return void
     */
    protected function emit(array $vals)
    {
        $headers = '';
        foreach ($this->headers as $key => $value) {
            $headers .= "$key: $value\r\n";
        }
        $opts = ['http' => [
            'method' => $this->method,
            'header' => $headers,
            'data'   => http_build_query($vals)
        ]];
        $context = stream_context_create($opts);
        
        $fp = @fopen($url, 'rb', FALSE, $context);
        if (!$fp) {
            throw new RuntimeException("Problem with $this->url");
        }
        $response = @stream_get_contents($fp);
        if ($response === FALSE) {
            throw new RuntimeException("Problem reading data from $this->url");
        }
    }
}
