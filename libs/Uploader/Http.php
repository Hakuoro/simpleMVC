<?php
namespace libs\Uploader;

class Http
{

	const UPLOAD_BASE_PATH = 'public/uploads/';
	const MAX_WITH = 320;
	const MAX_HEIGHT = 240;

	protected $requestData;

	protected $uploadBasePath;
	protected $fileInfo;
	protected $image = null;


	protected $validMimeTypes   = [IMAGETYPE_GIF => 'imagecreatefromgif', IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng'];
	protected $saveByTypes      = [IMAGETYPE_GIF => 'imagegif', IMAGETYPE_JPEG => 'imagejpeg', IMAGETYPE_PNG => 'imagepng'];
	protected $ext              = [IMAGETYPE_GIF => 'gif', IMAGETYPE_JPEG => 'jpeg', IMAGETYPE_PNG => 'png'];

	//[image] => Array ( [name] => test.jpg [type] => image/jpeg [tmp_name] => /tmp/phpffuDd0 [error] => 0 [size] => 62709 ) )
	public function __construct(array $requestData)
	{
		$this->requestData = $requestData;

		$this->uploadBasePath = BASE_PATH . DIRECTORY_SEPARATOR . self::UPLOAD_BASE_PATH;

		$this->fileInfo = getimagesize($this->requestData['tmp_name']);

	}


	protected function validateMimeType()
	{
		return isset($this->validMimeTypes[$this->fileInfo[2]]);
	}

	/**
	 * //формат JPG/GIF/PNG, не более 320х240 пикселей.
	 * @return bool
	 * @throws \Exception
	 */
	public function validate()
	{
		if (!empty($this->requestData['error'])) {
			return false;
		}

		if (!is_uploaded_file($this->requestData['tmp_name'])) {
			throw new \Exception('Posible hack! ' . var_export($this->requestData, true));
		}

		if (!$this->validateMimeType()) return false;

		return true;
	}

	public function createImage()
	{
		$createFunction = $this->validMimeTypes[$this->fileInfo[2]];

		$this->image = $createFunction($this->requestData['tmp_name']);

		if ($this->needResize()){
			$this->image = $this->resize();
		}
	}

	public function saveImage($filename)
	{
		$filename = $filename . '.' . $this->ext[$this->fileInfo[2]];
		$saveFunction = $this->saveByTypes[$this->fileInfo[2]];
		$saveFunction($this->image, $filename);
		return $this->ext[$this->fileInfo[2]];
	}


	protected function needResize(){
		return ($this->getHeight() > self::MAX_HEIGHT || $this->getWidth() > self::MAX_WITH);
	}


	protected function getType()
	{
		return $this->fileInfo[2];
	}

	protected function getHeight()
	{
		return $this->fileInfo[1];
	}

	protected function getWidth()
	{
		return $this->fileInfo[0];
	}


	protected function resize()
	{
		if ($this->getHeight() > $this->getWidth()) {
			$ratio = self::MAX_HEIGHT / $this->getHeight();
		}else {
			$ratio = self::MAX_WITH / $this->getWidth();
		}

		$newHeight  = $this->getHeight() * $ratio;
		$newWith    = $this->getWidth() * $ratio;

		$newImage = imagecreatetruecolor($newWith, $newHeight);
		imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newWith, $newHeight, $this->getWidth(), $this->getHeight());
		return $newImage;

	}
}