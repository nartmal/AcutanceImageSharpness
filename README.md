AcutanceImageSharpness
======================

Image Sharpness By Method of Acutance
By Lam Tran


http://en.wikipedia.org/wiki/Acutance



Description:
Image sharpness is a difficult measure. Typical methods use Fourier Analysis, 
applying some sort of convolution sometimes and then doing statistics. 

I found using Acutance to provide some decent broad benchmarks on how sharp an image is. It comes in handy when your comparing images with completely different content

It is also far easier algorithmically, providing some sort of performance gain 
(however using php kind of erases that)


HOW TO USE:

$class = new Acutance($file_location);//urls work but you have to beware of DNS/host file issues

$result = $class->process();


EXAMPLES

GOOD:
http://item2.tradesy.com/images/item/2/bags/louis-vuitton/shoulder-bags/louis-vuitton-lv-tan-travel-bag-284601-1.jpg
SCORE: 12.757910021537

http://item4.tradesy.com/images/item/2/bags/louis-vuitton/shoulder-bags/louis-vuitton-shoulder-bag-brown-263968-1.jpg
SCORE: 12.148350570582

POOR:
http://item5.tradesy.com/images/item/2/bags/louis-vuitton/shoulder-bags/louis-vuitton-hudson-shoulder-bag-noisette-29044-1.jpg
SCORE: 8.1124040870854

http://item4.tradesy.com/images/item/2/bags/coach/shoulder-bags/coach-shoulder-bag-brown-192328-1.jpg
6.9740951409763

TODO:

Exception Catching<br>
Composer Package?<br>

