### What Is it?

Logme is a logging package for PHP 5.4+.

Traditional PHP logging libraries have used `static` and/or Singleton
implementations to shoehorn logging functionality into globals with
no regard for code testability. Logme avoids this pitfall and allows
developers to use Dependency Injection to implement logging functionality
in their code.

### Features

* **Log Handler Chain**

Logme uses a variation on the Chain of Responsiblity pattern to attach
multiple log handlers, giving the developer full control over what events
are logged and to which destinations. Consider the needs of a typical web
application:

> You wish to write low-priority notices or warnings to a simple log file
but direct more severe log events like uncaught exceptions or critical
system failures to a database or REST API for appropriate handling and
notification.

Logme turns this kind of complex logging into a single orthogonal method
call.

* **Custom Filters**

Handlers may also implement fully-customizable filters for fine-grained
control over which events are actually logged.

* **Log Output Formatting**

Formatter objects expose a standardized interface for formatting log values.

* **Extensible Built-in Handlers**

Logme comes packaged with several built-in handlers for logging to files,
database resources, web servers and the console. More importantly, Logme
makes creating your own custom handlers a triviality.

### Basic Usage

```php
use Logme\Logger,
    Logme\Handlers\ConsoleHandler;

// Require the Logme bootstrap file
require '/hard/path/to/Logme.php';

$logger         = new Logger;
$consoleHandler = new ConsoleHandler;
$fileHandler    = new FileHandler('/path/to/my/log_file.txt');

$fileHandler->setThreshold(Logger::WARNING);

$logger->addHandler($consoleHandler);
$logger->addHandler($fileHandler);

$log->debug('appears in console but not log_file.txt');
$log->warning('will be handled by console handler AND file handler');
```
