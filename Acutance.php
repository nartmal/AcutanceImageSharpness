<?php

/*
 * A measure of image sharpness
 * REQUIRES GD IMAGE LIBRARY (its pretty standard)
 * http://en.wikipedia.org/wiki/Acutance
 */


class Acutance {

    public $_width;
    public $_height;
    public $_image_set;
    public $_file_location;
    private $intensity_mode;

    public function __construct($file_location = false) {
        $this->_file_location = $file_location;
        if ($file_location !== false) {
            $this->_image_set = true;
        }
        $this->intensity_mode = 'luminosity';
    }

    public function setPixelIntensityMethod($method) {
        $allowed_values = array('luminosity', 'average');
        if (in_array($method, $allowed_values)) {
            $this->intensity_mode = $method;
        }
    }

    public function setFileLocation($file_location) {
        $this->_file_location = $file_location;
        if ($file_location !== false) {
            $this->_image_set = true;
        }
    }

    public function process() {
        if ($this->_image_set == true) {
            $size = getimagesize($this->_file_location);
            $this->_width = $size[0];
            $this->_height = $size[1];
            $this->_image_set = true;

            $image = imagecreatefromjpeg($this->_file_location);
            return $this->findSharpness($image);
        } else {
            return -1;
        }
    }

    private function getRGB($im, $x, $y) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        return array("r" => $r, "g" => $g, "b" => $b);
    }

    private function getIntensity($rgb) {
        if ($this->intensity_mode == 'average') {
            return (.33333 * $rgb['r']) + (.33333 * $rgb['g']) + (.33333 * $rgb['b']);
        } elseif ($this->intensity_mode == 'luminosity') {
            return (.21 * $rgb['r']) + (.71 * $rgb['g']) + (.07 * $rgb['b']);
        }
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
        } else {
            return -1; //error
        }
    }

}

?>
