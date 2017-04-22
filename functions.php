<?php

function sed_email(){

}


function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
    $ext = strtolower(substr($src, -3));
    echo $ext;
    switch ($ext){
        case 'png':
            $source_image = imagecreatefrompng($src);
        break;
        case 'jpg':
        case 'peg':
            $source_image = imagecreatefromjpeg($src);
        break;
        
        case 'gif':
            $source_image = imagecreatefromgif($src);
        break;
    }
	//$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	//imagejpeg($virtual_image, $dest);
    switch($ext){
        case 'png':
            imagepng($virtual_image, $dest);
        break;
        case 'jpg':
        case 'peg':
            imagejpeg($virtual_image, $dest);
        break;
        case 'gif':
            imagegif($virtual_image, $dest);
        break;
    }
}

function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}