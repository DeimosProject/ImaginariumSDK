<?php

namespace Test;

use Deimos\ImaginariumSDK\SDK;

class SDKTest extends \PHPUnit\Framework\TestCase
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
        $smallHash    = substr(md5(microtime()), 0, 3); // std hash str 3 chars

        $host = 'https://localhost/';

        $this->sdk->setBasedir($dirName);
        $this->sdk->setUserName($user);
        $this->sdk->setServer('localhost');

        $this->assertEquals(
            $host . $user . '/' . $hash . '/default.png',
            $this->sdk->getOriginalUrl($hash)
        );

        $this->assertEquals(
            $host . $user . '/myKey/' . $hash . '/isImage.gif',
            $this->sdk->getThumbsUrl('myKey', $hash, 'isImage.gif')
        );

        $this->assertEquals(
            $dirName . '/' . $user . '/thumbs/myKey/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash,
            $this->sdk->getThumbsPath('myKey', $hash)
        );

        $padSmallHash = '0' . $smallHash; // '0' - default pad str
        $this->assertEquals(
            $dirName . '/' . $user . '/thumbs/myKey/' . substr($padSmallHash, 0, 2) . '/' . substr($padSmallHash, 2, 2) . '/' . $smallHash,
            $this->sdk->getThumbsPath('myKey', $smallHash)
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
