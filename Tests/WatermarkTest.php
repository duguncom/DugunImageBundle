<?php

namespace Dugun\ImageBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WatermarkTest extends WebTestCase
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
