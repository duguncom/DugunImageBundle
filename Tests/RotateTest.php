<?php

namespace Dugun\ImageBundle\Tests;

use Dugun\ImageBundle\Service\DugunImageService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Rotate extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var DugunImageService
     */
    private $service;

    public function setUp()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();
        $this->service = $this->container->get('dugun_image.service.image_service');
    }

    public function test_watermark()
    {
        $this->assertInstanceOf('\Dugun\ImageBundle\Service\DugunImageService', $this->service);

        $file = new UploadedFile(
            __DIR__.'/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );

        $image = $this->service->openFile($file);
        $width = $image->getWidth();
        $height = $image->getHeight();
        $this->service->rotate($image, 90);
        $this->assertEquals($width, $image->getHeight());
        $this->assertEquals($height, $image->getWidth());
        $this->service->rotate($image, 90);
        $this->assertEquals($width, $image->getWidth());
        $this->assertEquals($height, $image->getHeight());

        /*
         * Sorry dude, we cannot rotate a string.
         */
        $this->service->rotate('asdasd', 180);
    }
}
