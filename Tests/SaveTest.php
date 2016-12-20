<?php

namespace Dugun\ImageBundle\Tests;

use Dugun\ImageBundle\Service\DugunImageService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SaveTest extends \PHPUnit_Framework_TestCase
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

    public function test_open()
    {
        $this->assertInstanceOf('\Dugun\ImageBundle\Service\DugunImageService', $this->service);

        $file = new UploadedFile(
            __DIR__.'/../Resources/assets/test/file1.jpg',
            'file1.jpg',
            'image/jpeg'
        );

        $image = $this->service->openFile($file);
        $originalPath = $this->service->getPath($image);

        $this->service->save($image);
        $temporaryPath = $this->service->getPath($image);
        $this->assertNotEquals($originalPath, $temporaryPath);

        $this->service->save($image, true);
        $this->assertEquals($temporaryPath, $this->service->getPath($image));

        unlink($this->service->getPath($image));
        /*
         * check image is deleted
         */
        $this->assertFalse(file_exists($this->service->getPath($image)));

        /*
         * you must send an image instance!!11111birbirir
         */
        $this->service->save('asdasd');
    }
}
