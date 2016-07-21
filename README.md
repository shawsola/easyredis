#Usage example

```
$client = new Redis('127.0.0.1',6379,30);
$client->set('key', 'value');
$value = $client->get('key');
```

Full list of commands you can see on http://redis.io/commands