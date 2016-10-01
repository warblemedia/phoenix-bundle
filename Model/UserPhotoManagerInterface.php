<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UserPhotoManagerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return string
     */
    public function uploadPhoto(UploadedFile $file): string;
}
