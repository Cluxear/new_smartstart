<?php

namespace ReclamationBundle\Controller;

use Doctrine\ORM\OptimisticLockException;
use ProduitBundle\Entity\Produit;
use ReclamationBundle\Entity\Reclamation;
use ReclamationBundle\Form\ReclamationSearchType;
use ReclamationBundle\Entity\Reponsereclamation;
use ReclamationBundle\Entity\ReponseTraite;
use ReclamationBundle\Entity\traitereclamation;
use ReclamationBundle\Form\Form;
use ReclamationBundle\Form\ReclamationType;
use ReclamationBundle\ReclamationBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReclamationController extends Controller
{

    public function AfficheAction()
    {


        $m = $this->getDoctrine()->getManager();
        $Users = $m->getRepository("UserBundle:User")->findAll();


        return $this->render('ReclamationBundle:Reclamtion:AfficheProduit.html.twig', array(
            'produit' => $Users
        ));
    }


    public function ReclamationAction($id, \Symfony\Component\HttpFoundation\Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $form = $this->createForm('ReclamationBundle\Form\ReclamationType', $reclamation);
        $form->handleRequest($request);
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $name = $user->getUsername();
        $reclamation->setIdClient($user);
        $date = (date('Y-m-d'));
        $reclamation->setDatereclamation($date);
        $reclamation->setStatusreclamation("En cours");

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($reclamation);
            $em->flush();
            $manager = $this->get('mgilet.notification');
            $notif = $manager->createNotification('Reclamation Notification !');
            $notif->setMessage("Vous avez une nouvelle Reclamation.");
            $notif->setLink('http://symfony.com/');


            // or the one-line method :
            // $manager->createNotification('Notification subject','Some random text','http://google.fr');

            // you can add a notification to a list of entities
            // the third parameter ``$flush`` allows you to directly flush the entities
            $admins = $query = $em->createQuery("SELECT a FROM UserBundle:User a WHERE a.roles LIKE '%ADMIN%'")->getResult();
            $manager->addNotification($admins, $notif, true);


            //var_dump($reclamation->getIdreclamation());exit();

            return $this->redirectToRoute('userdash', array('id' => $id));
        }

        return $this->render('ReclamationBundle:Reclamtion:Ajoutreclamation.html.twig', array(
            'reclamation' => $reclamation,
            'form' => $form->createView(),
            'name' => $name
        ));
    }

    public function AjoutAction($produit,$id,$d,$s, \Symfony\Component\HttpFoundation\Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();

        $user = $em->getRepository('UserBundle:User')->find($id);

        $produits = $em->getRepository('ProduitBundle:Produit')->find($produit);
        $reclamation->setIdclient($user);
        $reclamation->setProduit($produits);
        $reclamation->setDescriptionreclamation($d);
        $reclamation->setSujetreclamation($s);
        $date = (date('Y-m-d'));
        $reclamation->setDatereclamation($date);
        $reclamation->setStatusreclamation("En cours");


        try {
            $em = $this->getDoctrine()->getManager();

            $em->persist($reclamation);
            $em->flush();
            return new JsonResponse(array('info' => 'success'));
        }
        catch(Exception $e)
        {
            return new JsonResponse(array('info' => 'error'));

        }


    }
    public function detailsUserAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();


        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $reclamation = $em->getRepository('ReclamationBundle:Reclamation')->find(array("id" => $id));
        $reclamationReponse = $em->getRepository('ReclamationBundle:Reponsereclamation')->findOneBy(array("idreclamation" => $id));


        $recl = $em->getRepository('ReclamationBundle:Reclamation')->findBy(array("id_client" => $this->getUser()->getId()));


        $name = $user->getUsername();
        //var_dump($id);exit();


        return $this->render('ReclamationBundle:Reclamtion:detailsReclamationUser.html.twig', array(
            'reclamation' => $reclamation,
            'name' => $name,
            'reponse' => $reclamationReponse,
            'reclamations' => $recl
        ));
    }

    public function userDashAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $name = $user->getUsername();

        $recl = $em->getRepository('ReclamationBundle:Reclamation')->findBy(array('id_client' => $this->getUser()->getId()));
        return $this->render('ReclamationBundle:Reclamtion:AfficheReclamation1.html.twig', array(

            'name' => $name,
            'reclamations' => $recl

        ));
    }

    public function indexAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $reclamation = new Reclamation();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $reclamations = $em->getRepository('ReclamationBundle:Reclamation')->findAll();
       /* $notif = $this->get('mgilet.notification')->getAllUnseen();
        if ($request->isXmlHttpRequest()) {
            foreach ($notif as $n) {
                $nn = $this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif = $this->get('mgilet.notification')->getAllUnseen();
            $count = count($notif);
            return new JsonResponse($count);
        }
        $count = count($notif);*/
        $name = $user->getUsername();



        return $this->render('ReclamationBundle:Reclamtion:AfficheAdminReclamation.html.twig', array(
            'reclamations' => $reclamations,
            'name' => $name,
        //    'notif' => $notif,
        //    'count' => $count,
        ));
    }


    public function detailsAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();


        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $reclamation = $em->getRepository('ReclamationBundle:Reclamation')->find(array("id" => $id));
        $reclamationReponse = $em->getRepository('ReclamationBundle:Reponsereclamation')->findOneBy(array("idreclamation" => $id));
/*        $notif = $this->get('mgilet.notification')->getAllUnseen();
        if ($request->isXmlHttpRequest()) {
            foreach ($notif as $n) {
                $nn = $this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif = $this->get('mgilet.notification')->getAllUnseen();
            $count = count($notif);
            return new JsonResponse($count);
        }*/
     //   $count = count($notif);
        $name = $user->getUsername();


        return $this->render('@Reclamation/Reclamtion/detailsReclamation.html.twig', array(
            'reclamation' => $reclamation,

            'reponse' => $reclamationReponse,
       /*     'notif' => $notif,
            'count' => $count,*/
            'name' => $name,
        ));

    }

    public function detailsTraiteAction(\Symfony\Component\HttpFoundation\Request $request, $idtraite)
    {
        $em = $this->getDoctrine()->getManager();


        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $reclamationReponse = $em->getRepository('ReclamationBundle:ReponseTraite')->find(array("id" => $idtraite));

        $notif = $this->get('mgilet.notification')->getAllUnseen();
        if ($request->isXmlHttpRequest()) {
            foreach ($notif as $n) {
                $nn = $this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif = $this->get('mgilet.notification')->getAllUnseen();
            $count = count($notif);
            return new JsonResponse($count);
        }
        $count = count($notif);
        $name = $user->getUsername();

        return $this->render('ReclamationBundle:Reclamtion:AfficheReponseTraite.html.twig', array(
            'name' => $name,
            'reponse' => $reclamationReponse,
            'notif' => $notif,
            'count' => $count,
        ));

    }

    public function createAction(\Symfony\Component\HttpFoundation\Request $request, $idreclamation)
    {
        $em = $this->getDoctrine()->getManager();
        $reponse = new Reponsereclamation();
        $form = $this->createForm('ReclamationBundle\Form\ReponseReclamationForm', $reponse);
        $form->handleRequest($request);
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));


        $reclamation = $em->getRepository('ReclamationBundle:Reclamation')->find($idreclamation);

        $date = (date('Y-m-d'));
        $reponse->setdatereponse($date);
        $reponse->setIdadmin($user);
        $reponse->setIdreclamation($reclamation);
        $reclamation->setStatusreclamation("Traitée");

   /*     $notif = $this->get('mgilet.notification')->getAllUnseen();
        if ($request->isXmlHttpRequest()) {
            foreach ($notif as $n) {
                $nn = $this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif = $this->get('mgilet.notification')->getAllUnseen();
            $count = count($notif);
            return new JsonResponse($count);
        }
        $count = count($notif);*/
        $name = $user->getUsername();

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($reponse);
            $em->flush();


            //var_dump($reclamation->getIdreclamation());exit();

            return $this->redirectToRoute('reclamation_display');
        }

        return $this->render('@Reclamation/Reclamtion/createReponseReclamation.html.twig', array(
            'reclamation' => $reclamation,
            'form' => $form->createView(),
            'name' => $name,
         //   'notif' => $notif,
        //    'count' => $count,
        ));

    }


    public function deleteAction($idreclamation)
    {

        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository("ReclamationBundle:Reclamation")->find($idreclamation);
        $reclamation->setStatusreclamation("Archiver");
        $em->persist($reclamation);
        $em->flush();

        return $this->redirectToRoute('reclamation_display');
    }


    public function TraiterAction($id, $id1, \Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new traitereclamation();
        $form = $this->createForm('ReclamationBundle\Form\TraitereclamationType', $reclamation);
        $form->handleRequest($request);
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $name = $user->getUsername();
        $produit = $em->getRepository('ReclamationBundle:Reclamation')->find($id);
        $reclamation->setIdreclamation($produit);
        $produit->setStatusreclamation("En cours de traitement");
        $user1 = $em->getRepository('UserBundle:User')->find($id1);
        $reclamation->setStatustraite("En cours");
        $reclamation->setIdagent($user1);
        $reclamation->setIdadmin($user);
        $date = (date('Y-m-d'));
        $reclamation->setdatetraite($date);
        $notif = $this->get('mgilet.notification')->getAllUnseen();
        $count = count($notif);

        if ($request->isXmlHttpRequest()) {
            foreach ($notif as $n) {
                $nn = $this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif = $this->get('mgilet.notification')->getAllUnseen();
            $count = count($notif);
            return new JsonResponse($count);
        }




            if ($form->isSubmitted() && $form->isValid()) {

                $em->persist($reclamation);
                $em->flush();

                //var_dump($reclamation->getIdreclamation());exit();

                return $this->redirectToRoute('reclamation_display', array('id' => $id));
            }


            return $this->render('ReclamationBundle:Reclamtion:AjoutTraite.html.twig', array(
                'reclamation' => $reclamation,
                'form' => $form->createView(),
                'name' => $name,
                'notif' => $notif,
                'count' => $count,

            ));
            }




    public function TraiteafficheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $name = $user->getUsername();

        $recl = $em->getRepository('ReclamationBundle:traitereclamation')->findBy(array("idagent" => $this->getUser()->getId()));
        return $this->render('ReclamationBundle:Reclamtion:AffichereclamationAgent.html.twig', array(

            'name' => $name,
            'reclamations' => $recl

        ));
    }

    public function TraiterepondreAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reponse = new ReponseTraite();
        $form = $this->createForm('ReclamationBundle\Form\ReponseTraiteType', $reponse);
        $form->handleRequest($request);
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));


        $name = $user->getUsername();
        $reclamation = $em->getRepository('ReclamationBundle:traitereclamation')->find($id);

        $date = (date('Y-m-d'));
        $reponse->setdatereponse($date);
        $reponse->setIdtraite($reclamation);
        $reclamation->setStatustraite("Traitée");
        $reclamation->setStatustraite("Traitée");

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($reponse);
            $em->flush();


            //var_dump($reclamation->getIdreclamation());exit();

            return $this->redirectToRoute('agenttrite');
        }

        return $this->render('ReclamationBundle:Reclamtion:createReponsetraite.html.twig', array(
            'reclamation' => $reclamation,
            'form' => $form->createView(),
            'name' => $name
        ));


    }

    public function TraiteafficheadminAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $reclamations = $em->getRepository('ReclamationBundle:ReponseTraite')->findAll();
        $notif = $this->get('mgilet.notification')->getAllUnseen();
        if ($request->isXmlHttpRequest()) {
            foreach ($notif as $n) {
                $nn = $this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif = $this->get('mgilet.notification')->getAllUnseen();
            $count = count($notif);
            return new JsonResponse($count);
        }
        $count = count($notif);
        $name = $user->getUsername();

        return $this->render('ReclamationBundle:Reclamtion:AffichetraiteAdmin.html.twig', array(
            'reclamations' => $reclamations,
            'name' => $name,
            'notif' => $notif,
            'count' => $count,
        ));
    }

    public function detailsAgentAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();


        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));
        $reclamation = $em->getRepository('ReclamationBundle:traitereclamation')->find(array("id" => $id));
        $reclamationReponse = $em->getRepository('ReclamationBundle:ReponseTraite')->findOneBy(array("idtraite" => $id));


        $recl = $em->getRepository('ReclamationBundle:traitereclamation')->findBy(array("idagent" => $this->getUser()->getId()));


        $name = $user->getUsername();
        $produit = $em->getRepository('ProduitBundle:Produit')->find($id);
        //var_dump($id);exit();


        return $this->render('ReclamationBundle:Reclamtion:detailsTraiteAgent.html.twig', array(
            'reclamation' => $reclamation,
            'name' => $name,
            'produit' => $produit,
            'reponse' => $reclamationReponse,
            'reclamations' => $recl
        ));
    }

    public function produitABloquerAction(\Symfony\Component\HttpFoundation\Request$request)
    {

        $em = $this->getDoctrine()->getManager();

        $prod = $em->getRepository('ReclamationBundle:Reclamation')->findByProduit(33);

        $user = $em->getRepository('UserBundle:User')->find(array("id" => $this->getUser()->getId()));



        $query = "SELECT u.produit_id from Reclamation u GROUP By u.produit_id HAVING COUNT(u.produit_id) >= 2";

        $statement = $em->getConnection()->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll();
        $notif = $this->get('mgilet.notification')->getAllUnseen();

        if ($request->isXmlHttpRequest()) {
            foreach ($notif as $n) {
                $nn = $this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif = $this->get('mgilet.notification')->getAllUnseen();
            $count = count($notif);
            return new JsonResponse($count);
        }
        $count = count($notif);
        $name = $user->getUsername();


        foreach ($result as $r) {
            $prod = $prod = $em->getRepository('ProduitBundle:Produit')->findById($r);


        }


        return $this->render('ReclamationBundle:Reclamtion:ProduitAbloquer.html.twig', array(
            'prod' => $prod,
            'name' => $name,
            'notif' => $notif,
            'count' => $count,

        ));

    }

    public function bloquerProduitAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository("ProduitBundle:Produit")->find($id);
        $reclamation->setVisib(0);
        $em->persist($reclamation);
        $em->flush();

        return $this->redirectToRoute('produit_a_bloque');
    }


    public function MarkAsSeenAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notifiableEntity = $em->getRepository('MgiletNotificationBundle:NotifiableEntity')->findBy(array('identifier' => $this->getUser()->getId()));
        $notifications = $em->getRepository('MgiletNotificationBundle:NotifiableNotification')->findBy(array('notifiableEntity' => $notifiableEntity[0]->getId()));
        foreach ($notifications as $notification) {
            $notification->setSeen(1);
            $em->remove($notification);
        }
        $em->flush();
        return $this->redirectToRoute('reclamation_display');
    }


    function AffichAction()
    {
        $em = $this->getDoctrine()->getManager()->getRepository('ReclamationBundle:Reclamation') ->findAll();



           ;

        $serializer = new Serializer([new  ObjectNormalizer()]);



        $data = $serializer->normalize($em);
        return new JsonResponse($data);



    }


}