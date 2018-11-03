<?php

echo \Intervention\Image\ImageManagerStatic
    ::make($_FILES['image']['tmp_name'])
    ->trim('top-left', null, 20)
    ->resize(480, 670)
    ->response('jpg', 100);
