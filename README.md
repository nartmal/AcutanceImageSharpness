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


$result = Acutance::calculate>process();


EXAMPLES (first order, with delta 1)

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





Included:


Added more ways of estimating the image gradient that is less sensitive to noise (Sobel + PreWitt)

Added Thresholding to reduce Noise

Added Blur if you want to use that as a pre-processing step to further reduce noise



Disclaimer:


Relying image sharpness/contrast alone is a poor metric for overall subjective image quality. In conjunction with somee nice object segementation and some knowledge of the object at hand + a large annotated training database, I have found with a proprietary dataset and some machine learning algorithm (like logistic regression or RandomForests), you can train a relatively decent classifier but mileage my vary. This is only written in PHP for novelty purposes, please use a seperate language in production if possible (like Python!).

