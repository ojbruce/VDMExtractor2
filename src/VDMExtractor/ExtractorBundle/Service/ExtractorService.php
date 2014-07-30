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
	
	/** Url of the website */
	protected $VDMUrl = 'http://www.viedemerde.fr/?page=';
	
	/** EntityManager Doctrine entity manager */
	protected $entityManager;


	/**
	 * Default Constructor
	 *
	 */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
    	$this->entityManager = $em;
    }

	/**
	 * Collect $limit last posts from VDM website
	 *
	 * @return void
	 */
	public function extract($limit)
	{
		$page  = 0;
		$posts = [];
		
		while (count($posts) < $limit) {

			$html = new \DOMDocument();
			@$html->loadHTMLFile($this->VDMUrl . '' . $page . '');

			$nodesPosts = $this->getDomNodePosts($html);

			foreach ($nodesPosts as $nodePost) {

				if (count($posts) >= $limit) {
					continue;
				}

				$nodePostText = $nodePost->nodeValue;

				// Extract data from node value
				$id      = $nodePost->attributes->getNamedItem('id')->textContent;
				$author  = $this->extractAuthor($nodePostText);
				$date    = $this->extractDate($nodePostText);
				$time    = $this->extractTime($nodePostText);
				$content = $this->extractContent($nodePostText);

				// Format date & time to a valid DateTime string
				$dateTime = str_replace('/','-', $date) . ' ' . $time;
				$dateTime = new DateTime($dateTime);

				$post    = new Post($id, $author, $dateTime, $content);
				$posts[] = $post;
			}

			$page++;
		}


		foreach ($posts as $post) {
			// Persist to database
			if(!$this->entityManager->find('VDMExtractor\ExtractorBundle\Entity\Post', $post->getId())){
				$this->entityManager->persist($post);
			}

		}
		$this->entityManager->flush();
	}

	/**
	 * Extract the post's author from the given string
	 *
	 * @return string Post's author
	 */
	public function extractAuthor($nodePostText)
	{
		$patternAuthor = "- par ([a-zA-Z0-9\']+([a-zA-Z\_0-9\.-\']*)) (\(homme\)|\(femme\))?";
		ereg($patternAuthor, $nodePostText, $matches); 
		return $matches[1];
	}

	/**
	 * Extract the post's date from the given string
	 *
	 * @return string Post's date
	 */
	public function extractDate($nodePostText)
	{
		$patternDate = "([0-9-]{2}/[0-9-]{2}/[0-9-]{4})";
		ereg($patternDate, $nodePostText, $matches); 
		return $matches[1];
	}

	/**
	 * Extract the post's time from the given string
	 *
	 * @return string Post's time
	 */
	public function extractTime($nodePostText)
	{
		$patternTime = "([0-9-]{2}:[0-9-]{2}) -";
		ereg($patternTime, $nodePostText, $matches); 
		return $matches[1];
	}

	/**
	 * Extract the post's content from the given string
	 *
	 * @return string Post's content
	 */
	public function extractContent($nodePostText)
	{
		$matches = split(' VDM', $nodePostText);
		return $matches[0];
	}

	/**
	 * Get posts' DOM Node from the given DOM Document
	 *
	 * @param \DOMDocument $domDocument DOM Document
	 *
	 * @return array containing DOM Node posts
	 */
	public function getDomNodePosts(\DOMDocument $domDocument)
	{
		$elements = $domDocument->getElementsByTagName("div");
		$posts    = [];

		foreach ($elements as $node) {
			if (!$node->hasAttributes()) {
				continue;
			}

			$classAttribute = $node->attributes->getNamedItem('class');

			if (!$classAttribute) {
				continue;
			}

			$class = $classAttribute->nodeValue;

			if ('post article' == $class) {
				$posts[] = $node;
			}
		}

		return $posts;
	}
	
	/**
	 * Setter for serviceLocator attribut
	 * @param $service the service locator
	 */
	public function setServiceLocator(ServiceLocatorInterface $service)
	{
		$this->serviceLocator = $service;
		$this->entityManager  = $service->get('doctrine.entitymanager.orm_default');
		return $this;
	}
	
	/**
	 * Getter of serviceLocator attribut
	 * @return $service the service locator
	 */
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}

	
}
