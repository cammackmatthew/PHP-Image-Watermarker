# PHP Image Watermarker
A light PHP image watermarker.

### About
A quick PHP project used to watermark user uploaded images, this project was produced when I wanted to learn how to use the GD libary within PHP. http://php.net/manual/en/ref.image.php

This is a lightweight project that doesn't use any databases and instead the images are uploaded into a local session.

### Known Bugs
1. There is in some cases depending on image, size, format and diminsions a issue where images do not upload correctly. 
2. Watermark positions are not dynamic to image size, and so if the same watermark is applied to different sized images it will not always position correctly.
3. The dropdown position options are also not dynamic to image size. 

### External Resources
This project uses the following external resources that I did not produce:
+ Materialize CSS Material Design framework: https://github.com/Dogfalo/materialize
+ jQuery: https://github.com/jquery/jquery 
