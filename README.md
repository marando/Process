Process
=======
Process is a PHP package for managing external executable processes.


Installation
------------
### With Composer

```
$ composer require marando/process
```


Usage
-----

### Initializing a New Process
New processes can be initialized by passing the process you wish to run to the first argument of the constructor:
```php
$proc = new Process('ping google.com');
```

### Starting a Process
The process will not start until you call the `start()` method:
```php
$proc = new Process('ping google.com');
$proc->start();
```

### Killing a Process
If you wish to prematurely kill a process use the `kill()` method:
```php
$proc = new Process('ping google.com');
$proc->start();
$proc->kill();
```

### Status of a Process
You can check if a process is currently running by using the `isRunning()` method:
```php
$proc = new Process('ping google.com');
$proc->start();
$proc->isRunning();
```

### Getting the PID
If you need to know the PID of the process, use the `pid` property:
```php
$proc = new Process('ping google.com');
$proc->start();
$proc->pid;
```

### Waiting for Completion
The `wait()` method will hold up code execution until the process has completed:
```php
$proc = new Process('ping google.com');
$proc->start();
$proc->wait();
```

### Process Output
You can also get the output of the process by accessing the log property, which will return a PHP `SplFileObject` instance that contains the output of the process. To enable this behavior you must first pass the path of the desired output file to the constructor:
```php
$proc = new Process('ping google.com', 'logfile.log');
$proc->start();
$proc->wait();

// Echo first 100 characters of the output.
echo $proc->log->fread(100);
```


