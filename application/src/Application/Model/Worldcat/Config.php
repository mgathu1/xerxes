<?php

/*
 * This file is part of Xerxes.
 *
 * (c) California State University <library@calstate.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Model\Worldcat;

use Application\Model\Search;

/**
 * Worldcat Config
 *
 * @author David Walker <dwalker@calstate.edu>
 */

class Config extends Search\Config
{
	protected $config_file = "config/worldcat";
	private static $instance; // singleton pattern
	
	public static function getInstance()
	{
		if ( empty( self::$instance ) )
		{
			self::$instance = new Config();
			$object = self::$instance;
			$object->init();			
		}
		
		return self::$instance;
	}
	
	public function getWorldcatGroup($source)
	{
		$group_object = new ConfigGroup();
		
		$groups = $this->xml->xpath("//config[@name='worldcat_groups']/group");
		
		if ( $groups != false )
		{
			foreach ( $groups as $group )
			{
				if ( $group["id"] == $source )
				{
					$group_object->limit_material_types = (string) $group->limit_material_types;
					$group_object->exclude_material_types = (string) $group->exclude_material_types;
					$group_object->frbr = (string) $group->frbr;
					$group_object->query_limit = (string) $group->query_limit;
	
					if ( (string) $group->show_holdings == "true" )
					{
						$group_object->show_holdings = true;
					}
	
					// include certain libraries
					
					$group_object->libraries_include = (string) $group->libraries;
					
					// exclude certain libraries
	
					$id = (string) $group->exclude;
	
					if ( $id != "" )
					{
						$arrID = explode(",", $id);
	
						foreach ( $arrID as $strID )
						{
							foreach ( $this->xml->xpath("//config[@name='worldcat_groups']/group[@id='$strID']/libraries") as $exclude )
							{
								if ( $group_object->libraries_exclude != null )
								{
									$group_object->libraries_exclude .= "," . (string) $exclude;
								}
								else
								{
									$group_object->libraries_exclude = (string) $exclude;
								}
							}
						}
					}
					
					break; // we got our object, so quite of out the foreach loop
				}
			}
		}
		
		return $group_object;
	}	
}
