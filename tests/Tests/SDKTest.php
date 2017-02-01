<?php

namespace Test;

use Deimos\ImaginariumSDK\SDK;

class SDKTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SDK
     */
    protected $sdk;

    protected function setUp()
    {
        parent::setUp();

        $this->sdk = new SDK();
    }

    public function testSDK()
    {

        $dirName = dirname(dirname(__DIR__)) . '/storage';
        $user    = substr(uniqid(mt_rand(), true), 0, mt_rand(2, 5));
        $hash    = substr(md5(microtime()), 0, 6); // std hash str 6 chars

        $host = 'https://localhost/';

        $this->sdk->setBasedir($dirName);
        $this->sdk->setUserName($user);
        $this->sdk->setServer('localhost');

        $this->assertEquals(
            $host . $user . '/origin/' . $hash . '/default.png',
            $this->sdk->getOriginalUrl($hash)
        );

        $this->assertEquals(
            $host . $user . '/thumbs/myKey/' . $hash . '/isImage.gif',
            $this->sdk->getThumbsUrl('myKey', $hash, 'isImage.gif')
        );

        $this->assertEquals(
            $dirName . '/' . $user . '/thumbs/myKey/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash,
            $this->sdk->getThumbsPath('myKey', $hash)
        );

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testServerException()
    {
        $this->sdk->getOriginalUrl('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBasedirException()
    {
        $this->sdk->getOriginalPath('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUserException()
    {
        $this->sdk->setBasedir(__DIR__);

        $this->sdk->getOriginalPath('');
    }
}
