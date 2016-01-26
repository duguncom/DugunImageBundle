<?php

namespace Dugun\ImageBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Rotate extends WebTestCase
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    public function test_watermark()
    {
        $service = $this->container->get('dugun_image.service.image_service');

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
