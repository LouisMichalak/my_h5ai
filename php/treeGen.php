<?php
ini_set('diplay_errors', '1');
class commonFeatures
{
    public $name;
    public $location;
    public $path;
    public $lastModification;
    public $size;
    public function __construct($location, $name, $size)
    {
        $this->name = $name;
        $this->location = $location;
        $this->path = substr($location, strlen('/wamp64/www'));
        $this->lastModification = filemtime($location);
        $this->size = $size;
    }
    public function getSize()
    {
        $units = ['o', 'kB', 'MB', 'GB', 'TB'];
        $size = $this->size;
        $ctr = 0;
        while ($size >= 1024)
        {
            $ctr++;
            $size = $size / 1024;
        }
        $size = round($size, 2);
        return($size . $units[$ctr]);
    }
    public function getDate()
    {
        return(date("d/m/Y H:i:s", $this->lastModification));
    }
}
class file extends commonFeatures
{
    public $extension;
    public function __construct($location, $name)
    {
        $size = filesize($location);
        parent::__construct($location, $name, $size);
        $this->extension = pathinfo($location, PATHINFO_EXTENSION); 
    }
	public function readFileContent()
	{
		if ($this->extension !== 'png' &&
		   $this->extension !== 'jpg' &&
		   $this->extension !== 'jpeg' &&
		   $this->extension !== 'ico' &&
		   $this->extension !== 'ai' && 
		   $this->extension !== 'gif' &&
		   $this->extension !== 'svg')
		{
			echo readfile($this->location);
		}
		else
		{
			echo '<img class="modal" src="'.$this->path.'" >';
		}
	}
}
class repos extends commonFeatures
{
   public $content;
   public function __construct($location = '/wamp64/www/', $name = 'h5ai')
   {
       parent::__construct($location, $name, 4096);
       $this->content = $this->setContent();
   }
   public function setContent()
   {
       $content = [];
       $files = scandir($this->location);
       foreach ($files as $file)
       {
           $fileLocation = $this->location . $file;
           if ($file !== '.' && $file !== '..')
           {
               if (is_dir($fileLocation))
               {
                   $newDir = new repos($fileLocation . '/', $file);
                   $this->size += $newDir->size;
                   $content[] = $newDir;
               }
               else
               {
                   $newFile = new file($fileLocation, $file);
                   $this->size += $newFile->size;
                   $content[] = $newFile;
               }
           }
       }
       return($content);
   }
   public function displayMenu()
   {
	   $refPath = '';
	   if (isset($_GET['folder']))
	   {   
	   		$refPath = $_GET['folder'];
	   }
       foreach ($this->content as $obj)
       {
           if (get_class($obj) === 'repos')
           {
               $isActive = '';
               $isOpenned = 'closedDir';
               if (strpos($refPath, $obj->path) === 0)
               {
                   $isOpenned = 'opennedDir';
               }
               if ($obj->path === $refPath)
               {
                   $isActive = 'active';
               }
               echo '<li class="'.$isOpenned.' '.$isActive.'">'.
                   '<button class="displayButton"></button> '.
                   '<a href="/my_h5ai'.$obj->path.'">'.$obj->name.'</a>';
               echo '<ul>';
               $obj->displayMenu('closedDir');
               echo '</ul>';
               echo '</li>';
           }
       }
   }
	public function sortMenu($a, $b)
	{
		$sortArray = [
			'size' => $_GET['size'],
			'lastModification' => $_GET['date'],
			'name' => $_GET['name']
		];
		$cmp = 0;
		foreach ($sortArray as $key => $value)
		{
			if ($value === 'asc' && $cmp === 0)
			{
				$cmp = $a->$key <=> $b->$key;
			}
			else if ($value === 'desc' && $cmp === 0)
			{
				$cmp = $b->$key <=> $a->$key;
			}
		}
		return($cmp);
	}
	public function displayMainMenu()
	{
	   $repos = $this;
	   if (isset($_GET['folder']) && $_GET['folder'] !== '/')
	   {
		   $repos = $this->researchAFile();
	   }
	   uasort($repos->content, array($repos, 'sortMenu'));
	   foreach ($repos->content as $file)
	   {
		   $type = '';
		   $unicode = '';
		   if (get_class($file) === 'repos')
		   {
			   $type = 'dir';
			   $unicode = '&#128448';
		   }
		   echo '<a href="/my_h5ai'.$file->path.'">'.
			   '<span>'.
			   '<span class="fileIcon '.$type.'">'.$unicode.'</span>'.
			   '<span class="fileName">'.$file->name.'</span>'.
			   '</span>'.
			   '<span><span>'.$file->getDate().'</span>'.
			   '<span>'.$file->getSize().'</span></span>'.
			   '</a>';
	   }
	}
	public function displayBreadCrumb()
	{
		$refPath = '';
		if(isset($_GET['folder']))
		{
			$refPath = $_GET['folder'];
		}
		foreach ($this->content as $obj)
		{
			if ($obj->path === $refPath)
			{
			   echo '<span class="chevron">&#x3009;</span><a href="/my_h5ai'.$obj->path.'">'.$obj->name.'</a>';
			   break;
			}
			else if (strpos($refPath, $obj->path) === 0)
			{
			   echo '<span class="chevron">&#x3009;</span><a href="/my_h5ai'.$obj->path.'">'.$obj->name.'</a>';
			   $obj->displayBreadCrumb();
			   break;
			}
		}
	}
	public function researchAFile()
	{
	   $refPath = $_GET['folder'];
	   foreach ($this->content as $obj)
	   {
		   if ($obj->path === $refPath)
		   {
			   return($obj);
		   }
		   else if (strpos($refPath, $obj->path) === 0)
		   {
			   return($obj->researchAFile());
			   break;
		   }
	   }
	}
	public function researchFiles()
	{
		$name = $_GET['input'];
		if($name === "")
		{
			return;
		}
		foreach ($this->content as $obj)
		{
		   	if ($obj->name === $name)
		   	{
			   	$type = '';
			   	$unicode = '';
			   	if (get_class($obj) === 'repos')
			   	{
					$type = 'dir';
				   	$unicode = '&#128448';
					$obj->researchFiles();
			   	}
			   	echo '<a href="/my_h5ai'.$obj->path.'">'.
				   '<span>'.
				   '<span class="fileIcon '.$type.'">'.$unicode.'</span>'.
				   '<span class="fileName">'.$obj->name.'</span>'.
				   '</span>'.
					'<span><span>'.$obj->getDate().'</span>'.
				   '<span>'.$obj->getSize().'</span></span>'.
				   '</a>';
		   	}
		}
	}
}
$tree = new repos();
if (isset($_GET['displayMenu']) && $_GET['displayMenu']  === 'true')
{
    $tree->displayMainMenu();
}
if (isset($_GET['displayModal']) && $_GET['displayModal'] === 'true')
{
	$file = $tree->researchAFile();
	$file->readFileContent();
}
if (isset($_GET['researchFiles']) && $_GET['researchFiles'] === 'true')
{
	$tree = $tree->researchFiles();
}
?>