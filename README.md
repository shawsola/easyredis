#Usage example

[![StyleCI](https://styleci.io/repos/63835125/shield?branch=master)](https://styleci.io/repos/63835125)
[![Build Status](https://travis-ci.org/shawsola/easyredis.svg?branch=master)](https://travis-ci.org/shawsola/easyredis)

```
$client = new Redis('127.0.0.1', 6379, 30);
$client->set('key', 'value');
$value = $client->get('key'); //value
```

Full list of commands you can see on http://redis.io/commands
