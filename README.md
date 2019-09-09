# heroku-selenium-php
selenium on herokuを構築する雛形

## Herokuにデプロイするときの準備

Seleniumを動かすためのbuildpackを追加する
```bash
heroku buildpacks:add https://github.com/heroku/heroku-buildpack-google-chrome -a php-selenium
heroku buildpacks:add https://github.com/heroku/heroku-buildpack-google-chrome -a php-selenium
```
app.jsonに書く方法はできない。公式では未サポートだから。

---

# 以下メモ

- エラー
```zsh
Uncaught Facebook\WebDriver\Exception\TimeOutException: Timed out waiting for http://localhost:9515/status to become available after 20000 ms. in /app/vendor/facebook/webdriver/lib/Net/URLChecker.php:37
```
- 原因

chromedriverのパスが違う

- 解決

パスを確認
```zsh
$ heroku run bash -a php-selenium
Running bash on ⬢ php-selenium... up, run.9548 (Free)

~ $ which chromedriver
/app/.chromedriver/bin/chromedriver
```

phpのソースを修正
```php
putenv('webdriver.chrome.driver=/app/.chromedriver/bin/chromedriver');
```

- エラー

```zsh
2019-09-01T01:30:02.837587+00:00 app[web.1]: [01-Sep-2019 01:30:02 UTC] PHP Fatal error:  Uncaught Facebook\WebDriver\Exception\TimeOutException in /app/vendor/facebook/webdriver/lib/WebDriverWait.php:84
2019-09-01T01:30:02.837797+00:00 app[web.1]: Stack trace:
2019-09-01T01:30:02.838105+00:00 app[web.1]: #0 /app/MainController.php(33): Facebook\WebDriver\WebDriverWait->until(Object(Facebook\WebDriver\WebDriverExpectedCondition))
2019-09-01T01:30:02.838343+00:00 app[web.1]: #1 /app/index.php(5): Main->run()
2019-09-01T01:30:02.838389+00:00 app[web.1]: #2 {main}
2019-09-01T01:30:02.838590+00:00 app[web.1]: thrown in /app/vendor/facebook/webdriver/lib/WebDriverWait.php on line 84
```

- 原因

表示したページが文字化けしてるから取得できずにタイムアウトになる。
Herokuには日本語フォントがない。

- 解決

.fontsディレクトリを配置すればOK。
