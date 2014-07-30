<?php

namespace VDMExtractor\ExtractorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use \DateTime;

/**
 * This class represents a post
 *
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class Post
{
	/**
	 * @var int $id Identifier
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @var string $author Author
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $author;

	/**
	 * @var DateTime $date Date
	 * @ORM\Column(type="datetime")
	 */
	protected $date;

	/**
	 * @var string $content
	 * @ORM\Column(type="string")
	 */
	protected $content;

	public function __construct($id, $author, DateTime $date, $content)
	{
		$this->id      = $id;
		$this->author  = $author;
		$this->date    = $date;
		$this->content = $content;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor($author)
	{
		$this->author = $author;
		return $this;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setDate(DateTime $date)
	{
		$this->date = $date;
		return $this;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	public function toArray()
	{
		return [
			'id'      => $this->id,
			'content' => utf8_encode($this->content),
			'date'    => $this->date->format('Y-m-d H:m:s'),
			'author'  => $this->author,
		]; 
	}
}

