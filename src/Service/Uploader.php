<?php

namespace App\Service;

use App\Entity\User;
use App\Service\UserFile;
use Intervention\Image\ImageManagerStatic as Image;

class Uploader
{
    const EXTENSIONS = ['jpg', 'jpeg', 'png', 'svg'];

    private $userFile;

    public function __construct(UserFile $userFile )
    {
        $this->userFile = $userFile;
    }
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

        // delete last profile picture
        $this->userFile->delete($user);
            
        $user->setPath($filename);
        return $errors;
    }
}
