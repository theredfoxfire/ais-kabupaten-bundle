<?php

namespace Ais\KabupatenBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\KabupatenBundle\Model\KabupatenInterface;
use Ais\KabupatenBundle\Form\KabupatenType;
use Ais\KabupatenBundle\Exception\InvalidFormException;

class KabupatenHandler implements KabupatenHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Kabupaten.
     *
     * @param mixed $id
     *
     * @return KabupatenInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Kabupatens.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Kabupaten.
     *
     * @param array $parameters
     *
     * @return KabupatenInterface
     */
    public function post(array $parameters)
    {
        $kabupaten = $this->createKabupaten();

        return $this->processForm($kabupaten, $parameters, 'POST');
    }

    /**
     * Edit a Kabupaten.
     *
     * @param KabupatenInterface $kabupaten
     * @param array         $parameters
     *
     * @return KabupatenInterface
     */
    public function put(KabupatenInterface $kabupaten, array $parameters)
    {
        return $this->processForm($kabupaten, $parameters, 'PUT');
    }

    /**
     * Partially update a Kabupaten.
     *
     * @param KabupatenInterface $kabupaten
     * @param array         $parameters
     *
     * @return KabupatenInterface
     */
    public function patch(KabupatenInterface $kabupaten, array $parameters)
    {
        return $this->processForm($kabupaten, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param KabupatenInterface $kabupaten
     * @param array         $parameters
     * @param String        $method
     *
     * @return KabupatenInterface
     *
     * @throws \Ais\KabupatenBundle\Exception\InvalidFormException
     */
    private function processForm(KabupatenInterface $kabupaten, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new KabupatenType(), $kabupaten, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $kabupaten = $form->getData();
            $this->om->persist($kabupaten);
            $this->om->flush($kabupaten);

            return $kabupaten;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createKabupaten()
    {
        return new $this->entityClass();
    }

}
