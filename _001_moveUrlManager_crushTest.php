Hello, Guys!
Can you help me?
I have yii-advanced-app.
I have simple the backend acceptance test:
<?php
    # backend/tests/acceptance/AcfCest.php
    ...
    public function accessToIndexNotLoginnedUser(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/'));
        $I->seeInCurrentUrl('/login');
        $I->seeLink('Login');
    }
    ...
?>

I have config backend url manager:
<?php
    # backend/config/main.php
....
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'hostInfo' => $params['backendHostInfo'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'rules' => [
                '' => 'site/index',
                '<_a:login|logout>' => 'site/<_a>',

                '<_c:[\w\-]+>' => '<_c>/index',
                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
                '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
            ],
        ],
....
?>

Here test passed OK.

Then I try move the config of urlManager to backend/config/urlManager.php:

<?php
    # backend/config/main.php
....
    'backendUrlManager' => require __DIR__ . '/urlManager.php',
    'urlManager' => function () {
        return \Yii::$app->get('backendUrlManager');
    }
....
?>
<?php
    # backend/config/urlManager.php
    return [
        'class' => 'yii\web\UrlManager',
        'hostInfo' => $params['backendHostInfo'],
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'suffix' => '/',
        'rules' => [
            '' => 'site/index',
            '<_a:login|logout>' => 'site/<_a>',

            '<_c:[\w\-]+>' => '<_c>/index',
            '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
            '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
            '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
        ],
    ];
?>

And now Test broken:
p@pc:$ clear && ./vendor/bin/codecept run -c backend --steps --debug

Codeception PHP Testing Framework v2.4.5
Powered by PHPUnit 7.3.4 by Sebastian Bergmann and contributors.

Backend\tests.acceptance Tests (1) ---------------------------------------------------------------------------------------------------------------------------------------
Modules: PhpBrowser, \backend\tests\Helper\Acceptance, Yii2
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
AcfCest: Access to index not loginned user
Signature: backend\tests\acceptance\AcfCest:accessToIndexNotLoginnedUser
Test: tests/acceptance/AcfCest.php:accessToIndexNotLoginnedUser
Scenario --
  Destroying application
  Starting application
  [ConnectionWatcher] watching new connections
  [Fixtures] Loading fixtures
  [Fixtures] Done
  [TransactionForcer] watching new connections
 I am on page "index-test.php?r="
  [Request Headers] []
  [Page] index-test.php?r=
  [Response] 302
  [Request Cookies] []
  [Response Headers] {"Date":["Mon, 10 Sep 2018 05:41:05 GMT"],"Server":["Apache/2.4.34 (Ubuntu)"],"Set-Cookie":["_session=hf5f9a1fkh2fcndmmjpfhvhhgc; path=/; HttpOnly"],"Expires":["Thu, 19 Nov 1981 08:52:00 GMT"],"Cache-Control":["no-store, no-cache, must-revalidate"],"Pragma":["no-cache"],"Location":["http://bnd.ys3.l/index-test.php?r=site%2Flogin"],"Content-Length":["0"],"Content-Type":["text/html; charset=UTF-8"]}
  [Redirecting to] http://bnd.ys3.l/index-test.php?r=site%2Flogin
  [Page] http://bnd.ys3.l/index-test.php?r=site%2Flogin
  [Response] 200
  [Request Cookies] {"_session":"hf5f9a1fkh2fcndmmjpfhvhhgc"}
  [Response Headers] {"Date":["Mon, 10 Sep 2018 05:41:05 GMT"],"Server":["Apache/2.4.34 (Ubuntu)"],"Expires":["Thu, 19 Nov 1981 08:52:00 GMT"],"Cache-Control":["no-store, no-cache, must-revalidate"],"Pragma":["no-cache"],"Set-Cookie":["_csrf-backend=be2fc924068632fe77664d7f2970683932f211e49df7a9c2cde76de3ca989bb7a%3A2%3A%7Bi%3A0%3Bs%3A13%3A%22_csrf-backend%22%3Bi%3A1%3Bs%3A32%3A%22icuR4oQSY8UvGRM6ifjna_QHlEdNJkPL%22%3B%7D; path=/; HttpOnly"],"Vary":["Accept-Encoding"],"Content-Length":["4646"],"Content-Type":["text/html; charset=UTF-8"]}
 I see in current url "/login"
 FAIL 

  [TransactionForcer] no longer watching new connections
  [ConnectionWatcher] no longer watching new connections
  [ConnectionWatcher] closing all (0) connections
  Destroying application
  Suite done, restoring $_SERVER to original
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

Backend\tests.functional Tests (0) ---------------------------------------------------------------------------------------------------------------------------------------
Modules: Yii2
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  Suite done, restoring $_SERVER to original
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

Backend\tests.unit Tests (0) ---------------------------------------------------------------------------------------------------------------------------------------------
Modules: 
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------


Time: 371 ms, Memory: 14.00MB

There was 1 failure:

---------
1) AcfCest: Access to index not loginned user
 Test  tests/acceptance/AcfCest.php:accessToIndexNotLoginnedUser
 Step  See in current url "/login"
 Fail  Failed asserting that '/index-test.php?r=site%2Flogin' contains "/login".

Scenario Steps:

 2. $I->seeInCurrentUrl("/login") at tests/acceptance/AcfCest.php:19
 1. $I->amOnPage("index-test.php?r=") at tests/acceptance/AcfCest.php:18


FAILURES!
Tests: 1, Assertions: 1, Failures: 1.

Whot I'm wrong?
