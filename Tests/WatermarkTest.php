<?php

namespace Dugun\ImageBundle\Tests;

use Dugun\ImageBundle\Service\DugunImageService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WatermarkTest extends \PHPUnit_Framework_TestCase
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

        $this->service->setWatermarkPosition('');
        $this->service->addWatermark($image);

        /*
         * We cannot add watermark to a string yay!
         */
        $this->service->addWatermark('asdasd');

        $this->service->setWatermarkFile('this-file-is-not-exist.jpg');
        $this->service->addWatermark($image);
    }
}
