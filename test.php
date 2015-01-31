<?php

include "Acutance.php";

function prettyDisplay($data){
    foreach($data as $type => $deltas){
        echo $type ."\n";
        foreach($deltas as $delta=>$value){
             echo "\t($delta):{".number_format($value,3)."}";
        }
        echo "\n";
    }
    echo "\n";
}


if(sizeof($argv) > 1){
    $file_location = $argv[1];
    prettyDisplay(Acutance::calculate($file_location));
}else{
    $testImages = array(
        "good" => array(
            "https://item5.tradesy.com/r/c10b21a760b906aff160b5d05803e0fff1ac0de4cc140c2676e49ebd0887cdc0/355/355/bags/coach/cross-body-bags/coach-croc-crocodile-cross-body-bag-1552354.jpg",
            "https://item2.tradesy.com/r/966ccd706644c7cd111b8007c5d190d85c3a244ad3557d3447812f59eaff0bab/355/355/bags/coach/cross-body-bags/coach-cross-body-bag-red-and-baby-blue-1580911.jpg",
            "https://item4.tradesy.com/r/c1d5f799d420d4b751e4f9a8cf1f97183c4f070391904c1dbf2a2b205df0fa1e/355/355/bags/coach/hobos/coach-cross-body-bag-navy-990018.jpg",
            "https://item2.tradesy.com/r/adbd579b2275c788b4738b122f4488e0a6f870b5106ee2be17d1fe71f4d8d3b8/355/355/bags/coach/os/coach-bag-satchel-1719786.jpg",
        ),
        "bad" => array(
            "https://item4.tradesy.com/r/c315d87e677bea787f51f0c479b431d2eb1725977eb4e5092178de01ab40bd5c/355/355/bags/coach/totes/coach-rarely-used-official-id-tags-tote-bag-brown-1726108.jpg",
            "https://item4.tradesy.com/r/18ff1c77eb8e6f97eb0860822d6f2bebfbd324f0c345ee361e311f3c760ce4f0/355/355/bags/coach/os/coach-black-travel-bag-1723468.jpg",
            "https://item3.tradesy.com/r/7ea799370a4f3966d1d870b3da65b86860edfdbdfdb8dd5a7527e2a4116ea68c/355/355/bags/coach/totes/coach-signature-c-f13742-tote-bag-black-1721272.jpg",
            "https://item4.tradesy.com/r/c8cd153aa7ea33eed329a7d30670ed461bbe0f68cdd40ad6cf4df9bd77db65d8/355/355/bags/coach/shoulder-bags/coach-shoulder-bag-1611893.jpg",
        ),
    );


    foreach($testImages as $bucketName => $bucketImages){
        echo $bucketName."\n";
        foreach($bucketImages as $image){
            echo $image."\n";
            prettyDisplay(Acutance::calculate($image));
        }
        echo "\n";
    }
}
