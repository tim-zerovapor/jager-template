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
		public $entries = array();


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

			if(file_exists($templateFile) && is_readable($templateFile)){
				$path = $templateFile;
			}
			else{
				throw new Exception('Template file not found.');
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

		private function _parseTemplate(){

			 // Create an alias of the template file property to save space
	        $template = $this->_template;
	 
	        // Remove any PHP-style comments from the template
	        $comment_pattern = array('#/\*.*?\*/#s', '#(?<!:)//.*#');
	        $template = preg_replace($comment_pattern, NULL, $template);

	        $template = str_get_html($template);
	        $template = $this->_getincludes($template);

	        // $this->see($template);
	         return $template;
		}







		private function _getincludes($html){


			$selector = '*[data-jager=true]';
			$nth = 0;

			foreach($html->find($selector) as $e){
			      $file = $e->attr['data-include'];

			      if($this->fileExistsCheck( $this->dirRoot.$file)){
			      		$content = file_get_contents( $this->dirRoot.$file);
			      		$html->find($selector,$nth)->innertext = $content;
			      }
			      
			      $nth++;
			      // $e->removeAttribute("data-jager");
			  	  $e->removeAttribute("data-include");
			  }

			  
			  
			  return $html;
		}



		function see($elm){
			echo "<pre>";
			print_r($elm);
			echo "</pre>";
		}

	}
