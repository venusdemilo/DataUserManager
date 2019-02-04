<?php
namespace App\EventListener;

// for Doctrine < 2.4: use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\User;

class StringToJson
{
  public function postUpdate(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    if (!$entity instanceof User){
        return;
    }
    $em = $args->getObjectManager();
    //$var = json_decode(["ROLE_CACA","ROLE_PIPI"]);
    $var = ['ROLE_CACA','ROLE_PIPI'];
    $entity->setRoles($var);
  }
}
