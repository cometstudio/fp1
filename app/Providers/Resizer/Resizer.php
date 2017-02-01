<?php

/*
Example usage:
    \Resizer::load($_SERVER['DOCUMENT_ROOT'].'/test.jpg')
                ->resize(100, 100)
                ->save($_SERVER['DOCUMENT_ROOT'].'/images/output.jpg');
*/

namespace App\Providers\Resizer;

use Auth;
use Illuminate\Support\Str;

class Resizer {

    private $image;
    private $width;
    private $height;
    private $imageResized;

    private $config = [];

    public function __construct($config = [])
    {
        if(empty($config)){
            $this->config = config('resizer');
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getImagesDir($id = 0)
    {
        $config = $this->getConfig();

        $path = [''];

        $path[] = array_get($config, 'dir');

        if(empty($id)) $path[] =  array_get($config, 'tmpDir');

        $dir = implode('/', $path);

        return $dir;
    }

    public function moveToPermanentLocation($gallery = null, $configSet = 'dirs')
    {
        try{
            if(!$config = $this->getConfig()) throw new \Exception;

            $gallery = $this->gallery($gallery);

            if(!is_array($gallery)) throw new \Exception;

            $temporaryDirsLocation = public_path() . '/' . array_get($config, 'dir') . '/' . array_get($config, 'tmpDir');
            $permanentDirsLocation = public_path() . '/' . array_get($config, 'dir');

            $dirs = array_get($config, $configSet);

            foreach($dirs as $dir=>$params) {
                foreach ($gallery as $item) {

                    $temporaryFilePath = $temporaryDirsLocation.'/'.$dir.'/'.$item.'.jpg';

                    if(file_exists($temporaryFilePath) && is_file($temporaryFilePath)){
                        $permanentFilePath = $permanentDirsLocation.'/'.$dir.'/'.$item.'.jpg';

                        if(copy($temporaryFilePath, $permanentFilePath)){
                            unlink($temporaryFilePath);
                        }
                    }else throw new \Exception;
                }
            }

            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function gallery($gallery = null, $useEmptyImage = false, $skipFirst = false)
    {
        try{
            if(empty($gallery)) throw new \Exception;

            if(!is_array($gallery)) $gallery = explode(' ', $gallery);

            if(!empty($skipFirst)) array_shift($gallery);

            if(empty($gallery)) throw new \Exception;

            return $gallery;
        }catch (\Exception $e){
            return $useEmptyImage ? ['empty'] : [];
        }
    }

    public function galleryString(array $gallery = [])
    {
        return empty($gallery) ? '' : implode(' ', $gallery);
    }

    public function galleryTitles($titlesString = '')
    {
        return explode(' #', $titlesString);
    }

    public function galleryTitlesString($titles = [])
    {
        return empty($titles) ? '' : implode(' #', $titles);
    }

    public function load($file)
    {
        $this->image    = $this->openImage($file);
        $this->width    = imagesx($this->image);
        $this->height   = imagesy($this->image);

        return $this;
    }

    private function openImage($file){
        if (!is_file($file)){
            throw new \Exception("File {$file} doesn't exists");
        }

        list($width, $height, $type, $attr) = getimagesize($file);

        $mime = image_type_to_mime_type($type);

        switch($mime){
            case 'image/jpeg': return imagecreatefromjpeg($file);
            case 'image/gif':	 return imagecreatefromgif($file);
            case 'image/png':	 return imagecreatefrompng($file);
            case 'image/bmp':	 return imagecreatefromwbmp($file);
        }

        throw new \Exception("Invalid image extension for {$file}. Acceptable image types are jpg,jpeg,gif,png");
    }

    public function resize($newWidth, $newHeight, $crop = false, $addWatermark = false)
    {
        array_set($this->config, 'settings.resize', empty($crop) ? 'auto' : 'crop');

        if(($this->width > $newWidth) || ($this->height > $newHeight)){
            list($width, $height) = $this->getDimensions($newWidth, $newHeight);
        }else{
            $width = $this->width;
            $height = $this->height;
        }

        $this->imageResized = imagecreatetruecolor($width, $height);

        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

        if (array_get($this->config, 'settings.resize') == 'crop'){
            $this->crop($width, $height, $newWidth, $newHeight);
        }

        if($addWatermark) {
            $watermarkFilepath = public_path() . '/'.$this->config['dir'].'/watermark.png';
            $watermark = imagecreatefrompng($watermarkFilepath);
            // bottom - right
            //imagecopy($this->imageResized, $watermark, (imagesx($this->imageResized) - imagesx($watermark) - 30), (imagesy($this->imageResized) - imagesy($watermark) - 30), 0, 0, imagesx($watermark), imagesy($watermark));
            // top - left
            imagecopy($this->imageResized, $watermark, 20, 20, 0, 0, imagesx($watermark), imagesy($watermark));
        }

        return $this;
    }

    public function filter($filter = null)
    {
        if($filter !== null) imagefilter($this->imageResized, $filter);

        return $this;
    }

    public function save($savePath)
    {
        switch (pathinfo($savePath, PATHINFO_EXTENSION)) {
            case 'jpg':
            case 'jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized, $savePath, array_get($this->config, 'settings.quality'));
                }
                break;

            case 'gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);
                }
                break;

            case 'png':
                if (imagetypes() & IMG_PNG) {
                    // Scale quality from 0-100 to 0-9
                    // Invert quality setting as 0 is best, not 9
                    $invertScaleQuality = 9 - round((array_get($this->config, 'settings.quality') / 100) * 9);
                    imagepng($this->imageResized, $savePath, $invertScaleQuality);
                }
                break;
        }

        imagedestroy($this->imageResized);
    }


    private function getDimensions($width, $height){
        switch (array_get($this->config, 'settings.resize')){
            case 'portrait':    return array($this->getSizeByFixedHeight($height),  $height);
            case 'landscape':   return array($width, $this->getSizeByFixedWidth($width));
            case 'auto':        return $this->getSizeByAuto($width, $height);
            case 'crop':        return $this->getOptimalCrop($width, $height);
            case 'exact':
            default:            return array($width, $height);
        }
    }

    private function getSizeByFixedHeight($height){
        return ($this->width / $this->height) * $height;
    }

    private function getSizeByFixedWidth($width){
        return ($this->height / $this->width) * $width;
    }

    private function getSizeByAuto($width, $height){
        if ($this->height < $this->width){
            return array($width, $this->getSizeByFixedWidth($width));
        }

        if ($this->height > $this->width){
            return array($this->getSizeByFixedHeight($height), $height);
        }

        if ($height < $width){
            return array($width, $this->getSizeByFixedWidth($width));
        }

        if ($height > $width){
            return array($this->getSizeByFixedHeight($height), $height);
        }

        return array($width, $height);
    }

    private function getOptimalCrop($width, $height){
        $ratio = min($this->height / $height, $this->width /  $width);
        return array(
            $this->width  / $ratio,
            $this->height / $ratio
        );
    }

    private function crop($optimalWidth, $optimalHeight, $width, $height){
        $x = ( $optimalWidth  / 2) - ( $width  /2 );
        $y = ( $optimalHeight / 2) - ( $height /2 );

        $crop = $this->imageResized;

        $this->imageResized = imagecreatetruecolor($width , $height);
        imagecopyresampled($this->imageResized, $crop, 0, 0, $x, $y, $width, $height , $width, $height);
    }
    
    public function addImage($filePath, $name = '', $tmp = false, $configSet = 'dirs')
    {
        $config = $this->getConfig();

        if(empty($config)) throw new \Exception;

        $user = Auth::user();

        if(empty($name)) $name = Str::random(18).$user->id;

        $image = Resizer::load($filePath);

        $path = !empty($tmp) ? public_path() . '/' . array_get($config, 'dir') . '/' . array_get($config, 'tmpDir') : public_path() . '/' . array_get($config, 'dir');

        $dirs = array_get($config, $configSet);

        if(empty($dirs)) throw new \Exception('No config defined');

        if (is_array($dirs)) {
            foreach ($dirs as $dir => $options) {
                $image->resize($options[0], $options[1], $options[2], (!empty($options[4]) ? true : false))
                    ->filter(!empty($options[3]) ? $options[3] : null)
                    ->save($path . '/' . $dir . '/' . $name . '.jpg');
            }
        }

        return $name;
    }

    public function deleteImages($gallery = null, $tmp = false, $configSet = 'dirs')
    {
        $config = $this->getConfig();

        if(empty($config)) throw new \Exception;

        $gallery = $this->gallery($gallery);

        $path = !empty($tmp) ? public_path() . '/' . array_get($config, 'dir') . '/' . array_get($config, 'tmpDir') : public_path() . '/' . array_get($config, 'dir');

        $dirs = array_get($config, $configSet);

        if(empty($dirs)) throw new \Exception;

        if (is_array($dirs)) {
            foreach ($dirs as $dir => $options) {
                foreach($gallery as $item){

                    $filePath = $path . '/' . $dir . '/' . $item . '.jpg';

                    if(file_exists($filePath) && is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
    }
}
