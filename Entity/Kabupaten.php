<?php

namespace Ais\KabupatenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ais\KabupatenBundle\Model\KabupatenInterface;

/**
 * Kabupaten
 */
class Kabupaten implements KabupatenInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $provinsi_id;

    /**
     * @var string
     */
    private $nama;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var boolean
     */
    private $is_delete;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set provinsiId
     *
     * @param integer $provinsiId
     *
     * @return Kabupaten
     */
    public function setProvinsiId($provinsiId)
    {
        $this->provinsi_id = $provinsiId;

        return $this;
    }

    /**
     * Get provinsiId
     *
     * @return integer
     */
    public function getProvinsiId()
    {
        return $this->provinsi_id;
    }

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Kabupaten
     */
    public function setNama($nama)
    {
        $this->nama = $nama;

        return $this;
    }

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama()
    {
        return $this->nama;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Kabupaten
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Kabupaten
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}
