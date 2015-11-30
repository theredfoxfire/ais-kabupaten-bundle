<?php

namespace Ais\KabupatenBundle\Tests\Handler;

use Ais\KabupatenBundle\Handler\KabupatenHandler;
use Ais\KabupatenBundle\Model\KabupatenInterface;
use Ais\KabupatenBundle\Entity\Kabupaten;

class KabupatenHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\KabupatenBundle\Tests\Handler\DummyKabupaten';

    /** @var KabupatenHandler */
    protected $kabupatenHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DOSEN_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $kabupaten = $this->getKabupaten();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($kabupaten));

        $this->kabupatenHandler = $this->createKabupatenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->kabupatenHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $kabupatens = $this->getKabupatens(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($kabupatens));

        $this->kabupatenHandler = $this->createKabupatenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->kabupatenHandler->all($limit, $offset);

        $this->assertEquals($kabupatens, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $kabupaten = $this->getKabupaten();
        $kabupaten->setTitle($title);
        $kabupaten->setBody($body);

        $form = $this->getMock('Ais\KabupatenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($kabupaten));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->kabupatenHandler = $this->createKabupatenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $kabupatenObject = $this->kabupatenHandler->post($parameters);

        $this->assertEquals($kabupatenObject, $kabupaten);
    }

    /**
     * @expectedException Ais\KabupatenBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $kabupaten = $this->getKabupaten();
        $kabupaten->setTitle($title);
        $kabupaten->setBody($body);

        $form = $this->getMock('Ais\KabupatenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->kabupatenHandler = $this->createKabupatenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->kabupatenHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $kabupaten = $this->getKabupaten();
        $kabupaten->setTitle($title);
        $kabupaten->setBody($body);

        $form = $this->getMock('Ais\KabupatenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($kabupaten));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->kabupatenHandler = $this->createKabupatenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $kabupatenObject = $this->kabupatenHandler->put($kabupaten, $parameters);

        $this->assertEquals($kabupatenObject, $kabupaten);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $kabupaten = $this->getKabupaten();
        $kabupaten->setTitle($title);
        $kabupaten->setBody($body);

        $form = $this->getMock('Ais\KabupatenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($kabupaten));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->kabupatenHandler = $this->createKabupatenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $kabupatenObject = $this->kabupatenHandler->patch($kabupaten, $parameters);

        $this->assertEquals($kabupatenObject, $kabupaten);
    }


    protected function createKabupatenHandler($objectManager, $kabupatenClass, $formFactory)
    {
        return new KabupatenHandler($objectManager, $kabupatenClass, $formFactory);
    }

    protected function getKabupaten()
    {
        $kabupatenClass = static::DOSEN_CLASS;

        return new $kabupatenClass();
    }

    protected function getKabupatens($maxKabupatens = 5)
    {
        $kabupatens = array();
        for($i = 0; $i < $maxKabupatens; $i++) {
            $kabupatens[] = $this->getKabupaten();
        }

        return $kabupatens;
    }
}

class DummyKabupaten extends Kabupaten
{
}
