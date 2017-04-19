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
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function getImageUrl($hash, $storageName, $fileName = 'default.png')
    {
        if (!$this->server)
        {
            throw new \InvalidArgumentException('$server variable is empty');
        }

        if ($storageName)
        {
            $storageName .= '/';
        }

        return $this->server . $this->user . '/' . $storageName . $hash . '/' . $fileName;
    }

    /**
     * @param string $hash
     * @param string $fileName
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getOriginalUrl($hash, $fileName = 'default.png')
    {
        return $this->getImageUrl($hash, '', $fileName);
    }

    /**
     * @param string $name
     * @param string $hash
     * @param string $fileName
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getThumbsUrl($name, $hash, $fileName = 'default.png')
    {
        return $this->getImageUrl($hash, $name, $fileName);
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

        if(strlen($hash) < 4)
        {
            $hash = str_pad($hash, 4, 0, STR_PAD_LEFT);
        }

        $hash = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash;

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

        return $this->getImagePath($hash, 'thumbs' . $name);
    }

}
