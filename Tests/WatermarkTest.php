<?php

namespace Dugun\ImageBundle\Tests;


use Imagine\Image\Box;
use Imagine\Image\Point;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WatermarkTest extends WebTestCase
{
    public function test_watermark()
    {
        $container = $this->getContainer();
        $service = $container->get('dugun_image.service.image_service');

        $file = new UploadedFile(
            __DIR__ . '/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );
        $image = $service->openFile($file);

        $service->setWatermarkPosition('');
        $service->addWatermark($image);

        /**
         * We cannot add watermark to a string yay!
         */
        $service->addWatermark('asdasd');

        $service->setWatermarkFile('this-file-is-not-exist.jpg');
        $service->addWatermark($image);
    }
}
