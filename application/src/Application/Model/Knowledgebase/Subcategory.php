<?php

/*
 * This file is part of Xerxes.
 *
 * (c) California State University <library@calstate.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Model\Knowledgebase;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Subcategory
 *
 * @author David Walker <dwalker@calstate.edu>
 * 
 * @Entity @Table(name="subcategories")
 */

class Subcategory
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $name;
	
	/**
	 * @Column(type="integer")
	 * @var int
	 */
	protected $sequence;
	
	/**
	 * @ManyToOne(targetEntity="Category", inversedBy="subcategories")
	 * @JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
	 * @var Category
	 */
	protected $category;
	
	/**
	 * @OneToMany(targetEntity="Database", mappedBy="subcategory", cascade={"persist"})
	 * @var Database[]
	 */	
	protected $databases;
	
	/**
	 * Create new Subcategory
	 */
	
	public function __construct()
	{
		$this->databases = new ArrayCollection();
	}
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getSequence()
	{
		return $this->sequence;
	}

	/**
	 * @param int $sequence
	 */
	public function setSequence($sequence)
	{
		$this->sequence = $sequence;
	}

	/**
	 * @return Category
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * @param Category $category
	 */
	public function setCategory($category) 
	{
		$this->category = $category;
	}

	/**
	 * @return Database[]
	 */
	public function getDatabases()
	{
		return $this->databases;
	}

	/**
	 * @param Database $databases
	 */
	public function addDatabase(Database $database)
	{
		$database->setSubcategory($this);
		$this->databases[] = $database;
	}
}