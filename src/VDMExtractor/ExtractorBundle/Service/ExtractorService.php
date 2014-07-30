<?php

namespace VDMExtractor\ExtractorBundle\Service;


use Doctrine\ORM\EntityManager;

use VDMExtractor\ExtractorBundle\Entity\Post;

use \DateTime;

/**
 * This class represents the module's service
 * To extract the vdm's posts 
 */
class ExtractorService 
{
	/** Acces to other services*/
	protected $serviceLocator;	
	
	/** Url of the website */
	protected $VDMUrl = 'http://www.viedemerde.fr/?page=';
	
	/** EntityManager Doctrine entity manager */
	protected $entityManager;


    public function __construct()
    {
        $this->entityManager  = null;
    }


	
	
}
