<?php
namespace Dugun\ImageBundle\Service;

use Dugun\ImageBundle\Service\Image\InterventionImageService;
use Intervention\Image\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DugunImageService
{

    private $parameters;

    /**
     * ImageService constructor.
     * @param $parameters
     */
    public function __construct($parameters)
    {
        $this->imageService = new InterventionImageService();
        $this->parameters = $parameters;
    }

    /**
     * Crops image by given parameters
     *
     * @param Image $image
     * @param $x start point of x axis
     * @param $y start point of y axis
     * @param $width new image's width
     * @param $height new image's height
     * @return Image
     */
    public function crop($image, $x, $y, $width, $height)
    {
        if ($image instanceof Image) {
            $imageWidth = $this->getWidth($image);
            $imageHeight = $this->getHeight($image);

            /**
             * If width > fileWidth, there is a serious problem.
             * Are you tring to crop outside of image??
             */
            if ($width > $imageWidth or $height > $imageHeight) {
                return $image;
            }

            if (($width > 0 and $height > 0)) {
                $image = $this->imageService->crop($image, $x, $y, $width, $height);
            }
        }


        return $image;
    }

    /**
     * Returns width of given image
     *
     * @param Image $image
     * @return mixed
     */
    public function getWidth($image)
    {
        return $this->imageService->getWidth($image);
    }

    /**
     * Returns height of given image
     *
     * @param Image $image
     * @return mixed
     */
    public function getHeight($image)
    {
        return $this->imageService->getHeight($image);
    }

    /**
     * Resizes given image. It always follows ascept ratio
     *
     * @param Image $image
     * @param $resizeWidth
     * @param $resizeHeight
     * @return Image
     */
    public function resize($image, $resizeWidth = null, $resizeHeight = null)
    {
        if ($image instanceof Image) {
            return $this->imageService->resize($image, $resizeWidth, $resizeHeight);
        }

        return $image;
    }

    /**
     * Adds watermark to an image if watermark file isset and exist
     *
     * @param Image $image
     * @return Image
     */
    public function addWatermark($image)
    {
        if ($image instanceof Image) {
            $watermarkFile = $this->getWatermarkFile();
            if ($watermarkFile !== null) {
                $watermark = $this->openFile($watermarkFile);
                $watermarkPosition = $this->getWatermarkPosition();
                return $this->imageService->addWatermark($image, $watermark, $watermarkPosition);
            }
            return $image;
        }
        return $image;
    }

    /**
     * Gets watermark image from config and check if it is exist.
     *
     * @return string
     */
    private function getWatermarkFile()
    {
        $image = $this->parameters['watermark_file'];
        if ($image && file_exists($image)) {
            return $image;
        }

        return null;
    }

    /**
     * Opens given image. We decided to image can be instance of Invervention, UploadedFile.
     *  And also it can be string.
     *
     *  We are sending image path to image service and it loads from path.
     *
     *
     * @param $image
     * @return Image
     */
    public function openFile($image)
    {
        if ($image instanceof Image) {
            $imagePath = $this->imageService->getPath($image);
        } elseif ($image instanceof UploadedFile) {
            $imagePath = $image->getRealPath();
        } elseif (is_string($image)) {
            $imagePath = $image;
        }

        if (isset($imagePath)) {
            $image = $this->imageService->openFile($imagePath);
        }

        return $image;
    }

    /**
     * Gets watermark position from config.
     * @return string
     */
    private function getWatermarkPosition()
    {
        $watermarkPosition = $this->parameters['watermark_position'];
        if (!$watermarkPosition) {
            $watermarkPosition = 'bottom';
        }
        return $watermarkPosition;
    }

    /**
     * Sets watermark image for instantly
     *
     * @param $filePath
     * @return string
     */
    public function setWatermarkFile($filePath)
    {
        $this->parameters['watermark_file'] = $filePath;
        return $filePath;
    }

    /**
     * Sets watermark position for instance
     * @param $position
     * @return string
     */
    public function setWatermarkPosition($position)
    {
        $this->parameters['watermark_position'] = $position;
        return $position;
    }

    /**
     * Rotates image by given degree
     *
     * @param Image $image
     * @param $clockwiseDegree
     * @return Image
     */
    public function rotate($image, $clockwiseDegree)
    {
        if ($image instanceof Image) {
            return $this->imageService->rotate($image, $clockwiseDegree);
        }

        return $image;
    }

    /**
     *
     *
     * @param Image $image
     * @param bool $overwrite
     * @return Image
     */
    public function save($image, $overwrite = false)
    {
        if ($image instanceof Image) {
            if (!$overwrite) {
                $tmp_dir = $this->parameters['temporary_folder'] . '/' . time() . '_';  //should come form config
            } else {
                $tmp_dir = $image->dirname . '/';
            }
            return $this->imageService->save($image, $tmp_dir);
        }
        return $image;
    }

    /**
     * @param Image $image
     * @return string
     */
    public function getPath($image)
    {
        return $this->imageService->getPath($image);
    }

}