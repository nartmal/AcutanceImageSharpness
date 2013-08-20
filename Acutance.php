<?php

/*
 * A measure of image sharpness
 * REQUIRES GD IMAGE LIBRARY (its pretty standard)
 * http://en.wikipedia.org/wiki/Acutance
 */


class Acutance {
    /** @access public */
    public $_width;
    public $_height;
    /** @access private */
    private $intensity_mode;
    //we don't want this changed out of scope. We already have a setter.
    private $file_location = false;
    /** @const */
    private static $ALLOWED_METHODS = array('luminosity', 'average');
    private static $INTENSITY_SETTINGS = array('luminosity'=>array('r'=>.21, 'g'=>.71,'b'=>.07),
                                               'average'=>array('r'=>.33333,'g'=>.33333,'b'=>.33333)
                                         );
    public function __construct($file_location = false, $isUrl=false) {
        //keep it dry
        $this->setFileLocation($file_location, $isUrl);
        $this->setPixelIntensityMethod('luminosity');
    }

    public function setPixelIntensityMethod($method) {
        if (in_array($method, self::$ALLOWED_METHODS)) {
            $this->intensity_mode = $method;
        }
    }
    
    public function setFileLocation($file_location, $isUrl=false) {
        if ($file_location && $file_location !== null) {
            //if the string is a url then replaces spaces it to be safe for getimagesize and imagecratefromjpeg
            //else just leave it alone
            $this->file_location =  $isUrl?str_replace(' ', '%20', $file_location):$file_location;
        }
    }

    public function process() {
        if ($this->file_location){
            //Since getimagesize and imagecreatefromjpeg both return false if there is a error this can be used for error checking
            $size = getimagesize($this->file_location);
            $image = imagecreatefromjpeg($this->file_location);
            if($size && $image){
                $this->_width = $size[0];
                $this->_height = $size[1];
                return $this->findSharpness($image);
            }
        }
        return -1;
    }

    private function getRGB($im, $x, $y) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        return array("r" => $r, "g" => $g, "b" => $b);
    }

    private function getIntensity($rgb) {
        $mode = $this->intensity_mode;
        //I think this is better than a elseif, but I could be wrong.
        $intensity = (self::$INTENSITY_SETTINGS[$mode]['r']*$rgb['r'])+
                     (self::$INTENSITY_SETTINGS[$mode]['g']*$rgb['g'])+
                     (self::$INTENSITY_SETTINGS[$mode]['b']*$rgb['b']);
        return $intensity;

    }

    private function findSharpness($image) {
        $pixel_count = 0;
        $running_total = 0;
        for ($x = 1; $x < $this->_width - 2; $x++) {
            for ($y = 1; $y < $this->_height - 2; $y++) {
                $xy = $this->getIntensity($this->getRGB($image, $x, $y));

                $x1 = $this->getIntensity($this->getRGB($image, $x - 1, $y));
                $x2 = $this->getIntensity($this->getRGB($image, $x + 1, $y));
                $y1 = $this->getIntensity($this->getRGB($image, $x, $y - 1));
                $y2 = $this->getIntensity($this->getRGB($image, $x, $y + 1));

                $dx = (abs($x1 - $xy) + abs($x2 - $xy)) / 2;
                $dy = (abs($y1 - $xy) + abs($y2 - $xy)) / 2;

                $amplitude = sqrt(($dx * $dx) + ($dy * $dy));
                $pixel_count++;
                $running_total += $amplitude;
            }
        }
        if ($pixel_count > 0) {
            return ($running_total / $pixel_count);
        }
        //got rid of redundant else
        return -1; //error
    }
}
?>
