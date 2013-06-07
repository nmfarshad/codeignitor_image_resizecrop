<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('resize_crop')){
    function resize_crop($abs_path_to_image='',$outputpath='',$customename='', $required_width='', $required_height=''){
  	
		//current image converted into an image identifier 
		// "imagecreatefromjpeg() returns an image identifier representing the image obtained from the given filename." -php.net
		$im = imagecreatefromjpeg($abs_path_to_image);
		//get image width and height 
		//returns an array with 0 position for width 1 position for height
		$vals = getimagesize($abs_path_to_image);
		
		$upload_width =  $vals[0];
		$upload_height =  $vals[1];
		
		
		$aspectratio_width = ($upload_width/$upload_height) * required_width;
		$aspectratio_height = ($upload_width/$upload_height) * required_height;
		
		$solution1_width = $aspectratio_width; 
		$solution1_height = $required_height;
		
		$solution2_width = $required_width; 
		$solution2_height = $aspectratio_height;
		
		$x=0;
		$y=0;
		$final_width =0 ;
		$final_height =0 ;
		if($solution1_width >= $required_width and $solution2_height >= $required_height){ 
			$final_width = $solution1_width;
			$final_height = $solution1_height;
			//crop width
			$x = -(round(($solution1_width - $upload_width)/2,2)); 
		}else{ 
			$final_width = $solution2_width;
			$final_height = $solution2_height;
			//crop height
			$y = -(round(($solution2_height - $upload_height)/2,2)); 
		}
		
		#Resize 
		//create a image identifier 
		// "imagecreatetruecolor() returns an image identifier representing a black image of the specified size." -php.net
		$image_p = imagecreatetruecolor($final_width, $final_height);
		
		// "imagecopyresampled() copies a rectangular portion of one image to another image, smoothly interpolating pixel 
		// values so that, in particular, reducing the size of an image still retains a great deal of clarity.
		// In other words, imagecopyresampled() will take a rectangular area from src_image of width src_w and 
		// height src_h at position (src_x,src_y) and place it in a rectangular area of dst_image of width dst_w and 
		// height dst_h at position (dst_x,dst_y).
		// If the source and destination coordinates and width and heights differ, appropriate stretching or shrinking of 
		// the image fragment will be performed. The coordinates refer to the upper left corner. This function can be used 
		// to copy regions within the same image (if dst_image is the same as src_image) but if the regions overlap the 
		// results will be unpredictable." - php.net 

		//Resize Image
		imagecopyresampled($image_p, $im, 0, 0, 0, 0, $final_width, $final_height, $required_width, $required_height);
		
		//crop Image
		//create a image identifier 
		$image_px = imagecreatetruecolor($required_width, $required_height);
		imagecopyresampled($image_px, $image_p, $x, $y, 0, 0, $final_width, $final_height, $final_width, $final_height);
		
		
		//ob_clean();
		//flush();
		//out put a jpeg file 
		imagejpeg($image_px,$outputpath.$customename,90);
		imagedestroy($image_p);
		imagedestroy($image_px);
	}
}
?>
