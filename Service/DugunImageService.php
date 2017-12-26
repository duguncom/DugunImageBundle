<?php

namespace Dugun\ImageBundle\Service;

use Dugun\ImageBundle\Service\Image\InterventionImageService;
use Intervention\Image\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DugunImageService
{
    /**
     * Parameters that carry watermark, temp folder details.
     * You can define them from your config.yml.
     *
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    protected $kernelRootDir;

    /**
     * @var InterventionImageService
     */
    protected $imageService;

    /**
     * ImageService constructor.
     *
     * @param $parameters
     * @param $kernelRootDir
     */
    public function __construct($parameters, $kernelRootDir)
    {
        $this->imageService = new InterventionImageService(['driver' => $parameters['driver']]);
        $this->parameters = $parameters;
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * Crops image by given parameters.
     *
     * @param Image $image
     * @param int   $x      start point of x axis
     * @param int   $y      start point of y axis
     * @param int   $width  new image's width
     * @param int   $height new image's height
     *
     * @return Image
     */
    public function crop($image, $x, $y, $width, $height)
    {
        if ($image instanceof Image) {
            $imageWidth = $this->getWidth($image);
            $imageHeight = $this->getHeight($image);

            /*
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
     * Returns width of given image.
     *
     * @param Image $image
     *
     * @return int
     */
    public function getWidth($image)
    {
        return $this->imageService->getWidth($image);
    }

    /**
     * Returns height of given image.
     *
     * @param Image $image
     *
     * @return int
     */
    public function getHeight($image)
    {
        return $this->imageService->getHeight($image);
    }

    /**
     * Resizes given image. It always follows ascept ratio.
     *
     * @param Image $image
     * @param int   $resizeWidth
     * @param int   $resizeHeight
     *
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
     * Adds watermark to an image if watermark file isset and exist.
     *
     * @param Image $image
     *
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
     * @return string|null
     */
    private function getWatermarkFile()
    {
        $image = dirname($this->kernelRootDir).'/'.$this->parameters['watermark_file'];
        if ($image && file_exists($image)) {
            return $image;
        }

        return;
    }

    /**
     * Opens given image. We decided to image can be instance of Invervention, UploadedFile.
     *  And also it can be string.
     *
     *  We are sending image path to image service and it loads from path.
     *
     *
     * @param mixed $image
     *
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
     *
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
     * Sets watermark image for instantly.
     *
     * @param string $filePath
     *
     * @return string
     */
    public function setWatermarkFile($filePath)
    {
        $this->parameters['watermark_file'] = $filePath;

        return $filePath;
    }

    /**
     * Sets watermark position for instance.
     *
     * @param string $position
     *
     * @return string
     */
    public function setWatermarkPosition($position)
    {
        $this->parameters['watermark_position'] = $position;

        return $position;
    }

    /**
     * Rotates image by given degree.
     *
     * @param Image $image
     * @param int   $clockwiseDegree
     *
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
     * @param Image $image
     * @param bool  $overwrite
     *
     * @return Image
     */
    public function save($image, $overwrite = false)
    {
        if ($image instanceof Image) {
            if (!$overwrite) {
                $tmp_dir = $this->parameters['temporary_folder'].'/'.time().'_';  //should come form config
            } else {
                $tmp_dir = $image->dirname.'/';
            }

            return $this->imageService->save($image, $tmp_dir);
        }

        return $image;
    }

    /**
     * @param Image $image
     *
     * @return string
     */
    public function getPath($image)
    {
        return $this->imageService->getPath($image);
    }
}
