操作日志记录器
========

### Yii2 configuration

```bash
./yii migrate --migration-path=@vendor/xlerr/operate-log/src/migrations
```

```php
'on beforeRequest' => function ($event) {
    \xlerr\operatelog\Recorder::register([
        \yii\web\IdentityInterface::class,
    ]);
},
```