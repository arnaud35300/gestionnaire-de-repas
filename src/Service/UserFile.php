<?php

namespace App\Service;

use App\Entity\User;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserFile
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;    
    }

    /**
     *  
     * @return boolean
     */
    public function delete(User $user = null): bool 
    {
        if ($user === null)
            return false;

        if ($user->getPath() !== 'user_profile.svg')
            unlink($this->params->get('kernel.project_dir') . '/public/data/user_profile_pictures' . '/' . $user->getPath());
    
        return true;
    }
}
