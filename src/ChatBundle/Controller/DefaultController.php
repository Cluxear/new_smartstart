<?php

namespace ChatBundle\Controller;

use AppBundle\Entity\FosUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $threads = array();
        $idT = 0;
        $provider = $this->container->get('fos_message.provider');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ENTREPRISE')){
        $threads = $provider->getSentThreads();
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FREELANCER')){
            $threads = $provider->getInboxThreads();
        }
        if(sizeof($threads)!=0){
            $idT=$threads[0]->getId();
        }

        return $this->render('@Chat/Default/index.html.twig', array('threads'=>$threads, 'idT'=>$idT));
    }
    public function sendAction(Request $request)
    {
        $sender=$this->container->get('security.token_storage')->getToken()->getUser();
        $provider = $this->container->get('fos_message.provider');
        $thread = $provider->getThread($request->request->get('idT'));
        $composer = $this->container->get('fos_message.composer');
        $message = $composer->reply($thread)
            ->setSender($sender)
            ->setBody($request->request->get('msg'))
            ->getMessage();
        $send = $this->container->get('fos_message.sender');
        $send->send($message);
    }
    public function getInitMessagesAction(Request $request){

        $provider = $this->container->get('fos_message.provider');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ENTREPRISE')){
            $threads = $provider->getSentThreads();
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FREELANCER')){
            $threads = $provider->getInboxThreads();
        }
        if (sizeof($threads)!=0){
        $messages=$threads[0]->getMessages();
        }

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = array();
            $idx = 0;
            if (sizeof($messages)!=0){
            foreach($messages as $msg) {
                $data = array(
                    'body' => $msg->getBody(),
                    'sender' => $msg->getSender()->getId(),
                );
                $jsonData[$idx++] = $data;
            }
            return new JsonResponse($jsonData);
            }
            else{
                $jsonData[0] = array(
                    'body' => "you don't have any message",
                    'sender' => 0,
                );
                return new JsonResponse($jsonData);
            }
        }
    }
    public function changeAction(Request $request){

        $provider = $this->container->get('fos_message.provider');
        $thread = $provider->getThread($request->request->get('id'));
        $messages=$thread->getMessages();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $json = array();
            $idx = 0;
            foreach($messages as $msg) {
                $data = array(
                    'body' => $msg->getBody(),
                    'sender' => $msg->getSender()->getId(),
                );
                $json[$idx++] = $data;
            }
            return new JsonResponse($json);
        }
    }
    public function participantAction(Request $request){
        $jsonData = array();
        $user=new FosUser();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $provider = $this->container->get('fos_message.provider');
        $thread = $provider->getThread($request->request->get('idT'));
        $participants = $thread->getParticipants();
        foreach($participants as $p){
            if ($p->getId()!=$user->getId()){

                $jsonData[0]=array('user' => $p->getName()." ".$p->getLastName()." (".$p->getUsername().")");

            }
        }
        return new JsonResponse($jsonData);
    }
    public function createAction(Request $request){
        $provider = $this->container->get('fos_message.provider');
        $threads = $provider->getSentThreads();
        $test = true;
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(FosUser::class)->find($request->request->get('idUser'));
        foreach ($threads as $t){
            $part = $t->getParticipants();
            foreach ($part as $p){
                if($p == $user){
                    $composer = $this->container->get('fos_message.composer');
                    $message = $composer->reply($t)
                        ->setSender($this->container->get('security.token_storage')->getToken()->getUser())
                        ->setBody($request->request->get('msg'))
                        ->getMessage();
                    $send = $this->container->get('fos_message.sender');
                    $send->send($request->request->get('message'));
                    $test = false;
                    break;
                }
            }
        }
        if($test==true){
            $composer = $this->container->get('fos_message.composer');
            $message = $composer->newThread()
                ->setSender($this->container->get('security.token_storage')->getToken()->getUser())
                ->addRecipient($user)
                ->setSubject('Hi')
                ->setBody($request->request->get('message'))
                ->getMessage();
        }
        return $this->redirectToRoute('opportunity_homepage');
    }
}
