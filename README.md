# Utils
一些常用的辅助工具类。

## Config
类似`Laravel`的配置类，可以通过"."分割的方式设置、获取配置信息。

- `loadPath($path)`：加载目录下的PHP文件，设置为以文件名为key的Config的配置项
- `loadFile($file_name)`：加载指定文件，设置文件名为key
- `set($key, $value)`：设置配置项`$key`的值为`$value`
- `get($key, $default = null)`：获取`$key`的配置值

假设目录`/tmp/config/`目录下有文件`mysql.php`：
```php
return [
    'web' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'root',
        'pass' => 'root',
        'database' => 'web_db'
    ],
    'server' => [
        'host' => '127.0.0.2',
        'port' => '3306',
        'user' => 'ruesin',
        'pass' => 'ruesin',
        'database' => 'server_db'
    ]
];
```
使用：
```php
//加载 /tmp/config 目录下的所有 php 文件，文件名作为配置数组的key
Config::loadPath('/tmp/config/');
//加载 /tmp/config/mysql.php 文件
Config::loadFile('/tmp/config/mysql.php');
/*
Config::$config = [
    'mysql' => [
        'web' => [...],
        'server' => [....],
    ]
];
*/
//将 Config::$config['mysql']['web']['user'] 的值改为 ruesin
Config::set('mysql.web.user', 'ruesin');

//获取Config::$config['mysql']['server']的值
print_r(Config::get('mysql.server'));
/*
[
    'host' => '127.0.0.2',
    'port' => '3306',
    'user' => 'ruesin',
    'pass' => 'ruesin',
    'database' => 'server_db'
]
*/

```

## Log

使用`file_put_contents`的简单文件日志类，默认文件写入`/tmp/`目录，可以通过初始化`init($config)`更改配置。

```php
//初始化配置，log_path为日志存储目录，unique_id为日志文件唯一标识，默认为当前进程号。
Log::init(['log_path' => '/your-log-path/', 'unique_id' => '123']);

//向 /your-log-path/ruesin/default.log 文件追加写入 This is my message!
Log::msg('This is my message!', 'default', 'ruesin');

//向 /your-log-path/ruesin/info.log 文件追加写入 [2019-01-14 22:53:00] This is my info!
Log::info('This is my info!', 'info', 'ruesin');

```



