<?php


namespace App\EventListener;


use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Maintenance
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $router;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $pathName = $event->getRequest()->getPathInfo();

        $maintenance = $this->entityManager->getRepository(Setting::class)->findOneBy(['name'=>'maintenance']);
        if ($maintenance->getValue()== 'true'){

            if (str_contains($pathName, 'admin') || str_contains($pathName, 'maintenance') ){

            }else{
                $event->setResponse(new RedirectResponse($this->router->generate('maintenance')));

            }


        }else{
            if(str_contains($pathName, 'maintenance')){
                $event->setResponse(new RedirectResponse($this->router->generate('main')));

            }
        }



    }

}