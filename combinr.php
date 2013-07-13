<?php
	
	class Combinr 
	{
		//start point for input
		private $stdin = "";
		private $inputFileType = "";
		private $inputFileDir = "";
		
		//file types that are accepted and information
		private $fileTypes = array('js', 'css');
		private $fileTypeError = "The file type specified is not supported";
		
		//welcome message
		public $welcome = "
==============================================
Welcome to COMBINR, a very simple way to combine 
file with the same extension into one file.
Unfortunately we only support javascript and css file types
other types may work but we have not tested them yet.

Created by   : Darren Leak
Date created : July 13 2013
==============================================

Please choose a file type that would like to combine(Javascript or css, e.g : js, css)
";
			
		//constructor for class
		public function Combinr()
		{	
			//set stdin variable
			$this->stdin = fopen('php://stdin', 'r');
			
			//call display welcome message function
			$this->displayWelcome();
		}
		
		//displays welcome message
		public function displayWelcome()
		{
			//display welcome message
			echo $this->welcome;
			
			//start receiving input 
			$this->getInputDetails();						
		}
		
		//get inputs
		public function getInputDetails()
		{
			//get file type
			$this->inputFileType = trim(fgets($this->stdin));
			
			//check if file type is supported
			$this->validateFileType($this->inputFileType);
			
			//get other input data
			echo "\nPlease enter the directory that contains the files to be combined\n";
			$this->inputFileDir = trim(fgets($this->stdin));
			
			$this->combine($this->inputFileDir, $this->inputFileType);
			
		}
		
		//validate file type input
		public function validateFileType($fileTypeInput)
		{
			try
			{
				if(in_array($fileTypeInput, $this->fileTypes))
				{
					return true;
				}
				else
				{
					echo $this->fileTypeError . "\n";
					
					$restartCombinr = new Combinr();
					
				}
			}	
			catch(Exception $e)
			{
				echo $this->fileTypeError;
			}
		}
		
		//public function combine files
		public function combine($directory, $fileTypeInput)
		{
			//set filename
			$fileName = trim("combinr." . $fileTypeInput);
		
			//store contents of files looped through
			$fileContents = "";
			
			//counter for array
			
			//scan chosen directory for files of chosen type
			$insideDirectory = scandir($directory);
			
			//check if array item is a directory or not
			foreach($insideDirectory as $inside)
			{
				if(!is_dir($inside) && trim($inside) != $fileName)
				{
					$currentPathInfo = pathinfo($inside);
					
					if($currentPathInfo['extension'] == $fileTypeInput)
					{
						$fileContents .= file_get_contents($directory . "/" . $inside) . "\n";
					}
					
				}	
				
			}
			
			//call function to create new file
			$this->createFile($directory, $fileName, $fileContents);
			
		}
		
		public function createFile($directory, $fileName,$fileContents)
		{
			//new file information
			$filePath = $directory . "/" . $fileName;
				
			//check if combined file exists
			if(file_exists($filePath))
			{
				//create file if doesn't exist
				$existingFile = fopen($filePath, 'w');
			
				//write to new file
				fwrite($existingFile, $fileContents);
				fclose($existingFile);
			}
			else
			{
				//create file if doesn't exist
				$createFile = fopen($filePath, 'w');
			
				//write to new file
				fwrite($createFile, $fileContents);
				fclose($createFile);
			}		
			
			echo "File created!\n";
			
		}
		
	}
	
	$startCombinr = new Combinr();
		
?>