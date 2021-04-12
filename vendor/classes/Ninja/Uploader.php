<?php
namespace Ninja;

class Uploader
{
    /**
     * Allow image type
     */
    private $imageTypes = array('png', 'jpg', 'gif', 'jpeg');
    private $imageSizes = array(200, 920);
    /**
     * Allowed File types
     */
    private $fileTypes = array(
        'doc',
        'xml',
        'exe',
        'txt',
        'zip',
        'rar',
        'doc',
        'mp3',
        'jpg',
        'png',
        'css',
        'psd',
        'pdf',
        '3gp',
        'ppt',
        'pptx',
        'xls',
        'xlsx',
        'html',
        'docx',
        'fla',
        'avi',
        'mp4',
        'swf',
        'ico',
        'gif',
        'webm',
        'jpeg',
        'wav',
        'csv',
    );

    /**
     * Allowed video types
     */
    private $videoTypes = array('mp4');
    private $audioTypes = array('mp3');
    private $sourceFile;
    public $source;
    public $sourceName;
    public $sourceSize;
    public $extension;
    public $destinationPath;
    public $destinationName;
    public $baseDir;
    public $fileName;

    private $dbType;
    private $dbTypeId;
    private $type;

    //max sizes
    private $maxFileSize = 10000000;
    private $maxImageSize = 10000000;
    private $maxVideoSize = 10000000;
    private $maxAudioSize = 10000000;

    private $error = false;
    private $errorMessage;
    public $result;
    public $insertedId;

    /**
     * @param $source
     * @param string $type
     */
    public function __construct($source, $type = "image")
    {
        $this->source = $source;
        $this->type = $type;
        $this->imageTypes = explode(',', 'jpg,png,gif,jpeg,pjpeg');
        $this->videoTypes = explode(',', 'mp4,mov,wmv,3gp,avi,flv,f4v,webm');
        $this->audioTypes = explode(',', 'mp3,m4a,mp4,acc,wav');
        $this->fileTypes = explode(',', 'doc,xml,exe,txt,zip,rar,mp3,jpg,png,css,psd,pdf,3gp,ppt,pptx,xls,xlsx,html,docx,fla,avi,mp4,swf,ico,gif,jpeg,webm');

        if ($source and $this->source['size'] != 0) {
            $this->sourceFile = $this->source['tmp_name'];
            $this->sourceSize = $this->source['size'];
            $this->sourceName = $this->source['name'];
            $name = pathinfo($this->sourceName);

            if (isset($name['extension'])) {
                $this->extension = strtolower($name['extension']);
                $this->confirmFile();
            }

        } else {

            $this->error = true;
            $this->errorMessage = l("failed-to-upload-file");

        }


        //confirm the creation of uploads directory
        if (!is_dir(path('//uploads/'))) {
            @mkdir(path('//uploads/'), 0777, true);
            $file = @fopen(path('//uploads/index.html'), 'x+');
            fclose($file);
        }

    }

    public function setFileTypes($types)
    {
        $this->fileTypes = $types;
        return $this;
    }

    public function noThumbnails()
    {
        $this->imageSizes = array(600, 920);
        return $this;
    }

    /**
     * Method to get the image width
     * @return null
     */
    public function getWidth()
    {
        list($width, $height) = getimagesize($this->sourceFile);
        return ($width) ? $width : null;
    }

    /**
     * Method to get the image height
     * @return int
     */
    public function getHeight()
    {
        list($width, $height) = getimagesize($this->sourceFile);
        return ($height) ? $height : null;
    }

    public function confirmFile()
    {
        switch ($this->type) {
            case 'image':
                if (!in_array($this->extension, $this->imageTypes)) {
                    $this->errorMessage = l("upload-file-not-valid-image");
                    $this->error = true;
                }
                if ($this->sourceSize > $this->maxImageSize) {
                    $this->errorMessage = l("upload-image-size-error " . format_bytes($this->maxImageSize));
                    $this->error = true;
                }
                break;
            case 'video':
                if (!in_array($this->extension, $this->videoTypes)) {
                    $this->errorMessage = l("upload-file-not-valid-video");
                    $this->error = true;
                }
                if ($this->sourceSize > $this->maxVideoSize) {
                    $this->errorMessage = l("upload-video-size-error " . format_bytes($this->maxVideoSize));
                    $this->error = true;
                }
                break;
            case 'audio':
                if (!in_array($this->extension, $this->audioTypes)) {
                    $this->errorMessage = l("upload-file-not-valid-audio");
                    $this->error = true;
                }
                if ($this->sourceSize > $this->maxAudioSize) {
                    $this->errorMessage = l("upload-audio-size-error" . format_bytes($this->maxAudioSize));
                    $this->error = true;
                }
                break;
            case 'file':
                if (!in_array($this->extension, $this->fileTypes)) {
                    $this->errorMessage = l("upload-file-not-valid-file");
                    $this->error = true;
                }

                if ($this->sourceSize > $this->maxFileSize) {
                    $this->errorMessage = l("upload-file-size-error " . format_bytes($this->maxFileSize));
                    $this->error = true;
                }
                break;
        }
    }

    /**
     * Function to confirm file passes
     */
    public function passed()
    {
        return !$this->error;
    }

    /**
     * Function to set destination
     */

    public function setPath($path)
    {
        $this->baseDir = "//uploads/" . $path;
        $path = path("//uploads/") . $path;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            //create the index.html file
            if (!file_exists($path . 'index.html')) {
                $file = fopen($path . 'index.html', 'x+');
                fclose($file);
            }
        }
        $this->destinationPath = $path;
        return $this;
    }

    public function getDestination()
    {
        return $this->destinationPath;
    }

    public function getDestinationFolder(){
        return  $this->baseDir;
    }

    public function uploadProfilePicture()
    {

        $this->uploadFile();
        $uploadedFile = $this->destinationPath . $this->fileName;
        list($w, $h) = getimagesize($uploadedFile);

        $max = 200;
        $tw = $w;
        $th = $h;

        if ($w > $h && $max < $w) {
            $th = $max / $w * $h;
            $tw = $max;
        } elseif ($h > $w && $max < $h) {
            $tw = $max / $h * $w;
            $th = $max;
        } elseif ($max < $w) {
            $tw = $th = $max;
        }

        $tmp = imagecreatetruecolor($tw, $th);
        $from = $this->extension == 'jpg' ? 'jpeg' : $this->extension;
        $create = "imagecreatefrom" . $from;
        $src = $create($uploadedFile);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imageconvolution($tmp, array(array(-1, -1, -1),
            array(-1, 16, -1), array(-1, -1, -1)), 8, 0);

        $imageSave = "image" . $from;
        $imageSave($tmp, $uploadedFile);
        imagedestroy($tmp);
        imagedestroy($src);

        return $this;
    }

    /**
     *Function to resize image
     * @param int $width
     * @param int $height
     * @param string $fit
     * @param string $any
     * @return $this
     */
    public function resize($width = null, $height = null, $fit = "inside", $any = "down")
    {
        if ($this->error) {
            return false;
        }

        $fileName = md5($this->sourceName . time()) . '.' . $this->extension;
        $fileName = (!$width) ? '_%w_' . $fileName : '_' . $width . '_' . $fileName;

        $this->result = $this->baseDir . $fileName;

        if ($width) {
            $this->finalizeResize($fileName, $width, $height, $fit, $any);
        } else {
            foreach ($this->imageSizes as $size) {
                $this->finalizeResize(str_replace('%w', $size, $fileName), $size, $size, $fit, $any);
            }
        }

        return $this;
    }

    /**
     * Function to get result
     * @return string
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * Function to upload video
     */
    public function uploadVideo()
    {
        return $this->directUpload();
    }

    /**
     * function to upload file
     */
    public function uploadFile()
    {
        return $this->directUpload();
    }

    protected function directUpload()
    {
        if ($this->error) {
            return false;
        }

        $this->fileName = md5($this->sourceName . time()) . "." . $this->extension;
        $this->result = $this->baseDir . $this->fileName;

        move_uploaded_file($this->sourceFile, $this->destinationPath . $this->fileName);
        return $this;
    }

    public function getError()
    {
        return $this->errorMessage;
    }

    public static function isImage($file)
    {
        
        $name = (isset($file['type'])) ? $file['type'] : false;
        if (!$name and $file) {
            $name = $file;
        }

        if ($name) {
            $name = strtolower($name);
            foreach (array('png', 'jpg', 'gif', 'jpeg', 'pjpeg') as $type) {
                if (preg_match("#/$type#", $name)) {
                    return true;
                }

            }
        }
        return false;
    }
}
