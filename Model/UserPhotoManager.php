<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserPhotoManager implements UserPhotoManagerInterface
{
    /** @var string */
    private $baseUrl;

    /** @var string */
    private $basePath;

    /**
     * UserPhotoManager constructor.
     *
     * @param string $baseUrl
     * @param string $basePath
     */
    public function __construct(string $baseUrl, string $basePath)
    {
        $this->baseUrl = $baseUrl;
        $this->basePath = $basePath;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return string
     */
    public function uploadPhoto(UploadedFile $file): string
    {
        $name = md5(uniqid('', true)) . '.' . $file->guessExtension();

        $file->move($this->basePath, $name);

        return sprintf('%s/%s', rtrim($this->baseUrl, '/'), $name);
    }
}
