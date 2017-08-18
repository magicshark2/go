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
class AdminController extends Controller
{
    /**
     * @Route("/admin/user/list", name="list_user")
     */
    public function indexAction(Request $request)
    { 
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();
        return $this->render('Admin/listuser.html.twig', array("users"=>$users));
    }

}
