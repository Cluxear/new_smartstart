<?php

namespace PostsBundle\Controller;

use PostBundle\Entity\Demande;
use PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $posts = $this->getDoctrine()->getRepository('PostBundle:Post')->findAll();

        $postrech = $this->getDoctrine()->getRepository('PostBundle:Post')->findDQL("sssssss");
      //  var_dump($postrech);
        if ($request->isMethod('POST')) {
            $posts = $this->getDoctrine()->getRepository('PostBundle:Post')->findDQL($request->get('searsh'));
            $request->getSession()
                ->getFlashBag()
                ->add('success',count($posts).'results!');
            return $this->render('PostsBundle:Default:index.html.twig',array(
                'posts'=>$posts,
                'user'=>$user
            ));

        }

        //var_dump($user);

        return $this->render('PostsBundle:Default:index.html.twig',array(
            'posts'=>$posts,
            'user'=>$user
        ));
    }

    public function detailspostAction(Request $request,$id)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $post = $this->getDoctrine()->getRepository('PostBundle:Post')->find($id);

        $demandes = $this->getDoctrine()->getRepository('PostBundle:Demande')->findBy(
            ['idpost' => $post]
        );

        $dejaapply = $this->getDoctrine()->getRepository('PostBundle:Demande')->findBy(
            ['idpost' => $post,
                'user_id'=>$user
            ]
        );

        $apply = false;
        if($dejaapply!=null) {
            $apply=true;
        }




        //var_dump($dejaapply);

        $demande = new Demande();
        if ($request->isMethod('POST')) {
            $demande->setDescription($request->get('description'));
            $demande->setPrix($request->get('cout'));
            $demande->setDateRealisation(new \DateTime($request->get('date')));
            $demande->setStatut($request->get('state'));
            $demande->setUserId($user);
            $demande->setIdpost($post);

            $em = $this->getDoctrine()->getManager();
            //   var_dump($time);
            $em->persist($demande);
            $em->flush();

            //send messages tel
         //   $message = new \DocDocDoc\NexmoBundle\Message\Simple("SenderId", "21658832885", "content of your sms");
          //  $nexmoResponse = $this->container->get('doc_doc_doc_nexmo')->send($message);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Demande Job added successfully ...!');
            return $this->redirectToRoute('mes_demandes');
        }

        return $this->render('PostsBundle:Default:details_post.html.twig',array(
            'p'=>$post,
            'user'=>$user,
            'demandes'=>$demandes,
            'count'=>count($demandes),
            'apply'=>$apply
        ));
    }

    public function mesdemandesAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $posts = $this->getDoctrine()->getRepository('PostBundle:Demande')->findBy(
            ['user_id' => $user]
        );

        var_dump(count($posts));

        return $this->render('PostsBundle:Default:mesdemandes.html.twig',array(
            'posts'=>$posts
        ));
    }

    public function supprimerDemandeAction($id,Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $post = $this->getDoctrine()->getRepository('PostBundle:Demande')->find($id);
       // var_dump($forum);

        $em =$this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $request->getSession()
            ->getFlashBag()
            ->add('success', 'your demande was successfully deleted...!');

        return $this->redirectToRoute('mes_demandes');
    }


    public function updatePostAction(Request $request,$id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $post = $this->getDoctrine()->getRepository('PostBundle:Post')->find($id);

        if ($request->isMethod('POST')) {
            $post->setDescription($request->get('description'));
            $post->setTitrePost($request->get('title'));
            $post->setDatePost(new \DateTime($request->get('date')));
            $post->setCout($request->get('cout'));
            $post->setRating(120);
            $post->setDeadline($request->get('deadline'));
            $post->setTypePayemet($request->get('payement'));
            $post->setUserId($user);
            $em = $this->getDoctrine()->getManager();
            //   var_dump($time);
            $em->persist($post);
            $em->flush();

            //send messages tel
            //   $message = new \DocDocDoc\NexmoBundle\Message\Simple("SenderId", "21658832885", "content of your sms");
            //  $nexmoResponse = $this->container->get('doc_doc_doc_nexmo')->send($message);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Post Job updated successfully ...!');
            return $this->redirectToRoute('posts_homepage');
        }

        //var_dump(count($posts));

        return $this->render('PostsBundle:Default:updatePost.html.twig',array(
            'post' =>$post
        ));
    }

    public function supprimerPostAction($id,Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $post = $this->getDoctrine()->getRepository('PostBundle:Post')->find($id);
        // var_dump($forum);

        $em =$this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Your post was successfully deleted...!');

        return $this->redirectToRoute('posts_homepage');
    }

    public function addPostAction(Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();




        //var_dump($user);

        $post = new Post();
        if ($request->isMethod('POST')) {
            $post->setDescription($request->get('description'));
            $post->setCout($request->get('cout'));
            $post->setDatePost(new \DateTime('now'));
            $post->setTitrePost($request->get('title'));
            $post->setUserId($user);
            $post->setRating(5);
            $post->setDeadline($request->get('deadline'));
            $post->setTypePayemet($request->get('payement'));

            $em = $this->getDoctrine()->getManager();
            //   var_dump($time);
            $em->persist($post);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'You Post Job was added successfully ...!');
            return $this->redirectToRoute('posts_homepage');
        }


        return $this->render('PostsBundle:Default:addPost.html.twig',array(
            'user'=>$user
        ));
    }

    public function detailsdemandeAction(Request $request,$id)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $demande = $this->getDoctrine()->getRepository('PostBundle:Demande')->find($id);


        return $this->render('PostsBundle:Default:detailsdemande.html.twig',array(
            'demande'=>$demande
        ));
    }


    public function updateDemAction(Request $request,$id)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $demande = $this->getDoctrine()->getRepository('PostBundle:Demande')->find($id);



        //var_dump($user);


        if ($request->isMethod('POST')) {
            $demande->setDescription($request->get('description'));
            $demande->setPrix($request->get('price'));
            $demande->setDateRealisation(new \DateTime($request->get('date')));
            $demande->setStatut($request->get('state'));
           // $demande->setUserId($user);
            //$demande->setIdpost($post);

            $em = $this->getDoctrine()->getManager();
            //   var_dump($time);
            $em->persist($demande);
            $em->flush();

            //send messages tel
            //   $message = new \DocDocDoc\NexmoBundle\Message\Simple("SenderId", "21658832885", "content of your sms");
            //  $nexmoResponse = $this->container->get('doc_doc_doc_nexmo')->send($message);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Demande Job Edited successfully ...!');
            return $this->redirectToRoute('mes_demandes');
        }

        return $this->render('PostsBundle:Default:editdemande.html.twig',array(
            'd'=>$demande
            ));
    }


    public function evaluerAction(Request $request,$id)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $post = $this->getDoctrine()->getRepository('PostBundle:Post')->find($id);

        //var_dump($user);


        if ($request->isMethod('POST')) {
            $post->setRating($request->get('rating'));


            $em = $this->getDoctrine()->getManager();
            //   var_dump($time);
            $em->persist($post);
            $em->flush();

            //send messages tel
            //   $message = new \DocDocDoc\NexmoBundle\Message\Simple("SenderId", "21658832885", "content of your sms");
            //  $nexmoResponse = $this->container->get('doc_doc_doc_nexmo')->send($message);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Rate added successfully ...!');
            return $this->redirectToRoute('posts_homepage');
        }

        return $this->render('PostsBundle:Default:evaluer.html.twig',array(
            'p'=>$post,
            'user'=>$user
        ));
    }

}
