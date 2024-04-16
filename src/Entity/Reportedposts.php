<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reportedposts
 *
 * @ORM\Table(name="reportedposts", indexes={@ORM\Index(name="idPost", columns={"idPost"})})
 * @ORM\Entity
 */
class Reportedposts
{
    /**
     * @var int
     *
     * @ORM\Column(name="idReport", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreport;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255, nullable=false)
     */
    private $reason;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrReports", type="integer", nullable=false, options={"default"="1"})
     */
    private $nbrreports = 1;

    /**
     * @var \Posts
     *
     * @ORM\ManyToOne(targetEntity="Posts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPost", referencedColumnName="idPost")
     * })
     */
    private $idpost;


}
