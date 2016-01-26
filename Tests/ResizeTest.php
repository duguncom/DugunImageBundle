<?php

namespace Dugun\ImageBundle\Tests;


use Imagine\Image\Box;
use Imagine\Image\Point;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResizeTest extends WebTestCase
{
    public function test_resize()
    {
        $container = $this->getContainer();
        $service = $container->get('dugun_image.service.image_service');

        $file = new UploadedFile(
            __DIR__ . '/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );

        $image = $service->openFile($file);
        $service->resize($image, 300, 300);
        $this->assertEquals(300, $service->getWidth($image));
        $this->assertEquals(300, $service->getHeight($image));

        /*
         * test for aspet ratio
         */
        $image = $service->openFile($file);
        $service->resize($image, 1000, 300);
        $this->assertEquals(1000, $service->getWidth($image));
        $this->assertEquals(300, $service->getHeight($image));

        /*
         * test for aspet ratio
         */
        $image = $service->openFile($file);
        $service->resize($image, 300, 1000);
        $this->assertEquals(300, $service->getWidth($image));
        $this->assertEquals(1000, $service->getHeight($image));

        /**
         * dude, we cannot resize a string. what were you thinking????
         */
        $service->resize('adad', 100, 50);
    }
}
