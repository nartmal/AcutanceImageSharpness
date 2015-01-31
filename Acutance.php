<?php

/*
 * A measure of image sharpness
 * REQUIRES GD IMAGE LIBRARY (its pretty standard)
 * http://en.wikipedia.org/wiki/Acutance
 */


class Acutance {
    
    const LUMINOSITY = 1;
    const RGB_AVERAGE = 2;

    public static function calculate($fileLocation, $deltas = array(1,2,3), $greyScaleMode = 1){
        $size = getimagesize($fileLocation);
        $image = imagecreatefromjpeg($fileLocation);

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
                        $left = self::getIntensity(imagecolorat($image, $x - $delta, $y), $greyScaleMode);
                        $right =self::getIntensity( imagecolorat($image, $x + $delta, $y), $greyScaleMode);
                        $top = self::getIntensity(imagecolorat($image, $x, $y + $delta), $greyScaleMode);
                        $bottom = self::getIntensity(imagecolorat($image, $x, $y - $delta), $greyScaleMode);

                        $dx = (float)($left - $right)/(2.*$delta);
                        $dy = (float)($bottom - $top)/(2.*$delta);

                        $sum["x"][$delta] += abs($dx);
                        $sum["y"][$delta] += abs($dy);
                        $sum["d"][$delta] += sqrt(($dx*$dx) + ($dy*$dy));               
 
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
            $returnArray["dx_avg_amplitude"][] = (float)$sum["x"][$delta]/$counter["x"][$delta];
            $returnArray["dy_avg_amplitude"][] = (float)$sum["y"][$delta]/$counter["y"][$delta];
            $returnArray["acutance"][] = (float)$sum["d"][$delta]/$counter["d"][$delta];
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
            default:
                throw new \Exception("invalid greyscale mode!");
        }

    }



}

