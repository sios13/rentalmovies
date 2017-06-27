<?php

class CImage {

	private $maxWidth;
	private $maxHeight;

	private $src;
	private $verbose;
	private $saveAs;
	private $quality;
	private $ignoreCache;
	private $newWidth;
	private $newHeight;
	private $cropToFit;
	private $sharpen;
	
	private $pathToImage;
	private $filesize;
	
	private $width;
	private $height;
	private $type;
	private $attr;
	
	private $cropWidth;
	private $cropHeight;
	
	private $fileExtension;
	private $cacheFileName;
	
	private $image;
	
	public function __construct($subdir = "") {
		define('IMG_PATH', realpath(__DIR__ . '/../../webroot/img/' . $subdir . '/'));
		define('CACHE_PATH', realpath(__DIR__ . '/../../webroot/img/cache/'));
	
		$this->maxWidth = 2000;
		$this->maxHeight = 2000;
	
		$this->src        = isset($_GET['src'])     ? $_GET['src']      : null;
		$this->verbose    = isset($_GET['verbose']) ? true              : null;
		$this->saveAs     = isset($_GET['save-as']) ? $_GET['save-as']  : null;
		$this->quality    = isset($_GET['quality']) ? $_GET['quality']  : 60;
		$this->ignoreCache = isset($_GET['no-cache']) ? true           : null;
		$this->newWidth   = isset($_GET['width'])   ? $_GET['width']    : null;
		$this->newHeight  = isset($_GET['height'])  ? $_GET['height']   : null;
		$this->cropToFit  = isset($_GET['crop-to-fit']) ? true : null;
		$this->sharpen    = isset($_GET['sharpen']) ? true : null;
		
		$this->pathToImage = IMG_PATH . '/' . $this->src;
		
		$this->validateIncomingArguments();
		$this->displayLog();
		$this->getImageInformation();
		$this->calculateWidthAndHeight();
		$this->createCacheFilename();
		$this->checkCache();
		$this->openOriginalImage();
		$this->resizeImage();
		$this->applyFilters();
		$this->saveImageAs();
		
		$this->outputImage($this->cacheFileName, $this->verbose);
	}
	
	function validateIncomingArguments() {
	
		is_dir(IMG_PATH) or $this->errorMessage('The image dir is not a valid directory.');
		is_writable(CACHE_PATH) or $this->errorMessage('The cache dir is not a writable directory.');
		isset($this->src) or $this->errorMessage('Must set src-attribute.');
		preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $this->src) or $this->errorMessage('Filename contains invalid characters.');
		substr_compare(IMG_PATH, $this->pathToImage, 0, strlen(IMG_PATH)) == 0 or $this->errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
		is_null($this->saveAs) or in_array($this->saveAs, array('png', 'jpg', 'jpeg')) or $this->errorMessage('Not a valid extension to save image as');
		is_null($this->quality) or (is_numeric($this->quality) and $this->quality > 0 and $this->quality <= 100) or $this->errorMessage('Quality out of range');
		is_null($this->newWidth) or (is_numeric($this->newWidth) and $this->newWidth > 0 and $this->newWidth <= $this->maxWidth) or $this->errorMessage('Width out of range');
		is_null($this->newHeight) or (is_numeric($this->newHeight) and $this->newHeight > 0 and $this->newHeight <= $this->maxHeight) or $this->errorMessage('Height out of range');
		is_null($this->cropToFit) or ($this->cropToFit and $this->newWidth and $this->newHeight) or $this->errorMessage('Crop to fit needs both width and height to work');
	}
	
	function displayLog() {
		if ($this->verbose) {
			$query = array();
			parse_str($_SERVER['QUERY_STRING'], $query);
			unset($query['verbose']);
			$url = '?' . http_build_query($query);

			echo "
			<html lang='en'>
			<meta charset='UTF-8'/>
			<title>img.php verbose mode</title>
			<h1>Verbose mode</h1>
			<p><a href=$url><code>$url</code></a><br>
			<img src='{$url}' /></p>
			";
		}
	}
	
	function getImageInformation() {
		$imgInfo = list($this->width, $this->height, $this->type, $this->attr) = getimagesize($this->pathToImage);
		!empty($imgInfo) or $this->errorMessage("The file doesn't seem to be an image.");
		$mime = $imgInfo['mime'];

		if($this->verbose) {
			$this->filesize = filesize($this->pathToImage);
			$this->verbose("Image file: {$this->pathToImage}");
			$this->verbose("Image information: " . print_r($imgInfo, true));
			$this->verbose("Image width x height (type): {$this->width} x {$this->height} ({$this->type}).");
			$this->verbose("Image file size: {$this->filesize} bytes.");
			$this->verbose("Image mime type: {$mime}.");
		}
	}
	
	function calculateWidthAndHeight() {
		$aspectRatio = $this->width / $this->height;

		if($this->cropToFit && $this->newWidth && $this->newHeight) {
			$targetRatio = $this->newWidth / $this->newHeight;
			$this->cropWidth   = $targetRatio > $aspectRatio ? $this->width : round($this->height * $targetRatio);
			$this->cropHeight  = $targetRatio > $aspectRatio ? round($this->width  / $targetRatio) : $this->height;
			if($this->verbose) { $this->verbose("Crop to fit into box of {$this->newWidth}x{$this->newHeight}. Cropping dimensions: {$this->cropWidth}x{$this->cropHeight}."); }
		} else if($this->newWidth && !$this->newHeight) {
			$this->newHeight = round($this->newWidth / $aspectRatio);
			if($this->verbose) { $this->verbose("New width is known {$this->newWidth}, height is calculated to {$this->newHeight}."); }
		} else if(!$this->newWidth && $this->newHeight) {
			$this->newWidth = round($this->newHeight * $aspectRatio);
			if($this->verbose) { $this->verbose("New height is known {$this->newHeight}, width is calculated to {$this->newWidth}."); }
		} else if($this->newWidth && $this->newHeight) {
			$ratioWidth  = $this->width  / $this->newWidth;
			$ratioHeight = $this->height / $this->newHeight;
			$ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
			$this->newWidth  = round($width  / $ratio);
			$this->newHeight = round($height / $ratio);
			if($this->verbose) { $this->verbose("New width & height is requested, keeping aspect ratio results in {$this->newWidth}x{$this->newHeight}."); }
		} else {
			$this->newWidth = $this->width;
			$this->newHeight = $this->height;
			if($this->verbose) { $this->verbose("Keeping original width & heigth."); }
		}
	}
	
	function createCacheFilename() {
		$parts          = pathinfo($this->pathToImage);
		$this->fileExtension  = $parts['extension'];
		$this->saveAs   = is_null($this->saveAs) ? $this->fileExtension : $this->saveAs;
		$quality_       = is_null($this->quality) ? null : "_q{$this->quality}";
		$cropToFit_     = is_null($this->cropToFit) ? null : "_cf";
		$sharpen_       = is_null($this->sharpen) ? null : "_s";
		$dirName        = preg_replace('/\//', '-', dirname($this->src));
		$this->cacheFileName  = CACHE_PATH . "/-{$dirName}-{$parts['filename']}_{$this->newWidth}_{$this->newHeight}{$quality_}{$cropToFit_}{$sharpen_}.{$this->saveAs}";
		$this->cacheFileName  = preg_replace('/^a-zA-Z0-9\.-_/', '', $this->cacheFileName);
		
		if($this->verbose) { $this->verbose("Cache file is: {$this->cacheFileName}"); }
	}
	
	function checkCache() {
		$imageModifiedTime = filemtime($this->pathToImage);
		$cacheModifiedTime = is_file($this->cacheFileName) ? filemtime($this->cacheFileName) : null;
		
		if(!$this->ignoreCache && is_file($this->cacheFileName) && $imageModifiedTime < $cacheModifiedTime) {
			if($this->verbose) { $this->verbose("Cache file is valid, output it."); }
			$this->outputImage($this->cacheFileName, $this->verbose);
		}
		
		if($this->verbose) { $this->verbose("Cache is not valid, process image and create a cached version of it."); }
	}
	
	function openOriginalImage() {
		if($this->verbose) { $this->verbose("File extension is: {$this->fileExtension}"); }

		switch($this->fileExtension) {  
			case 'jpg':
			case 'jpeg': 
				$this->image = imagecreatefromjpeg($this->pathToImage);
				if($this->verbose) { $this->verbose("Opened the image as a JPEG image."); }
				break;  

			case 'png':  
				$this->image = imagecreatefrompng($this->pathToImage); 
				if($this->verbose) { $this->verbose("Opened the image as a PNG image."); }
				break;  

			default: errorPage('No support for this file extension.');
		}
	}
	
	function resizeImage() {
		if($this->cropToFit) {
			if($this->verbose) { $this->verbose("Resizing, crop to fit."); }
			$cropX = round(($this->width - $this->cropWidth) / 2);  
			$cropY = round(($this->height - $this->cropHeight) / 2);    
			$imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
			imagecopyresampled($imageResized, $this->image, 0, 0, $cropX, $cropY, $this->newWidth, $this->newHeight, $this->cropWidth, $this->cropHeight);
			$this->image = $imageResized;
			$this->width = $this->newWidth;
			$this->height = $this->newHeight;
		} else if(!($this->newWidth == $this->width && $this->newHeight == $this->height)) {
			if($this->verbose) { $this->verbose("Resizing, new height and/or width."); }
			$imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
			imagecopyresampled($imageResized, $this->image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
			$this->image  = $imageResized;
			$this->width  = $this->newWidth;
			$this->height = $this->newHeight;
		}
	}
	
	function applyFilters() {
		if($this->sharpen) {
			$this->image = $this->sharpenImage($this->image);
		}
	}
	
	function saveImageAs() {
		switch($this->saveAs) {
			case 'jpeg':
			case 'jpg':
				if($this->verbose) { $this->verbose("Saving image as JPEG to cache using quality = {$this->quality}."); }
				imagejpeg($this->image, $this->cacheFileName, $this->quality);
				break;  

			case 'png':  
				if($this->verbose) { $this->verbose("Saving image as PNG to cache."); }
				imagepng($this->image, $this->cacheFileName);  
				break;  

			default:
				errorMessage('No support to save as this file extension.');
				break;
		}
		
		if($this->verbose) { 
			clearstatcache();
			$cacheFilesize = filesize($this->cacheFileName);
			$this->verbose("File size of cached file: {$cacheFilesize} bytes."); 
			$this->verbose("Cache file has a file size of " . round($cacheFilesize/$this->filesize*100) . "% of the original size.");
		}
	}
	
	function errorMessage($message) {
		header("Status: 404 Not Found");
		die('img.php says 404 - ' . htmlentities($message));
	}
	
	function verbose($message) {
		echo "<p>" . htmlentities($message) . "</p>";
	}
	
	function outputImage($file, $verbose) {
		$info = getimagesize($file);
		!empty($info) or $this->errorMessage("The file doesn't seem to be an image.");
		$mime   = $info['mime'];

		$lastModified = filemtime($file);  
		$gmdate = gmdate("D, d M Y H:i:s", $lastModified);

		if($verbose) {
			$this->verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
			$this->verbose("Memory limit: " . ini_get('memory_limit'));
			$this->verbose("Time is {$gmdate} GMT.");
		}

		if(!$verbose) header('Last-Modified: ' . $gmdate . ' GMT');
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
			if($verbose) { $this->verbose("Would send header 304 Not Modified, but its verbose mode."); exit; }
			header('HTTP/1.0 304 Not Modified');
		} else {  
			if($verbose) { $this->verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); exit; }
			header('Content-type: ' . $mime);  
			readfile($file);
		}
		exit;
	}
	
	function sharpenImage($image) {
		$matrix = array(
			array(-1,-1,-1,),
			array(-1,16,-1,),
			array(-1,-1,-1,)
		);
		$divisor = 8;
		$offset = 0;
		imageconvolution($image, $matrix, $divisor, $offset);
		return $image;
	}
}
