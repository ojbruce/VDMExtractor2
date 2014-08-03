<?php

namespace VDMExtractor\ExtractorBundle\Service;


use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use \DateTime;

use VDMExtractor\ExtractorBundle\Entity\Post;



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

	/** Instance of logger */
 	protected $logger;

	/**
	 * Default Constructor
	 *
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em, Logger $logger)
	{
		$this->entityManager = $em;
		$this->logger = $logger;
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
			// TODO ojbruce 08/2014 fix loadhtmlfile encoding
			@$html->loadHTMLFile($this->VDMUrl . '' . $page . '');

			$nodesPosts = $this->getDomNodePosts($html);
			

			foreach ($nodesPosts as $nodePost) {

				$this
					->logger
					->info('VDMExtractor\ExtractorBundle\Service:extract nodesContent : ' 
						.$nodePost->textContent);

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
		$patternAuthor = '/(- par) ([\w\-\'\. ]*) (\(homme\)|\(femme\))?/';

		$author = 'error match';
		if(preg_match($patternAuthor, $nodePostText, $matches)){
			$author = $matches[2];			
		}

		return $author;
	}

	/**
	 * Extract the post's date from the given string
	 *
	 * @return string Post's date
	 */
	public function extractDate($nodePostText)
	{
		$patternDate = "/([0-9]{2}\/[0-9]{2}\/[0-9]{4})/";
		
		$date = '00/00/0000';
		if(preg_match($patternDate, $nodePostText, $matches)){
			$date = $matches[1];
		} 
		
		return $date;
	}

	/**
	 * Extract the post's time from the given string
	 *
	 * @return string Post's time
	 */
	public function extractTime($nodePostText)
	{
		$patternTime = "/([0-9-]{2}:[0-9-]{2}) \-/";

		$time = '00:00';
		if(preg_match($patternTime, $nodePostText, $matches)){
			$time = $matches[1];
		} 
		
		return $time;
	}

	/**
	 * Extract the post's content from the given string
	 *
	 * @return string Post's content
	 */
	public function extractContent($nodePostText)
	{
		$matches = preg_split('/ VDM/', $nodePostText);
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


}
