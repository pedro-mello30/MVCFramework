<?php

/**
* 
*/
class ImageHelper
{
	private $_pasta;
	private $_ext;

	function __construct()
	{

		$this->_pasta 			=  FILES_APP;
		$this->_ext 			=  ".jpeg";

		//VERIFICA SE A PASTA JÁ EXISTE, SE NÃO EXISTIR, ELA SERÁ CRIADA
		if (!is_dir($this->_pasta)) 
			@mkdir( $this->_pasta );
	}

    public function getPasta(): string
    {
        return $this->_pasta;
    }

    public function setPasta(string $pasta)
    {
        $this->_pasta = $pasta;
    }

    public function getExt(): string
    {
        return $this->_ext;
    }

    public function setExt(string $ext)
    {
        $this->_ext = $ext;
    }

	public function saveCompressedFromUpload($data)
    {

        $image = $this->generateImageFromUpload($data);
        return $this->saveCompressed($image);
    }

    public function saveCompressedFromBase64($data)
    {
        $image = $this->generateImageFromBase64($data);
        return $this->saveCompressed($image);
    }

    public function saveFromUpload($data)
    {
        $image = $this->generateFromUpload($data);
        return $this->save($image);
    }

    public function saveFromBase64($data)
    {
        $image = $this->generateImageFromBase64($data);
        return $this->save($image);
    }

    private function saveCompressed($image)
    {
//        print_r("   saveCompressed");
        $imageName = $this->generateName();
        $imagePath = $this->_pasta . $imageName;

        $img = $this->saveImage($image, $imagePath);

        $imageMin = $this->resize($imagePath);
        $imgM = $this->saveImage($imageMin, $imagePath . ".min", 30);

        return ( ($img == $imgM) && ($img == 1) )? $imageName : false;
//        return "saveCompressed";
	}

    private function save($image)
    {
        $imageName = $this->generateName();
        $imagePath = $this->_pasta . $imageName;
        $img = $this->saveImage($image, $imagePath);

        return ($img) ? $imageName : false;
    }

    private function generateImageFromUpload($data)
    {
        $file_info = getimagesize($data['tmp_name']);

        if ($file_info['mime'] == "image/jpeg") {
            $image = imagecreatefromjpeg($data['tmp_name']);
        }else if ($file_info['mime'] == "image/gif") {
            $image = imagecreatefromgif($data['tmp_name']);
        }else if ($file_info['mime'] == "image/png") {
            $image = imagecreatefrompng($data['tmp_name']);
        }

        return $image;

    }

    private function generateImageFromBase64($data)
    {
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace('data:image/jpg;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);

        return imagecreatefromstring($data);
    }

    private function resize($file, $newWidth = 0.2, $newHeight = 0.2)
    {
        $size = getimagesize($file . $this->_ext);
        $width = $size[0] * $newWidth;
        $height = $size[1] * $newHeight;

        $reso = imagecreatefromjpeg($file . $this->_ext);

        imagesetinterpolation($reso, IMG_BILINEAR_FIXED);
        $reso = imagescale($reso, $width, $height);

        return $reso;
    }

    private function saveImage($imageFile, $nameFile, $quality = 85)
    {
        $a = imagejpeg($imageFile, $nameFile . $this->_ext, $quality);
        imagedestroy($a);
        return $a;

    }

    public function generateName() : string
    {
	    return md5(rand(99999,9999999999));
    }




}