<?php

/**
 * A templating engine
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT License, available at
 * http://www.opensource.org/licenses/mit-license.html
 *
 * @author     Tim Smith <tim@thinklikearobot.com>
 * @copyright  2012 thinklikearobot.com
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Jager\Template;
use Exception;
include('third_party/simplehtmldom/simple_html_dom.php');
// include('third_party/ganon.php');
	class Engine{

		const  DS  = DIRECTORY_SEPARATOR ;

		/**
		* template root folder
		*@var string
		*/
		public $templateRoot;

		/** 
		* Stores location of template file
		* @var string
		*/
		public $templateFile;
		
		/**
		 *Stores the contents of the template file
		 *@var string
		 */
		private $_template;

		/** 
		* Stores the entries to be parsed into the template
		* @var array
		*/
		public $data = array();


		public function __construct($templateRoot = null){

			// define template rooot
			if($templateRoot != null ){
				$this->templateRoot = $templateRoot;
			}
			else{ 
				$this->templateRoot = getcwd();
			}
		}


		/**
		* Loads template
		*
		*/
		private function _load_template(){
			// todo load template
			try{
				$path = $this->fileExistsCheck($this->templateFile);
				$this->_template = file_get_contents($path);
			}
			catch(Exception $e){
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}

		
		/**
		*  Check to see if template file exist
		*	@var string;
		*/
		private function fileExistsCheck($file){
			$path;

			$templateFile = $this->dirRoot.$file;

			try{
				if(file_exists($templateFile) && is_readable($templateFile)){
					$path = $templateFile;
				}
				else{
					throw new \Exception("Template <strong>\"$file\"</strong> file not found.");
				}
			}
			catch(Exception $e){
				
				$this->_showException($e->getMessage());
			}
			return $path;
		}

		/**
		* build our template view
		* @var string , @var array
		*/
		public function view($template, $data = false){

				$this->templateFile = $template;

				$this->_load_template();
				$template = $this->_parseTemplate();

				return  $template;
		}

		/**
		* Parse our template file 
		* replace any template vars with their value.
		*/
		private function _parseTemplate(){

			 // Create an alias of the template file property to save space
	        $template = $this->_template;
	 
	        // Remove any PHP-style comments from the template
	        $comment_pattern = array('#/\*.*?\*/#s', '#(?<!:)//.*#');
	        $template = preg_replace($comment_pattern, NULL, $template);

	        // first include anyfiles that need to be included.
	        $template = str_get_html($template);
	        $template = $this->_getincludes($template);
	        $template = $this->__replaceVars($template);

	        // $this->see($template);
	         return $template;
		}



		/**
		* Checks the template for any includes and brings them into the the main tempalte
		*@var obj
		*/
		private function _getincludes($html){

			$selector = '*[data-jager-include]';
			$nth = 0;

			foreach($html->find($selector) as $e){
			      $file = $e->attr['data-jager-include'];

			      if($this->fileExistsCheck( $this->dirRoot.$file)){
			      		$content = file_get_contents( $this->dirRoot.$file);
			      		//todo 
			      		//consider parsing these includes before adding them to the dom
			      		$html->find($selector,$nth)->innertext = $content;
			      }
			      
			      $nth++;
			  	  // todo 
			  	  // removal of jager tags.
			  	  // $e->removeAttribute('data-jager-include');
			  	  $e->attr['data-jager-include']="";
			  	  
			  }


			  return $html;
		}

		private function __replaceVars($html){
			$html = str_get_html($html);
			$selector = '*[data-jager-var]';
			$nth = 0; 

			foreach($html->find($selector) as $e){
				$var = $e->attr['data-jager-var'];
				$html->find($selector,$nth)->innertext = $this->data[$var];
			}

			return $html;

		}

		//todo 
		// remove this 
		// just used for dumping shit in a readible fashion
		private function see($elm){
			echo "<pre>";
			print_r($elm);
			echo "</pre>";
		}

		// show usefull exceptions rather than just small text on a screen.
		private function _showException($message){

			echo	"<div style=\"margin: 10px auto; width: 960px; padding: 8px 35px 8px 14px;color: #C09853;background-color: #FCF8E3;border: 1px solid #FBEED5;\">";
	        echo       "<h4 style=\"margin:0px;font-size:17.5px\">Warning!</h4>";
	        echo      "<p style=\"margin: 0 0 10px;\">$message</p>";
			echo	"</div>";

		}

	}
