<?php

namespace Test;

use Deimos\SDK\SDK;

class SDKTest extends \PHPUnit_Framework_TestCase
{
    public function testSDK()
    {
        $sdk = new SDK();

        $dirName = dirname(dirname(__DIR__)) . '/storage';
        $user = uniqid(mt_rand(), true);
        $hash = substr(md5(microtime()), 0, 6); // std hash str 6 chars

        $host = 'http://localhost/';

        $sdk->setBasedir($dirName);
        $sdk->setUserName($user);
        $sdk->setServer();

        $this->assertEquals(
            $host . $user . '/origin/' . $hash . '/image.png',
            $sdk->getImageUrl($hash)
        );

        $this->assertEquals(
            $sdk->getImageUrl($hash),
            $sdk->getOriginalUrl($hash)
        );

        $this->assertEquals(
            $host . $user . '/myKey/' . $hash . '/isImage.gif',
            $sdk->getImageUrl($hash, 'myKey', 'isImage.gif')
        );

        $this->assertEquals(
            $dirName . '/' . $user . '/myKey/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash,
            $sdk->getImagePath($hash, 'myKey')
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testServerException()
    {
        $sdk = new SDK();

        $sdk->getImageUrl('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBasedirException()
    {
        $sdk = new SDK();

        $sdk->getImagePath('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUserException()
    {
        $sdk = new SDK();

        $sdk->setBasedir(__DIR__);

        $sdk->getImagePath('');
    }
}
