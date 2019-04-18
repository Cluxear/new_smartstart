<?php

namespace ContractBundle\Controller;


use AppBundle\Entity\FosUser;
use ContractBundle\Entity\Contract;
use ContractBundle\Form\ContractType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use UserBundle\Entity\User;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $contracts = $this->getDoctrine()->getRepository(Contract::class)->getMyContracts($user->getId());
        $form = $this->get('form.factory')
            ->createNamedBuilder('payment-form')
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('idCon', HiddenType::class)
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                // TODO: charge the card
                $em = $this->getDoctrine()->getManager();
                $contract = $em->getRepository(Contract::class)->find($form->get('idCon')->getData());
                $stripeClient = $this->get('my.stripe.client');
                $stripeClient->createCharge($contract->getSum()*100,
                    "usd",
                    $form->get('token')->getData(),
                    null,
                    "in cents",
                    "Pay the Freelancer ".$contract->getIdApplication()->getUsername());
                return $this->redirectToRoute('my_contracts');
            }
        }
        return $this->render('@Contract/Default/index.html.twig',array('con'=>$contracts,'form' => $form->createView(),
            'stripe_public_key' => "pk_test_RzueYgRk554ZpsUNBPruZ42P"));
    }
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $contract = $em->getRepository(Contract::class)->find($id);
        $em->remove($contract);
        $em->flush();
        return $this->redirectToRoute('my_contracts');
    }
    public function lowAction()
    {
        $user=new FosUser();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $contracts = $this->getDoctrine()->getRepository(Contract::class)->getMyLowContracts($user->getId());
        return $this->render('@Contract/Default/lowContracts.html.twig',array('con'=>$contracts));
    }
    public function updateAction(Request $request,$id)
    {
        $contract = new Contract();
        $em = $this->getDoctrine()->getManager();
        $contract = $em->getRepository(Contract::class)->find($id);
        $contract->setStartDate(new \DateTime($request->request->get('start')));
        $contract->setFinishDate(new \DateTime($request->request->get('finish')));
        $contract->setSum($request->request->get('sum'));
        $contract->setPaymentMethod($request->request->get('paymentMethod'));
        $contract->setPrio(1);
        $em->persist($contract);
        $em->flush();
        return $this->redirectToRoute('my_contracts');
    }
    public function freelancerContractsAction()
    {
        $user = $this->getUser();
        $contracts = $this->getDoctrine()->getRepository(Contract::class)->freelancerContracts($user->getId());
        return $this->render('@Contract/Default/freelancerContracts.html.twig',array('con'=>$contracts));
    }
    public function printAction($id){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $date=new \DateTime();
        $converstion = $date->format('y-m-d');
        $em = $this->getDoctrine()->getManager();
        $contract = $em->getRepository(Contract::class)->find($id);
        $converstionstart = $contract->getStartDate()->format('y-m-d');
        $converstionfinish = $contract->getFinishDate()->format('y-m-d');
        $snappy = $this->get("knp_snappy.pdf");
        $html = "<h1>CONTRACT</h1>
                <p>This Contract is between ".$contract->getIdApplication()->getUsername() ."</p>
                                <p>The Contract is dated ".$converstion."</p>
                                <p>1. WORK AND PAYMENT.</p>
                                <p>1.1 Project. The Client is hiring the Developer to do the following: </p>
                                <p>".$contract->getIdApplication()->getUsername()."</p>
                                <P>1.2 Schedule. The Developer will begin work on ".$converstionstart." and must finish the work by ".$converstionfinish."</P>
                                <p>1.3 Payment. The Client will pay the Developer a flat fee of ".$contract->getSum()." (USD). </p>
                                <p>1.4 Expenses. The Client will reimburse the Developer's expenses. Expenses do not need to be pre-approved by the Client.</p>
                                <p>1.5 Invoices. The Developer will invoice the Client at the end of the project. The Client agrees to pay the amount owed within 15 days of receiving the invoice. Payment after that date will incur a late fee of 0.0% per month on the outstanding amount.</p>
                                <p>1.6 Support. The Developer will not provide support for any deliverable once the Client accepts it, unless otherwise agreed in writing.</p>
                                <p>2. OWNERSHIP AND LICENSES.</p>
                                <p>2.1 Client Owns All Work Product. As part of this job, the Developer is creating “work product” for the Client. To avoid confusion, work product is the finished product, as well as drafts, notes, materials, mockups, hardware, designs, inventions, patents, code, and anything else that the Developer works on—that is, conceives, creates, designs, develops, invents, works on, or reduces to practice—as part of this project, whether before the date of this Contract or after. The Developer hereby gives the Client this work product once the Client pays for it in full. This means the Developer is giving the Client all of its rights, titles, and interests in and to the work product (including intellectual property rights), and the Client will be the sole owner of it. The Client can use the work product however it wants or it can decide not to use the work product at all. The Client, for example, can modify, destroy, or sell it, as it sees fit.
                                2.2 Developer’s Use Of Work Product. Once the Developer gives the work product to the Client, the Developer does not have any rights to it, except those that the Client explicitly gives the Developer here.
                                2.3 Developer’s Help Securing Ownership. In the future, the Client may need the Developer’s help to show that the Client owns the work product or to complete the transfer. The Developer agrees to help with that. For example, the Developer may have to sign a patent application. The Client will pay any required expenses for this. If the Client can’t find the Developer, the Developer agrees that the Client can act on the Developer’s behalf to accomplish the same thing. The following language gives the Client that right: if the Client can’t find the Developer after spending reasonable effort trying to do so, the Developer hereby irrevocably designates and appoints the Client as the Developer’s agent and attorney-in-fact, which appointment is coupled with an interest, to act for the Developer and on the Developer’s behalf to execute, verify, and file the required documents and to take any other legal action to accomplish the purposes of paragraph 2.1 (Client Owns All Work Product).
                                2.4 Developer’s IP That Is Not Work Product. During the course of this project, the Developer might use intellectual property that the Developer owns or has licensed from a third party, but that does not qualify as “work product.” This is called “background IP.” Possible examples of background IP are pre-existing code, type fonts, properly-licensed stock photos, and web application tools. The Developer is not giving the Client this background IP. But, as part of the Contract, the Developer is giving the Client a right to use and license (with the right to sublicense) the background IP to develop, market, sell, and support the Client’s products and services. The Client may use this background IP worldwide and free of charge, but it cannot transfer its rights to the background IP (except as allowed in Section 11.1 (Assignment)). The Client cannot sell or license the background IP separately from its products or services. The Developer cannot take back this grant, and this grant does not end when the Contract is over.
                                2.5 Developer’s Right To Use Client IP. The Developer may need to use the Client’s intellectual property to do its job. For example, if the Client is hiring the Developer to build a website, the Developer may have to use the Client’s logo. The Client agrees to let the Developer use the Client’s intellectual property and other intellectual property that the Client controls to the extent reasonably necessary to do the Developer’s job. Beyond that, the Client is not giving the Developer any intellectual property rights, unless specifically stated otherwise in this Contract.
                                3. COMPETITIVE ENGAGEMENTS. The Developer won’t work for a competitor of the Client until this Contract ends. To avoid confusion, a competitor is any third party that develops, manufactures, promotes, sells, licenses, distributes, or provides products or services that are substantially similar to the Client’s products or services. A competitor is also a third party that plans to do any of those things. The one exception to this restriction is if the Developer asks for permission beforehand and the Client agrees to it in writing. If the Developer uses employees or subcontractors, the Developer must make sure they follow the obligations in this paragraph, as well.
                                4. NON-SOLICITATION. Until this Contract ends, the Developer won’t: (a) encourage Client employees or service providers to stop working for the Client; (b) encourage Client customers or clients to stop doing business with the Client; or (c) hire anyone who worked for the Client over the 12-month period before the Contract ended. The one exception is if the Developer puts out a general ad and someone who happened to work for the Client responds. In that case, the Developer may hire that candidate. The Developer promises that it won’t do anything in this paragraph on behalf of itself or a third party.
                                5. REPRESENTATIONS.
                                5.1 Overview. This section contains important promises between the parties.
                                5.2 Authority To Sign. Each party promises to the other party that it has the authority to enter into this Contract and to perform all of its obligations under this Contract.
                                5.3 Developer Has Right To Give Client Work Product. The Developer promises that it owns the work product, that the Developer is able to give the work product to the Client, and that no other party will claim that it owns the work product. If the Developer uses employees or subcontractors, the Developer also promises that these employees and subcontractors have signed contracts with the Developer giving the Developer any rights that the employees or subcontractors have related to the Developer’s background IP and work product.
                                5.4 Developer Will Comply With Laws. The Developer promises that the manner it does this job, its work product, and any background IP it uses comply with applicable U.S. and foreign laws and regulations.
                                5.5 Work Product Does Not Infringe. The Developer promises that its work product does not and will not infringe on someone else’s intellectual property rights, that the Developer has the right to let the Client use the background IP, and that this Contract does not and will not violate any contract that the Developer has entered into or will enter into with someone else.
                                5.6 Client Will Review Work. The Client promises to review the work product, to be reasonably available to the Developer if the Developer has questions regarding this project, and to provide timely feedback and decisions.
                                5.7 Client-Supplied Material Does Not Infringe. If the Client provides the Developer with material to incorporate into the work product, the Client promises that this material does not infringe on someone else’s intellectual property rights.
                                6. TERM AND TERMINATION. This Contract ends on March 25, 2019, unless the Client or the Developer ends the contract before that time. Either party may end this Contract for any reason by sending an email or letter to the other party, informing the recipient that the sender is ending the Contract and that the Contract will end in 7 days. The Contract officially ends once that time has passed. The party that is ending the Contract must provide notice by taking the steps explained in Section 11.4. The Developer must immediately stop working as soon as it receives this notice, unless the notice says otherwise. The Client will pay the Developer for the work done up until when the Contract ends and will reimburse the Developer for any agreed-upon, non-cancellable expenses. The following sections don’t end even after the Contract ends: 2 (Ownership and Licenses); 3 (Competitive Engagements); 4 (Non-Solicitation); 5 (Representations); 8 (Confidential Information); 9 (Limitation of Liability); 10 (Indemnity); and 11 (General).
                                7. INDEPENDENT CONTRACTOR. The Client is hiring the Developer as an independent contractor. The following statements accurately reflect their relationship:
                                - The Developer will use its own equipment, tools, and material to do the work.
                                - The Client will not control how the job is performed on a day-to-day basis. Rather, the Developer is responsible for determining when, where, and how it will carry out the work.
                                - The Client will not provide the Developer with any training.
                                - The Client and the Developer do not have a partnership or employer-employee relationship.
                                - The Developer cannot enter into contracts, make promises, or act on behalf of the Client.
                                - The Developer is not entitled to the Client’s benefits (e.g., group insurance, retirement benefits, retirement plans, vacation days).\n\"
                                - The Developer is responsible for its own taxes.
                                - The Client will not withhold social security and Medicare taxes or make payments for disability insurance, unemployment insurance, or workers compensation for the Developer or any of the Developer’s employees or subcontractors.
                                8. CONFIDENTIAL INFORMATION.
                                8.1 Overview. This Contract imposes special restrictions on how the Client and the Developer must handle confidential information. These obligations are explained in this section.
                                8.2 The Client’s Confidential Information. While working for the Client, the Developer may come across, or be given, Client information that is confidential. This is information like customer lists, business strategies, research & development notes, statistics about a website, and other information that is private. The Developer promises to treat this information as if it is the Developer’s own confidential information. The Developer may use this information to do its job under this Contract, but not for anything else. For example, if the Client lets the Developer use a customer list to send out a newsletter, the Developer cannot use those email addresses for any other purpose. The one exception to this is if the Client gives the Developer written permission to use the information for another purpose, the Developer may use the information for that purpose, as well. When this Contract ends, the Developer must give back or destroy all confidential information, and confirm that it has done so. The Developer promises that it will not share confidential information with a third party, unless the Client gives the Developer written permission first. The Developer must continue to follow these obligations, even after the Contract ends. The Developer’s responsibilities only stop if the Developer can show any of the following: (i) that the information was already public when the Developer came across it; (ii) the information became public after the Developer came across it, but not because of anything the Developer did or didn’t do; (iii) the Developer already knew the information when the Developer came across it and the Developer didn’t have any obligation to keep it secret; (iv) a third party provided the Developer with the information without requiring that the Developer keep it a secret; or (v) the Developer created the information on its own, without using anything belonging to the Client.
                                8.3 Third-Party Confidential Information. It’s possible the Client and the Developer each have access to confidential information that belongs to third parties. The Client and the Developer each promise that it will not share with the other party confidential information that belongs to third parties, unless it is allowed to do so. If the Client or the Developer is allowed to share confidential information with the other party and does so, the sharing party promises to tell the other party in writing of any special restrictions regarding that information.
                                9. LIMITATION OF LIABILITY. Neither party is liable for breach-of-contract damages that the breaching party could not reasonably have foreseen when it entered this Contract.
                                10. INDEMNITY.
                                10.1 Overview. This section transfers certain risks between the parties if a third party sues or goes after the Client or the Developer or both. For example, if the Client gets sued for something that the Developer did, then the Developer may promise to come to the Client’s defense or to reimburse the Client for any losses.
                                10.2 Client Indemnity. In this Contract, the Developer agrees to indemnify the Client (and its affiliates and its and their directors, officers, employees, and agents) from and against all liabilities, losses, damages, and expenses (including reasonable attorneys’ fees) related to a third-party claim or proceeding arising out of: (i) the work the Developer has done under this Contract; (ii) a breach by the Developer of its obligations under this Contract; or (iii) a breach by the Developer of the promises it is making in Section 5 (Representations).
                                10.3 Developer Indemnity. In this Contract, the Client agrees to indemnify the Developer (and its affiliates and its and their directors, officers, employees, and agents) from and against liabilities, losses, damages, and expenses (including reasonable attorneys’ fees) related to a third-party claim or proceeding arising out of a breach by the Client of its obligations under this Contract.
                                11. GENERAL.
                                11.1 Assignment. This Contract applies only to the Client and the Developer. The Developer cannot assign its rights or delegate its obligations under this Contract to a third-party (other than by will or intestate), without first receiving the Client’s written permission. In contrast, the Client may assign its rights and delegate its obligations under this Contract without the Developer’s permission. This is necessary in case, for example, another Client buys out the Client or if the Client decides to sell the work product that results from this Contract.
                                11.2 Arbitration. As the exclusive means of initiating adversarial proceedings to resolve any dispute arising under this Contract, a party may demand that the dispute be resolved by arbitration administered by the American Arbitration Association in accordance with its commercial arbitration rules.
                                11.3 Modification; Waiver. To change anything in this Contract, the Client and the Developer must agree to that change in writing and sign a document showing their contract. Neither party can waive its rights under this Contract or release the other party from its obligations under this Contract, unless the waiving party acknowledges it is doing so in writing and signs a document that says so.
                                11.4 Notices.
                                (a) Over the course of this Contract, one party may need to send a notice to the other party. For the notice to be valid, it must be in writing and delivered in one of the following ways: personal delivery, email, or certified or registered mail (postage prepaid, return receipt requested). The notice must be delivered to the party’s address listed at the end of this Contract or to another address that the party has provided in writing as an appropriate address to receive notice.
                                (b) The timing of when a notice is received can be very important. To avoid confusion, a valid notice is considered received as follows: (i) if delivered personally, it is considered received immediately; (ii) if delivered by email, it is considered received upon acknowledgement of receipt; (iii) if delivered by registered or certified mail (postage prepaid, return receipt requested), it is considered received upon receipt as indicated by the date on the signed receipt. If a party refuses to accept notice or if notice cannot be delivered because of a change in address for which no notice was given, then it is considered received when the notice is rejected or unable to be delivered. If the notice is received after 5:00pm on a business day at the location specified in the address for that party, or on a day that is not a business day, then the notice is considered received at 9:00am on the next business day.
                                11.5 Severability. This section deals with what happens if a portion of the Contract is found to be unenforceable. If that’s the case, the unenforceable portion will be changed to the minimum extent necessary to make it enforceable, unless that change is not permitted by law, in which case the portion will be disregarded. If any portion of the Contract is changed or disregarded because it is unenforceable, the rest of the Contract is still enforceable.
                                11.6 Signatures. The Client and the Developer must sign this document using Bonsai’s e-signing system. These electronic signatures count as originals for all purposes.
                                11.7 Governing Law. The laws of the state of Alaska govern the rights and obligations of the Client and the Developer under this Contract, without regard to conflict of law principles of that state.
                                11.8 Entire Contract. This Contract represents the parties’ final and complete understanding of this job and the subject matter discussed in this Contract. This Contract supersedes all other contracts (both written and oral) between the parties.
                                THE PARTIES HERETO AGREE TO THE FOREGOING AS EVIDENCED BY THEIR SIGNATURES BELOW.</p>
                                <p>                          ".$contract->getIdApplication()->getUsername()          ."</p>
                ";
        $fileName = $contract->getIdApplication()->getUsername();
        return new Response($snappy->getOutputFromHtml($html),200,array('Content-Type' => 'application/pdf','Content-Disposition' => 'attachment; filename="'.$fileName.'.pdf"'));
    }

    public function ajouterContractAction(Request $request){
        $contract = new Contract();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $form = $this->createForm( ContractType::class, $contract);
        $form->handleRequest($request);
        if ($form->isSubmitted()){

            /**
             * @var $contract Contract
             */
            $contract = $form->getData();
            $user = $this->getDoctrine()->getRepository(User::class)->find($contract->getIdApplication());
            $contract->setIdApplication($user);
            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('ajouter_contract');
        }
        return $this->render('@Contract/Default/ajouter_contract.html.twig', ['form'=> $form->createView()]);
    }

}
