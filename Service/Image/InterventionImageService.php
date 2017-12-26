<?php

namespace Dugun\ImageBundle\Service\Image;

use Dugun\ImageBundle\Contracts\DugunImageInterface;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class InterventionImageService implements DugunImageInterface
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    public function __construct(array $config = [])
    {
        $this->imageManager = new ImageManager($config);
    }

    public function openFile($filePath)
    {
        $file = $this->imageManager->make($filePath);

        return $file;
    }

    /**
     * @param Image $file
     * @param $x
     * @param $y
     * @param $width
     * @param $height
     *
     * @return Image
     */
    public function crop($file, $x, $y, $width, $height)
    {
        if ($file instanceof Image) {
            $file = $file->crop($width, $height, $x, $y);
        }

        return $file;
    }

    /**
     * @param $file
     * @param $resizeWidth
     * @param $resizeHeight
     *
     * @return Image
     */
    public function resize($file, $resizeWidth = null, $resizeHeight = null)
    {
        if ($file instanceof Image) {
            $resize_ratio = $resizeWidth / $resizeHeight;
            $oraginal_ratio = $file->getWidth() / $file->getHeight();
            $cropStartX = 0;
            $cropStartY = 0;
            if ($resize_ratio > $oraginal_ratio) {
                $file->resize($resizeWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $cropStartY = ($file->getHeight() - $resizeHeight) / 2;
            } else {
                $file->resize(null, $resizeHeight, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $cropStartX = ($file->getWidth() - $resizeWidth) / 2;
            }
            $file = $this->crop($file, (int) $cropStartX, (int) $cropStartY, $resizeWidth, $resizeHeight);
        }

        return $file;
    }

    /**
     * @param $file
     * @param $watermarkPath
     * @param $watermarkPostion
     *
     * @return Image
     */
    public function addWatermark($file, $watermarkPath, $watermarkPostion)
    {
        if ($file instanceof Image) {
            $file = $file->insert($watermarkPath, $watermarkPostion, 0, 10);
        }

        return $file;
    }

    /**
     * @param $file
     *
     * @return mixed
     */
    public function getWidth($file)
    {
        return $file->width();
    }

    /**
     * @param $file
     *
     * @return mixed
     */
    public function getHeight($file)
    {
        return $file->height();
    }

    /**
     * @param $file
     * @param $clockwiseDegree
     *
     * @return mixed
     */
    public function rotate($file, $clockwiseDegree)
    {
        // Intervention class rotate anti clockwise
        if ($file instanceof Image) {
            $file->rotate(-$clockwiseDegree);
        }

        return $file;
    }

    /**
     * @param $file
     * @param $savePath
     *
     * @return Image
     */
    public function save($file, $savePath)
    {
        if ($file instanceof Image) {
            $file = $file->save($savePath.$file->basename, 100);
        }

        return $file;
    }

    /**
     * @param $file
     *
     * @return string
     */
    public function getPath($file)
    {
        if ($file instanceof Image) {
            $file = $file->dirname.'/'.$file->basename;
        }

        return $file;
    }
}
