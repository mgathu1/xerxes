<?php

/*
 * This file is part of Xerxes.
 *
 * (c) California State University <library@calstate.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xerxes\Marc;

/**
 * MARC Control Field
 * 
 * @author David Walker <dwalker@calstate.edu>
 */

class ControlField extends Field 
{
	public $tag;
	public $value;
	
	/**
	 * Create a MARC Control Field
	 * 
	 * @param \DOMNode $objNode
	 */
	
	public function __construct(\DOMNode $objNode = null)
	{
		if ( $objNode != null )
		{
			$this->tag = $objNode->getAttribute("tag");
			$this->value = $objNode->nodeValue;
		}
	}
	
	/**
	 * Retrieve value at supplied position
	 * 
	 * @param int|string $position	expressed as a number (6) or range (6-7)
	 * 
	 * @return string|null			if value found at supplied position
	 */

	public function position($position)
	{
		$arrPosition = explode("-", $position);
		
		$start = $arrPosition[0];
		$stop = $start;
				
		if ( count($arrPosition) == 2 )
		{
			$stop = $arrPosition[1];
		}
		
		$end = $stop - $start + 1;
		
		if ( strlen($this->value) >= $stop + 1)
		{
			return substr($this->value, $start, $end);
		}
		else
		{
			return null;
		}
	}
}