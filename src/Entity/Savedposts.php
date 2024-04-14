<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Savedposts
 *
 * @ORM\Table(name="savedposts", indexes={@ORM\Index(name="owner", columns={"owner"}), @ORM\Index(name="post", columns={"post"})})
 * @ORM\Entity
 */
class Savedposts
{
    /**
     * @var int
     *
     * @ORM\Column(name="idSavedPost", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idsavedpost;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner", referencedColumnName="idUser")
     * })
     */
    private $owner;

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
