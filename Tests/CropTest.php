<?php

namespace Dugun\ImageBundle\Tests;

use Dugun\ImageBundle\Service\DugunImageService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CropTest extends \PHPUnit_Framework_TestCase
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

    public function test_crop()
    {
        $this->assertInstanceOf('\Dugun\ImageBundle\Service\DugunImageService', $this->service);

        $file = new UploadedFile(
            __DIR__.'/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );

        $image = $this->service->openFile($file);
        $this->service->crop($image, 0, 0, 540, 310);
        $this->assertEquals(540, $this->service->getWidth($image));
        $this->assertEquals(310, $this->service->getHeight($image));

        $image = $this->service->openFile($file);
        $croppedImage = $this->service->crop($image, 0, 0, 1366, 768);
        $this->assertEquals(1366, $this->service->getWidth($croppedImage));
        $this->assertEquals(768, $this->service->getHeight($croppedImage));

        /**
         * We are sending crop as greater than image's width-height.
         * So it will return as original image.
         * Maybe it can throw exception?
         */
        $image = $this->service->openFile($file);
        $croppedImage = $this->service->crop($image, 0, 0, 2000, 2000);
        $this->assertEquals(1366, $this->service->getWidth($croppedImage));
        $this->assertEquals(768, $this->service->getHeight($croppedImage));

        /*
         * We cannot crop :(
         */
        $this->service->crop('asdasd', 0, 0, 100, 100);
    }
}
