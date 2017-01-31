<?php

namespace Deimos\SDK;

class SDK
{

    private $basedir;
    private $user;
    private $server;

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
     * @param string $server
     * @param string $schema
     */
    public function setServer($server = 'localhost', $schema = 'http')
    {
        $this->server = $schema . '://' . $server . '/';
    }

    /**
     * @param string $hash
     * @param string $key
     * @param string $fileName
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getImageUrl($hash, $key = 'origin', $fileName = 'image.png')
    {
        if(!$this->server)
        {
            throw new \InvalidArgumentException('$server variable is empty');
        }

        return $this->server . $this->user . '/' . $key . '/' . $hash . '/' . $fileName;
    }

    /**
     * @param string $hash
     * @param string $fileName
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getOriginalUrl($hash, $fileName = 'image.png')
    {
        return $this->getImageUrl($hash, 'origin', $fileName);
    }

    /**
     * @param string $hash
     * @param string $key
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getImagePath($hash, $key = 'origin')
    {
        if(!$this->basedir)
        {
            throw new \InvalidArgumentException('$basedir variable is empty');
        }

        if(!$this->user)
        {
            throw new \InvalidArgumentException('$user variable is empty');
        }


        $hash = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash;
        return $this->basedir . $this->user . '/' . $key . '/' . $hash;
    }

}
