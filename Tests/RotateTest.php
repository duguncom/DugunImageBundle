<?php

namespace Dugun\ImageBundle\Tests;


use Imagine\Image\Box;
use Imagine\Image\Point;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Rotate extends WebTestCase
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
        $width = $image->getWidth();
        $height = $image->getHeight();
        $service->rotate($image, 90);
        $this->assertEquals($width, $image->getHeight());
        $this->assertEquals($height, $image->getWidth());
        $service->rotate($image, 90);
        $this->assertEquals($width, $image->getWidth());
        $this->assertEquals($height, $image->getHeight());

        /**
         * Sorry dude, we cannot rotate a string.
         */
        $service->rotate('asdasd', 180);
    }
}
