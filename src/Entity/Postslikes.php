<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Postslikes
 *
 * @ORM\Table(name="postslikes", indexes={@ORM\Index(name="idPost", columns={"post"}), @ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Postslikes
{
    /**
     * @var int
     *
     * @ORM\Column(name="idLike", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlike;

    /**
     * @var int
     *
     * @ORM\Column(name="action", type="integer", nullable=false)
     */
    private $action = '0';

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="idUser")
     * })
     */
    private $iduser;

    /**
     * @var \Posts
     *
     * @ORM\ManyToOne(targetEntity="Posts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post", referencedColumnName="idPost")
     * })
     */
    private $post;


}
