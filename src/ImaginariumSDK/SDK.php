<?php

namespace Deimos\ImaginariumSDK;

class SDK
{

    /**
     * @var string
     */
    protected $basedir;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var int
     */
    protected $partSize = 2;

    /**
     * @var int
     */
    protected $partsCount = 2;

    /**
     * @var string
     */
    protected $hashPadStr = '0';

    /**
     * @param string $name
     */
    public function setUserName($name)
    {
        $this->user = $name;
    }

    /**
     * @param string $dir
     */
    public function setBasedir($dir)
    {
        $this->basedir = rtrim($dir, '/\\') . '/';
    }

    /**
     * @param string $domain
     * @param string $schema
     */
    public function setServer($domain, $schema = 'https')
    {
        $this->server = $schema . '://' . $domain . '/';
    }

    /**
     * @param string $hash
     * @param string $storageName
     * @param string $fileName
     * @param bool   $withServer
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function getImageUrl($hash, $storageName, $fileName = 'default.png', $withServer = true)
    {
        if ($withServer && !$this->server)
        {
            throw new \InvalidArgumentException('$server variable is empty');
        }

        if ($storageName)
        {
            $storageName .= '/';
        }

        return ($withServer ? $this->server : '') . $this->user . '/' . $storageName . $hash . '/' . $fileName;
    }

    /**
     * @param string $hash
     * @param string $fileName
     * @param bool   $withServer
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getOriginalUrl($hash, $fileName = 'default.png', $withServer = true)
    {
        return $this->getImageUrl($hash, '', $fileName, $withServer);
    }

    /**
     * @param string $name
     * @param string $hash
     * @param string $fileName
     * @param bool   $withServer
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getThumbsUrl($name, $hash, $fileName = 'default.png', $withServer = true)
    {
        return $this->getImageUrl($hash, $name, $fileName, $withServer);
    }

    protected function splitHash($hash)
    {
        $result = '';

        $minLenght = ($this->partsCount * $this->partSize);
        $padHash = $hash;

        if(strlen($hash) < $minLenght)
        {
            $padHash = str_pad($hash, $minLenght, $this->hashPadStr, STR_PAD_LEFT);
        }

        $i = 0;
        while($i < $this->partsCount)
        {
            $result .= substr($padHash , ($i * $this->partSize), $this->partSize) . '/';
            $i++;
        }

        return $result . $hash;
    }

    /**
     * @param string $hash
     * @param string $storageName
     * @param bool   $withBaseDir
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function getImagePath($hash, $storageName = null, $withBaseDir = true)
    {
        if(!$storageName)
        {
            $storageName = 'origin';
        }

        if ($withBaseDir && !$this->basedir)
        {
            throw new \InvalidArgumentException('$basedir variable is empty');
        }

        if (!$this->user)
        {
            throw new \InvalidArgumentException('$user variable is empty');
        }


        $hash = $this->splitHash($hash);

        $path = $this->user . '/' . $storageName . '/' . $hash;

        return ($withBaseDir ? $this->basedir : '' ) . $path;
    }

    /**
     * @param string $hash
     * @param bool   $withBaseDir
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getOriginalPath($hash, $withBaseDir = true)
    {
        return $this->getImagePath($hash, null, $withBaseDir);
    }

    /**
     * @param string $name
     * @param string $hash
     * @param bool   $withBaseDir
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getThumbsPath($name, $hash, $withBaseDir = true)
    {
        if($name)
        {
            $name = '/' . $name;
        }

        return $this->getImagePath($hash, 'thumbs' . $name, $withBaseDir);
    }

}
