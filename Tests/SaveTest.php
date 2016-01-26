<?php

namespace Dugun\ImageBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SaveTest extends WebTestCase
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

    public function test_open()
    {

        $service = $this->container->get('dugun_image.service.image_service');

        $file = new UploadedFile(
            __DIR__ . '/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );

        $image = $service->openFile($file);
        $originalPath = $service->getPath($image);


        $service->save($image);
        $temporaryPath = $service->getPath($image);
        $this->assertNotEquals($originalPath, $temporaryPath);

        $service->save($image, true);
        $this->assertEquals($temporaryPath, $service->getPath($image));

        unlink($service->getPath($image));
        /**
         * check image is deleted
         */
        $this->assertFalse(file_exists($service->getPath($image)));

        /**
         * you must send an image instance!!11111birbirir
         */
        $service->save('asdasd');


    }
}
