<?php

namespace Dugun\ImageBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CropTest extends WebTestCase
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


    public function test_crop()
    {

        $service = $this->container->get('dugun_image.service.image_service');

        $file = new UploadedFile(
            __DIR__ . '/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );

        $image = $service->openFile($file);
        $service->crop($image, 0, 0, 540, 310);
        $this->assertEquals(540, $service->getWidth($image));
        $this->assertEquals(310, $service->getHeight($image));

        $image = $service->openFile($file);
        $croppedImage = $service->crop($image, 0, 0, 1366, 768);
        $this->assertEquals(1366, $service->getWidth($croppedImage));
        $this->assertEquals(768, $service->getHeight($croppedImage));

        /**
         * We are sending crop as greater than image's width-height.
         * So it will return as original image.
         * Maybe it can throw exception?
         */
        $image = $service->openFile($file);
        $croppedImage = $service->crop($image, 0, 0, 2000, 2000);
        $this->assertEquals(1366, $service->getWidth($croppedImage));
        $this->assertEquals(768, $service->getHeight($croppedImage));


        /**
         * We cannot crop :(
         */
        $service->crop('asdasd', 0, 0, 100, 100);
    }
}
