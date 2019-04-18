<?php

namespace CertificationBundle\Controller;

use CertificationBundle\Entity\Notification;
use CertificationBundle\Entity\Test;
use CertificationBundle\Entity\UsersTests;
use CertificationBundle\form\TestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Tests\User\UserTest;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/testslist", name="testslist")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $testsForUser = $this->getDoctrine()->getManager()->getRepository(Test::class)->findTestsByUserIdWhereStatusIsActive($user->getId());
        if(!empty($testsForUser)){

            $trialsLeft = $this->getDoctrine()->getManager()->getRepository(UsersTests::class)->getNbrEssaieAndIdTest($user->getId());
            /**
             * @var $paginator \Knp\Component\Pager\Paginator
             */
            $paginator = $this->get('knp_paginator');
            $result = $paginator->paginate(
                $testsForUser,
                $request->query->getInt('page', 1),
                5
            );
            return $this->render('@Certification/testslist.html.twig',array('tests' => $result, 'trials'  => $trialsLeft ));
        }

        return $this->render('@Certification/testslist.html.twig');
    }

    /**
     * @Route("/exam_page/{id}", name="exam_page")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderExam($id, Request $request){
        /**
         * @var $test Test
         */
        $user = $this->getUser();
        $test = $this->getDoctrine()->getRepository(Test::class)->find($id);
        /**
         * @var $UserTestAffectation UsersTests
         */
        $UserTestAffectation = $this->getDoctrine()->getRepository(UsersTests::class)->findOneBy(['test_id'=>$test,'user_id'=> $user]);
        $questions = $test->getquestions();
        $form = $this->createFormBuilder($UserTestAffectation)
            ->add('submition',CollectionType::class,[
               'entry_type' => TextType::class,
                    'allow_add' => true,
                    'entry_options' => [
                        'attr' => ['size'=> 50],
                        'required' => false,
                    ],
                    ]
            )
            ->add('save',SubmitType::class, ['label'=> 'Submit Test','attr'=> array('class'=> 'btn btn-primary')])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $entityManager = $this->getDoctrine()->getManager();
            /**
             * @var $submissions UsersTests
             */
            $submissions = $form->getData();
            $submissions->setNbrEssai($submissions->getnbr_essai()+1);
            $submissions->setStatus('submitted');
            $entityManager->merge($submissions);
            $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('testslist');
        }
        return $this->render('@Certification/examPage.html.twig',array('questions'=> $questions, 'form'=> $form->createView(), 'test' => $test));
    }
    /**
     * @Route("/SubmittedTest/{test_id}", name="submitted_test")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function TestsubmitionPage($test_id){
        return $this->render('@Certification/successfulTestSubmission.html.twig');

    }
    /**
     * @Route("/CheckSubmittedTest/{test_id}", name="check_submitted_test")
     */
    public function CheckTestSubmitionPage($test_id){
        return $this->render('@Certification/check_submitted_test.html.twig');

    }

    /**
     * @Route("/addTest", name="addTest")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTest(Request $request){
        $Test = new Test();
        $Test->setQuestions(array(""));
        $form = $this->createForm( TestType::class, $Test);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $entityManager = $this->getDoctrine()->getManager();
            /**
             * @var $submissions UsersTests
             */
            $Test = $form->getData();
            $Users = $this->getDoctrine()->getRepository(User::class)->findAll();
            foreach ( $Users as $user ){
                $UserTest = new UsersTests();
                $UserTest->setScore(0);
                $UserTest->setStatus('active');
                $UserTest->setCorrection(array_fill(0,sizeof($Test->getquestions()),""));
                $UserTest->setSubmition(array_fill(0, sizeof($Test->getQuestions()),""));
                $UserTest->setNbrEssai(0);
                $UserTest->setUserId($user);
                $UserTest->setTestId($Test);
                $entityManager->persist($UserTest);

            }
            $entityManager->persist($Test);
            $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('addTest');
        }
        return $this->render('@Certification/addTest.html.twig', array('form'=> $form->createView()));
    }
    /**
     * @Route("modifyTestList", name="modifyTestList")
     */
        public function testslistForModification(){
            $Tests = $this->getDoctrine()->getRepository(Test::class)->findAll();
            return $this->render("@Certification/listTestsForModification.html.twig", ['tests'=> $Tests]);

        }

    /**
     * @Route("modifyTest/{id}", name="modifyTest")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function TestModification($id, Request $request){
        $Test = $this->getDoctrine()->getRepository(Test::class)->find($id);
        $form = $this->createForm( TestType::class, $Test);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $entityManager = $this->getDoctrine()->getManager();
            /**
             * @var $submissions UsersTests
             */
            $Test = $form->getData();
            $entityManager->merge($Test);
            $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('modifyTestList');
        }
        return $this->render("@Certification/ModifyTest.html.twig", ['test'=> $Test, 'form' => $form->createView()]);

    }

}
