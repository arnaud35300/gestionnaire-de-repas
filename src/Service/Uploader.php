<?php

namespace App\Service;

use App\Entity\User;
use Intervention\Image\ImageManagerStatic as Image;

class Uploader
{
    const EXTENSIONS = ['jpg', 'jpeg', 'png', 'svg'];

    /**
     * Uploades an image within the public images folder.
     *  
     * @return array
     */
    public function upload(object $file, string $path, User $user,int $width = 800): array
    {
        $errors = array();

        if ($file->getError() > 0)
            $errors['upload'] = 'An error occurred while uploading the file.';

        if (!in_array($file->guessExtension(), self::EXTENSIONS))
            $errors['extension'] = 'Invalid extension. You can only upload .jpg, .jpeg, .svg and .png files.';

        if (count($errors) > 0)
            return $errors;

        $pathname = $file->getPathname();
        $filename = md5(uniqid()) . '.' . $file->guessExtension();

        $image = Image::make($pathname);
        
        $image->fit($width);
        $image->save($path . '/' . $filename);

        if ($user->getPath() !== 'user_profile.svg')
            unlink($path . '/' . $user->getPath());
            
        $user->setPath($filename);
        return $errors;
    }
}
