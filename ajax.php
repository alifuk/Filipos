<?php
if(isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['menu']) && !empty($_POST['menu'])    ) {
    $action = $_POST['action'];
    $menu = $_POST['menu'];



foreach (new DirectoryIterator("./".$menu . "/" .  $action . "/content/bin/images/thumb") as $fileInfo) {
    			if($fileInfo->isDot()) continue;
    			if($fileInfo->isFile()){
    			
    			echo "
            <span class=\"thumbSpan\">
                <img class=\"thumb\" src=\"./".$menu . "/" .  $action . "/content/bin/images/thumb/" . $fileInfo->getFilename() . " \" />
            </span>";
    			

    			}
    
			}

/*

$dir = new RecursiveDirectoryIterator( "./".$menu . "/" .  $action . "/content/images/thumb" ,
    FilesystemIterator::SKIP_DOTS);

// Flatten the recursive iterator, folders come before their files
$it  = new RecursiveIteratorIterator($dir,
    RecursiveIteratorIterator::SELF_FIRST);

// Maximum depth is 1 level deeper than the base folder
$it->setMaxDepth(0);

// Basic loop displaying different messages based on file or folder
foreach ($it as $fileinfo) {
    if ($fileinfo->isFile() ) {
        /*printf("File From %s - %s\n", $it->getSubPath(), $fileinfo->getFilename());*/
        

/*
        if ( strrpos($fileinfo->getFilename(), "jpg" ) ) {



            echo "
            <span class=\"thumbSpan\">
                <img class=\"thumb\" src=\"" .$menu . "/" .  $action . "/content/images/thumb" . $fileinfo->getFilename() . " \" />
            </span>";





         
      }

    }
}


    /*
    switch($action) {
        case 'test' : echo "ahoj";break;
        case 'blah' : blah();break;
        // ...etc...
    }*/
}
?>