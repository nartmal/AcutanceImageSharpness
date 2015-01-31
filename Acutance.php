<?php

/*
 * A measure of image sharpness
 * REQUIRES GD IMAGE LIBRARY (its pretty standard)
 * http://en.wikipedia.org/wiki/Acutance
 */


class Acutance {
    
    const LUMINOSITY = 1;
    const RGB_AVERAGE = 2;
    const RGB_MAX = 3;

    public static function calculate($fileLocation, $deltas = array(1,2,3,4), $greyScaleMode = 2, $blur = false, $thresholding = 10.){
        $size = getimagesize($fileLocation);
        $image = imagecreatefromjpeg($fileLocation);
        
        if($blur === true){
            //$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
            //imageconvolution($image, $gaussian, 16, 0);
            imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
        }

        $width = $size[0];
        $height = $size[1];

        $sum = array("x"=>array(),"y"=>array());
        $counter = array("x"=>array(),"y"=>array());

        foreach($deltas as $delta){
            $sum["x"][$delta] = 0;
            $sum["y"][$delta] = 0;
            $sum["d"][$delta] = 0;
            $counter["x"][$delta] = 0;
            $counter["y"][$delta] = 0;
            $counter["d"][$delta] = 0;
        }

        for($x = 1; $x < $width; $x++){
            for($y = 1; $y < $height; $y++){

                foreach($deltas as $delta){
                    //truncate calculations if its too close to border
                    if($x + $delta < $width 
                        && $x - $delta > 0 
                        && $y + $delta < $height 
                        && $y - $delta > 0){
                        
                        //$rgb = imagecolorat($image, $x, $y);

                        /*
                        //FIRST ORDER
                        $left = self::getIntensity(imagecolorat($image, $x - $delta, $y), $greyScaleMode);
                        $right =self::getIntensity( imagecolorat($image, $x + $delta, $y), $greyScaleMode);
                        $top = self::getIntensity(imagecolorat($image, $x, $y + $delta), $greyScaleMode);
                        $bottom = self::getIntensity(imagecolorat($image, $x, $y - $delta), $greyScaleMode);

                        $dx = (float)($left - $right)/(2.*$delta);
                        $dy = (float)($bottom - $top)/(2.*$delta);

                        */


                        /*
                        //PREWITT EDGE DETECTION TEMPLATE
                        $w = self::getIntensity(imagecolorat($image, $x - $delta, $y), $greyScaleMode);
                        $e =self::getIntensity( imagecolorat($image, $x + $delta, $y), $greyScaleMode);
                        $n = self::getIntensity(imagecolorat($image, $x, $y + $delta), $greyScaleMode);
                        $s = self::getIntensity(imagecolorat($image, $x, $y - $delta), $greyScaleMode);
                        
                        $nw = self::getIntensity(imagecolorat($image, $x - $delta, $y + $delta), $greyScaleMode);
                        $sw =self::getIntensity( imagecolorat($image, $x - $delta, $y - $delta), $greyScaleMode);
                        $ne = self::getIntensity(imagecolorat($image, $x + $delta, $y + $delta), $greyScaleMode);
                        $se = self::getIntensity(imagecolorat($image, $x + $delta, $y - $delta), $greyScaleMode);


                        $dx = (float)(($nw+$w+$sw) - ($ne+$e+$se))/(2.*$delta);
                        $dy = (float)(($nw+$n+$ne) - ($sw+$s+$sw))/(2.*$delta);
                        */


                        ///*
                        //SOBEL
                        $w = self::getIntensity(imagecolorat($image, $x - $delta, $y), $greyScaleMode);
                        $e = self::getIntensity( imagecolorat($image, $x + $delta, $y), $greyScaleMode);
                        $n = self::getIntensity(imagecolorat($image, $x, $y + $delta), $greyScaleMode);
                        $s = self::getIntensity(imagecolorat($image, $x, $y - $delta), $greyScaleMode);

                        $nw = self::getIntensity(imagecolorat($image, $x - $delta, $y + $delta), $greyScaleMode);
                        $sw = self::getIntensity( imagecolorat($image, $x - $delta, $y - $delta), $greyScaleMode);
                        $ne = self::getIntensity(imagecolorat($image, $x + $delta, $y + $delta), $greyScaleMode);
                        $se = self::getIntensity(imagecolorat($image, $x + $delta, $y - $delta), $greyScaleMode);


                        $dx = .18 * (float)(($nw+(2*$w)+$sw) - ($ne+(2*$e)+$se))/(2.*$delta);
                        $dy = .18 * (float)(($nw+(2*$n)+$ne) - ($sw+(2*$s)+$sw))/(2.*$delta);
                        //*/

                

                        /*
                        //LOG SOBEL 
                        $e = self::getIntensity( imagecolorat($image, $x + $delta, $y), $greyScaleMode);
                        $n = self::getIntensity(imagecolorat($image, $x, $y + $delta), $greyScaleMode);
                        $s = self::getIntensity(imagecolorat($image, $x, $y - $delta), $greyScaleMode);
                        
                        $nw = self::getIntensity(imagecolorat($image, $x - $delta, $y + $delta), $greyScaleMode);
                        $sw =self::getIntensity( imagecolorat($image, $x - $delta, $y - $delta), $greyScaleMode);
                        $ne = self::getIntensity(imagecolorat($image, $x + $delta, $y + $delta), $greyScaleMode);
                        $se = self::getIntensity(imagecolorat($image, $x + $delta, $y - $delta), $greyScaleMode);


                        $dx = (float)(($nw+(2*$w)+$sw) - ($ne+(2*$e)+$se))/(2.*$delta);
                        $dy = (float)(($nw+(2*$n)+$ne) - ($sw+(2*$s)+$sw))/(2.*$delta);
                        */

                
                        //echo ((abs($dx) > $thresholding) ? abs($dx) : 0.) . "\t" . ((abs($dy) > $thresholding) ? abs($dy) : 0.) . "\n";


                        $sum["x"][$delta] += (abs($dx) > $thresholding) ? abs($dx) : 0.;
                        $sum["y"][$delta] += (abs($dy) > $thresholding) ? abs($dy) : 0.;
                        $d = sqrt(($dx*$dx) + ($dy*$dy));               
                        $sum["d"][$delta] += ($d > $thresholding) ? $d : 0.; 



                        $counter["x"][$delta]++;
                        $counter["y"][$delta]++; //because im too lazy to pre-calculate from dimensions
                        $counter["d"][$delta]++;

                    }


                }

            }
        }
        

        $returnArray = array(
            "dx_avg_amplitude" => array(),
            "dy_avg_amplitude" => array(),
            "acutance" => array(),
        );


        foreach($deltas as $delta){
            $returnArray["dx_avg_amplitude"][] = ($counter["x"][$delta] > 0.) ? (float)$sum["x"][$delta]/$counter["x"][$delta] : 0.;
            $returnArray["dy_avg_amplitude"][] = ($counter["y"][$delta] > 0.) ? (float)$sum["y"][$delta]/$counter["y"][$delta] : 0.;



            $returnArray["acutance"][] = ($counter["d"][$delta] > 0.) ? (float)$sum["d"][$delta]/$counter["d"][$delta] : 0.;
        }


        return $returnArray;


    }

    private static function getIntensity($rgb,$mode){
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        switch($mode){
            case self::LUMINOSITY:
                return (.21*$r) + (.71*$g) + (.07*$b);
            case self::RGB_AVERAGE:
                return (.333*$r) + (.333*$g) + (.333*$b);
            case self::RGB_MAX:
                return max($r,$g,$b);
            default:
                throw new \Exception("invalid greyscale mode!");
        }

    }



}

