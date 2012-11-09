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
		 * Root directory of our template
		 * @var string
		 */
		public $templateRoot;

		/**
		 * Template file to load
		 * @var string
		 */
		public $templateFile;
		
		/**
		 *  Parsed  Template page
		 * @var [type]
		 */
		private $_template;

		/**
		 * Holds all the data to be inserted into the template
		 * @var array
		 */
		public $data = array();

		/**
		 * [__construct description]
		 * @param string $templateRoot This is the root folder for your templates.
		 */
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
		 * [_load_template description]
		 * @return [type]
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

			 // first include anyfiles that need to be included.
			 $template = str_get_html($template);
			 $template = $this->_getincludes($template);

			 $template = $this->_replaceVars($template);// make sure this is last
			 $template = $this->_foreach($template);// make sure this is last

			 // $this->see($template);
			 return $template;
		}



		/**
		* Checks the template for any includes and brings them into the the main tempalte
		*@var obj
		*/
		private function _getincludes($html){

			$selector = '*[data-jte-include]';
			$nth = 0;

			foreach($html->find($selector) as $e){
			      $file = $e->attr['data-jte-include'];

			      if($this->fileExistsCheck( $this->dirRoot.$file)){
			      		$content = file_get_contents( $this->dirRoot.$file);
			      		//todo 
			      		//consider parsing these includes before adding them to the dom
			      		$html->find($selector,$nth)->innertext = $content;
			      }
			      
			      $nth++;
			      	
			  	  $e->attr['data-jte-include']="";
			  	  
			  }


			  return $html;
		}

		private function _replaceVars($html){
			$html = str_get_html($html);
			$selector = '*[data-jte-var]';
			$nth = 0; 

			foreach($html->find($selector) as $e){
				$var = $e->attr['data-jte-var'];
				$e->innertext = $this->data[$var];
			}

			return $html;
		}

		/**
		 * Parses an array then replaces values ( {{*}} ) that exisit in the template
		 * @param  String $html  This should be a template file that you want to parse
		 * @return  String      parsed HTML
		 */
		private function _foreach($html){
			$selector = '*[data-jte-foreach]';
			$nth = 0;
			$temp_data; // holds temp template data.
			
			// get each foreach loop in the dom
			foreach($html->find($selector) as $e){
				
				$array = $e->attr['data-jte-foreach'];// get our array name
				$entries  = $this->data[$array]; // get our array of data
				$tempTemplate = $e->innertext;
				$shell;

				$pattern = "/{{(.*?)}}/s";

				$vars = array();

				preg_match_all ($pattern, $tempTemplate, $vars);
	
				foreach($entries as $entry){

					$temp = $tempTemplate;

					foreach($entry as $k=>$v){
						// get index 
						$key = array_search($k, $vars[1]);
						//  replace {{*}} in template
						$temp = str_replace($vars[0][$key], $v, $temp);
						
					}	

					$shell= $shell.$temp;
					unset($temp);
				}

				$e->innertext = $shell;
				unset($shell);
			}

			return $html;
		}

		// show usefull exceptions rather than just small text on a screen.
		private function _showException($message){

			echo	"<div style=\"margin: 10px auto; width: 100%; padding: 8px 35px 8px 14px;color: #C09853;background-color: #FCF8E3;border: 1px solid #FBEED5;\">";
			echo       "<h4 style=\"margin:0px;font-size:17.5px\">Warning!</h4>";
			echo      "<p style=\"margin: 0 0 10px;\">$message</p>";
			echo	"</div>";
		}

		//todo 
		// remove this 
		// just used for dumping shit in a readible fashion
		private function see($elm){
			echo"<pre>";
			print_r($elm);
			echo "</pre>";
		}

	}
