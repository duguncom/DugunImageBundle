<?php

namespace Dugun\ImageBundle\Tests;

use Dugun\ImageBundle\Service\DugunImageService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ResizeTest extends \PHPUnit\Framework\TestCase
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

    public function test_resize()
    {
        $this->assertInstanceOf('\Dugun\ImageBundle\Service\DugunImageService', $this->service);

        $file = new UploadedFile(
            __DIR__.'/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );

        $image = $this->service->openFile($file);
        $this->service->resize($image, 300, 300);
        $this->assertEquals(300, $this->service->getWidth($image));
        $this->assertEquals(300, $this->service->getHeight($image));

        /*
         * test for aspet ratio
         */
        $image = $this->service->openFile($file);
        $this->service->resize($image, 1000, 300);
        $this->assertEquals(1000, $this->service->getWidth($image));
        $this->assertEquals(300, $this->service->getHeight($image));

        /*
         * test for aspet ratio
         */
        $image = $this->service->openFile($file);
        $this->service->resize($image, 300, 1000);
        $this->assertEquals(300, $this->service->getWidth($image));
        $this->assertEquals(1000, $this->service->getHeight($image));

        /*
         * dude, we cannot resize a string. what were you thinking????
         */
        $this->service->resize('adad', 100, 50);
    }
}
