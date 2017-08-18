<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\News;
use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Form\NewsType;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    { 
        $em = $this->getDoctrine()->getManager();
        $new = new News();
        $form = $this->createForm(NewsType::class, $new);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em->persist($new);
            $em->flush();
            if($new->getId() != null) {
                $this->addFlash("success", "Enregistrement ok");
            } else {
                $this->addFlash("error", "error");
            }
            
        }
        
        return $this->render('default/index.html.twig', array(
            'news' =>$em->getRepository(News::class)->findAll(),
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/addGroupPerso", name="homepage2")
     */
    public function homeAction(Request $request)
    { 
        $em = $this->getDoctrine()->getManager();
        $groupAdmin = new Group('Administrateur');
        $groupAdmin->addRole('ROLE_ADMIN');
        
        $groupDirecteur = new Group('Directeur Général');
        $groupDirecteur->addRole('ROLE_DIRECTEUR');
        
        $em->persist($groupAdmin);
        $em->persist($groupDirecteur);
        $em->flush();

        $user1 = $em->getRepository(User::class)->find(1);
        $user2 = $em->getRepository(User::class)->find(2);

        $user1->addGroup($groupDirecteur);
        $user2->addGroup($groupAdmin);
        $em->persist($user1);
        $em->persist($user2);
        $em->flush();

        return $this->render('default/index.html.twig', array(
            'news' =>$em->getRepository(News::class)->findAll(),
            'form' => $this->createForm(NewsType::class, new News())->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="default_delete")
     */
    public function deleteAction(Request $request,News $news)
    { 
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();
        $newRemove = $em->getRepository(News::class)->findOneById($news->getId());
        if($newRemove == null) {
            $this->addFlash("success", "Suppression ok");
        } else {
            $this->addFlash("error", "error");
        }
        return $this->render('default/index.html.twig', array(
            'form' =>$this->createForm(NewsType::class, new News())->createView(),
            'news' =>$em->getRepository(News::class)->findAll(),
        ));
    }

}
