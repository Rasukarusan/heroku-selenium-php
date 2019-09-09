<?php
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;

/**
 * selenium facebook-webdriver 実行のサンプル
 */
class Main {
    const SELENIUM_TIMEOUT_SEC = 60; // seleniumのタイムアウト時間(秒)

    function run() {
        $driver = $this->createDriver(); 
        $driver->get('https://www.google.co.jp/');
        $element = $driver->findElement(WebDriverBy::name('q'));
        $element->sendKeys('セレニウムで自動操作');
        $element->submit();

        // キャプチャ
        $file = __DIR__ . '/sample_chrome.png';
        $driver->takeScreenshot($file);
        echo '<img src="'.$file.'">';

        $driver->close();
    }

    private function createDriver() {
        $options = new ChromeOptions();
        $options->addArguments(array(
            '--headless',
            '--no-sandbox',
            '--disable-gpu',
        ));
        if(!empty(getenv('GOOGLE_CHROME_SHIM'))) {
            // Remote/Service/DriverService.phpで環境変数からchromedriverを取得しているため必要
            putenv('webdriver.chrome.driver=/app/.chromedriver/bin/chromedriver');
            $options->setBinary(getenv('GOOGLE_CHROME_SHIM'));
        }else {
            // ローカルでwhich chromedriverとコマンドを打って表示されるPATH
            putenv('webdriver.chrome.driver=/usr/local/bin/chromedriver');
        }
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $driver = ChromeDriver::start($capabilities);
        $driver->manage()->window()->maximize();
        $driver->manage()->timeouts()->implicitlyWait(self::SELENIUM_TIMEOUT_SEC);
        $driver->manage()->timeouts()->pageLoadTimeout(self::SELENIUM_TIMEOUT_SEC);
        $driver->manage()->timeouts()->setScriptTimeout(self::SELENIUM_TIMEOUT_SEC);
        return $driver;
    }
}

