<?php

namespace Xerxes\Record;

use Xerxes\Utility\Parser;

/**
 * Record Format
 * 
 * @author David Walker
 * @copyright 2011 California State University
 * @link http://xerxes.calstate.edu
 * @license http://www.gnu.org/licenses/
 * @version
 * @package Xerxes
 */

class Format
{
	protected $internal = ""; // original value from source
	protected $normalized = ""; // normalized value
	protected $public = ""; // value for public display
	
	// ris format types
	
	// see pdf available at -- http://www.refman.com/support/risformat_intro.asp
	// also from wikipedia -- http://en.wikipedia.org/wiki/RIS_(file_format)
	
	const Generic = "GEN";
	const AbstractOfWork = "ABS";
	
	const AggregatedDatabase = "AGGR";
	const AncientText = "ANCIENT";
	const ArticleElectronic = "EJOUR";
	const ArticleInPress = "INPR";
	const ArticleJournal = "JOUR";
	const ArticleMagazine = "MGZN";
	const ArticleNewspaper = "NEWS";
	const Artwork = "ART";
	const AudiovisualMaterial = "ADVS";
	const Bill = "BILL";
	const BillUnenacted = "UNBILL";
	const Blog = "BLOG";
	const Book = "BOOK";
	const BookEdited = "EDBOOK";
	const BookElectronic = "EBOOK";
	const BookSection = "CHAP";
	const BookSectionElectronic = "ECHAP";
	const Broadcast = "MPCT";
	const CourtCase = "CASE";
	const Catalog = "CTLG";
	const Chart = "CHART";
	const ClassicalWork = "CLSWK";
	const ComputerProgram = "COMP";
	const ConferencePaper = "CPAPER";
	const ConferenceProceeding = "CONF";
	const Dataset = "DATA";
	const DictionaryEntry = "DICT";
	const EncyclopediaArticle = "ENCYC";
	const Equation = "EQUA";
	const Figure = "FIGURE";
	const GovernmentDocument = "GOVDOC";
	const Grant = "GRNT";
	const Hearing = "HEAR";
	const InternetCommunication = "ICOMM";
	const Journal = "JFULL";
	const LegalRule = "LEGAL";
	const Manuscript = "MANSCPT";
	const Map = "MAP";
	const MusicalScore = "MUSIC";
	const OnlineDatabase = "DBASE";
	const OnlineMultimedia = "MULTI";
	const Pamphlet = "PAMP";
	const Patent = "PAT";
	const PersonalCommunication = "PCOMM";
	const Report = "RPRT";
	const Serial = "SER";
	const Slide = "SLIDE";
	const SoundRecording = "SOUND";
	const Standard = "STAND";
	const Statute = "STAT";
	const Thesis = "THES";
	const UnpublishedWork = "UNPD";
	const VideoRecording = "VIDEO";
	const WebPage = "ELEC";
	
	// local types not covered above
	// always include XERXES_ at the start to distinguish them
	
	const BookReview = "XERXES_BookReview";
	const Image = "XERXES_Image";
	const Kit = "XERXES_KIT";
	const MixedMaterial = "XERXES_MixedMaterial";
	const PhysicalObject = "XERXES_PhysicalObject";
	const Review = "XERXES_Review";
	
	// aliases
	
	const Article = "JOUR";
	const Unknown = "GEN";
	const Periodical = "JFULL";
	
	public function determineFormat($data_fields)
	{
		$this->setFormat($this->extractFormat($data_fields));
	}
	
	public function toRIS()
	{
		// the explicit cases below handle our locally defined types,
		// otherwise just take the internal value straight-up, 
		// since it is itself the RIS type
		
		switch ( $this->internal )
		{
			case self::BookReview :
			case self::Review :
				
				return self::Article;
				break;

			case self::Image :
			case self::Kit :
			case self::MixedMaterial :
			case self::PhysicalObject :
			case "":
				
				return self::Unknown;
				break;
				
			default:
				
				return $this->internal;
		}
	}
	
	/**
	 * Crosswalk the internal format to one available in OpenURL 1.0
	 *
	 * @return string				OpenURL genre value
	 */
	
	public function toOpenURLGenre()
	{
		switch ( $this->internal )
		{
			case self::Journal :
			case self::Serial :
					
				return "journal";
				break;

			case self::ArticleElectronic :
			case self::ArticleJournal :				
			case self::ArticleMagazine :
			case self::ArticleNewspaper :			
				
				return "article";
				break;
			
			case self::ConferenceProceeding :
				
				return "proceeding";
				break;

			case self::ConferencePaper :
				
				return "conference";
				break;				
				
			case self::ArticleInPress :
				
				return "preprint";
				break;
			
			case self::Book :
			case self::BookEdited :	
			case self::BookElectronic :							

				return "book";
				break;

			case self::BookSection :
			case self::BookSectionElectronic :
			case self::DictionaryEntry :
			case self::EncyclopediaArticle :				

				return "bookitem";
				break;
			
			case self::Report :
				
				return "report";
				break;
			
			case self::Thesis :
				
				return "dissertation"; // not an actual openurl genre, but supported by sfx
				break;
				
			case self::AncientText :
			case self::Bill :
			case self::Blog :
			case self::CourtCase :
			case self::ClassicalWork :
			case self::GovernmentDocument :
			case self::Grant :
			case self::Hearing :
			case self::LegalRule :
			case self::Manuscript :
			case self::Pamphlet :
			case self::Patent :
			case self::PersonalCommunication :
			case self::Standard :
			case self::Statute :
			case self::BillUnenacted :
			case self::WebPage :

				return "document";
				break;				
				
			default :
				
				return "unknown";
		}
	}
	
	public function extractFormat($data_fields)
	{
		if ( is_array($data_fields) )
		{
			$data_fields = implode(" ", $data_fields); // combine them into a string
		}
		
		$data_fields = Parser::strtolower( $data_fields );
		
		if ( strstr( $data_fields, 'dissertation' ) ) return  self::Thesis; 
		if ( strstr( $data_fields, 'proceeding' ) ) return  self::ConferenceProceeding; 
		if ( strstr( $data_fields, 'conference' ) ) return  self::ConferencePaper; 
		if ( strstr( $data_fields, 'hearing' ) ) return  self::Hearing; 
		if ( strstr( $data_fields, 'working' ) ) return  self::UnpublishedWork; 
		if ( strstr( $data_fields, 'book review' ) || strstr( $data_fields, 'review-book' ) ) return  self::BookReview; 
		if ( strstr( $data_fields, 'film review' ) || strstr( $data_fields, 'film-book' ) ) return  self::Review;
		if ( strstr( "$data_fields ", 'review ' ) ) return  self::Review;
		if ( strstr( $data_fields, 'book art' ) || strstr( $data_fields, 'book ch' ) || strstr( $data_fields, 'chapter' ) ) return  self::BookSection; 
		if ( strstr( $data_fields, 'journal' ) ) return  self::Article; 
		if ( strstr( $data_fields, 'periodical' ) || strstr( $data_fields, 'serial' ) ) return  self::Article; 
		if ( strstr( $data_fields, 'book' ) ) return  self::Book;
        if ( strstr( $data_fields, 'pamphlet' ) ) return  self::Pamphlet;  
        if ( strstr( $data_fields, 'essay' ) ) return  self::Article;
		if ( strstr( $data_fields, 'article' ) ) return  self::Article;

		// if we got this far, just return unknown
		
		return self::Unknown;		
	}
	
	/**
	 * Return the constant name with the supplied value
	 * 
	 * @param string $value
	 */
	
	protected function getConstNameForValue($value)
	{
		$reflector = new \ReflectionClass($this);
		
		foreach ( $reflector->getConstants() as $const => $val )
		{
			if ( $value == $val )
			{
				return $const;
			}
		}
	}
	
	
	// @todo this is only for testing
	
	public function setFormat($format)
	{
		$this->internal = $format;
		$this->normalized = $format;
		$this->public = $format;
	}
	
	public function getInternalFormat()
	{
		return $this->internal;
	}
	
	public function setInternalFormat($format)
	{
		$this->internal = $format;
	}
	
	public function getNormalizedFormat()
	{
		return $this->normalized;
	}
	
	public function setNormalizedFormat($format)
	{
		$this->normalized = $format;
	}
	
	public function getPublicFormat()
	{
		return $this->public;
	}
	
	public function setPublicFormat($format)
	{
		$this->public = $format;
	}	
	
	public function __toString()
	{
		return (string) $this->public;
	}
	
	public function toXML() // @todo: replace with toArray
	{
		$value = Parser::escapeXml((string) $this->public);
		
		$xml = new \DOMDocument();
		$xml->loadXML("<format>$value</format>");
		
		return $xml;
	}
}