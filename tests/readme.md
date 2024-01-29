# Prerequisite

---

1. For initial project setup, make sure you have set file `.env.testing` (db_name, etc)
2. If db for testing not created yet, create first with command `php artisan migrate --env=testing --seed`

# Start Test

---

Type command `php artisan test` to simply run all test case. But if you want to run scope of test, you can:

1. define testsuite on `phpunit.xml`

```
<testsuite name="YourTestSuiteName">
    <directory>tests/Feature/YourTestSuite/Path</directory>
</testsuite>
```

2. then, type command `php artisan test --testsuite=YourTestSuiteName`
