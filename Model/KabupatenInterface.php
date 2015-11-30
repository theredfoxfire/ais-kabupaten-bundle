<?php

namespace Ais\KabupatenBundle\Model;

Interface KabupatenInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set provinsiId
     *
     * @param integer $provinsiId
     *
     * @return Kabupaten
     */
    public function setProvinsiId($provinsiId);

    /**
     * Get provinsiId
     *
     * @return integer
     */
    public function getProvinsiId();

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Kabupaten
     */
    public function setNama($nama);

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Kabupaten
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive();

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Kabupaten
     */
    public function setIsDelete($isDelete);

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
