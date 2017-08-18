<?php

namespace AppBundle\Controller;
use AppBundle\Entity\News;
use AppBundle\Form\NewsType;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {

   
    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return Response
     */
    protected function renderLogin(array $data)
    {
        if(array_key_exists("error",$data) && isset($data['error'])) {
           // return $this->redirectToRoute('homepage');
                   return $this->render('default/index.html.twig', array_merge(array(
            'form' =>$this->createForm(NewsType::class, new News())->createView(),
            'news' =>$this->getDoctrine()->getManager()->getRepository(News::class)->findAll(),
        ),$data));
        } else {
            return $this->render('@FOSUser/Security/login.html.twig', $data);
        }
    }
}