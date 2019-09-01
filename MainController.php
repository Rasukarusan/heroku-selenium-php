<?php

require_once './vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;

class Main {
    const SELENIUM_TIMEOUT_SEC = 800; // seleniumのタイムアウト時間(秒)

    /**
     * selenium facebook-webdriver 実行のサンプル
     */
    function run() {
        $driver = $this->createDriver(); // オプション起動(headless等)
        // 指定URLへ遷移 (Google)
        $driver->get('https://www.google.co.jp/');
        // 検索Box
        $element = $driver->findElement(WebDriverBy::name('q'));
        // 検索Boxにキーワードを入力して
        $element->sendKeys('セレニウムで自動操作');
        // 検索実行
        $element->submit();

        // 検索結果画面のタイトルが 'セレニウムで自動操作 - Google 検索' になるまで10秒間待機する
        // 指定したタイトルにならずに10秒以上経ったら
        // 'Facebook\WebDriver\Exception\TimeOutException' がthrowされる
        $driver->wait(10)->until(
            WebDriverExpectedCondition::titleIs('セレニウムで自動操作 - Google 検索')
        );

        // セレニウムで自動操作 - Google 検索 というタイトルを取得できることを確認する
        if ($driver->getTitle() !== 'セレニウムで自動操作 - Google 検索') {
            throw new Exception('fail');
        }
        // キャプチャ
        $file = __DIR__ . '/sample_chrome.png';
        $driver->takeScreenshot($file);

        // ブラウザを閉じる
        $driver->close();
    }


    private function createDriver() {
        $options = new ChromeOptions();
        $options->addArguments(array(
            "--headless",
            "--no-sandbox",
            "--disable-gpu",
        ));
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $host = 'http://localhost:4444/wd/hub';
        $driver = RemoteWebDriver::create($host, $capabilities);
        $driver->manage()->window()->maximize();
        $driver->manage()->timeouts()->implicitlyWait(self::SELENIUM_TIMEOUT_SEC);
        $driver->manage()->timeouts()->pageLoadTimeout(self::SELENIUM_TIMEOUT_SEC);
        $driver->manage()->timeouts()->setScriptTimeout(self::SELENIUM_TIMEOUT_SEC);
        return $driver;
    }
}

