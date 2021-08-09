<?php
  require_once('vendor/autoload.php');
  use Facebook\WebDriver\Remote\RemoteWebDriver;
  use Facebook\WebDriver\WebDriverBy;

  $username = getenv('BROWSERSTACK_USERNAME');
  $accessKey = getenv('BROWSERSTACK_ACCESS_KEY');
  $buildName = getenv('BROWSERSTACK_BUILD_NAME');
  $browserstackLocal = getenv('BROWSERSTACK_LOCAL');
  $browserstackLocalIdentifier = getenv('BROWSERSTACK_LOCAL_IDENTIFIER');
  print("\n".$username);
  print("\n".$accessKey);
  print("\n".$buildName);
  print("\n".$browserstackLocal);
  print("\n".$browserstackLocalIdentifier);

  $caps = array(
    "os_version" => "10",
    "resolution" => "1920x1080",
    "browser" => "Chrome",
    "browser_version" => "latest",
    "os" => "Windows",
    "name" => "BStack-[Php] Sample Test", // test name
    "build" => "".$buildName, // CI/CD job or build name
    "browserstack.local" => "".$browserstackLocal,
    "browserstack.localIdentifier" => "".$browserstackLocalIdentifier
  );
  $web_driver = RemoteWebDriver::create(
    "https://".$username.":".$accessKey."@hub-cloud.browserstack.com/wd/hub",
    $caps
  );
  # Searching for 'BrowserStack' on google.com
  $web_driver->get("http://localhost:8888");
  sleep(2);
  $web_driver->get("http://google.com");
  $element = $web_driver->findElement(WebDriverBy::name("q"));
  if($element) {
      $element->sendKeys("BrowserStack");
      $element->submit();
  }
  print $web_driver->getTitle();
  # Setting the status of test as 'passed' or 'failed' based on the condition; if title of the web page starts with 'BrowserStack'
  if (substr($web_driver->getTitle(),0,12) == "BrowserStack"){
      $web_driver->executeScript('browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"passed", "reason": "Yaay! Title matched!"}}' );
  }  else {
      $web_driver->executeScript('browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"failed", "reason": "Oops! Title did not match!"}}');
  }
  $web_driver->quit();
?> 