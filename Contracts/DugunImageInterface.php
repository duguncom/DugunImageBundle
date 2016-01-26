<?php

namespace Dugun\ImageBundle\Contracts;

interface DugunImageInterface
{

    public function openFile($filePath);

    public function crop($file, $x, $y, $width, $height);
    public function resize($file, $resizeWidth = null, $resizeHeight = null);
    public function addWatermark($file, $watermark, $watermarkPostion);
    public function rotate($file, $clockwiseDegree);
    public function save($file, $savePath);
    public function getPath($file);

    public function getWidth($file);
    public function getHeight($file);
}