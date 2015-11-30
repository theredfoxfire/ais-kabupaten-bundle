<?php

namespace Ais\KabupatenBundle\Handler;

use Ais\KabupatenBundle\Model\KabupatenInterface;

interface KabupatenHandlerInterface
{
    /**
     * Get a Kabupaten given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return KabupatenInterface
     */
    public function get($id);

    /**
     * Get a list of Kabupatens.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Kabupaten, creates a new Kabupaten.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return KabupatenInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Kabupaten.
     *
     * @api
     *
     * @param KabupatenInterface   $kabupaten
     * @param array           $parameters
     *
     * @return KabupatenInterface
     */
    public function put(KabupatenInterface $kabupaten, array $parameters);

    /**
     * Partially update a Kabupaten.
     *
     * @api
     *
     * @param KabupatenInterface   $kabupaten
     * @param array           $parameters
     *
     * @return KabupatenInterface
     */
    public function patch(KabupatenInterface $kabupaten, array $parameters);
}
