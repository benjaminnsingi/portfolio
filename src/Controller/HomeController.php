<?php

namespace App\Controller;

use App\Entity\About;
use App\Entity\Portfolio;
use App\Form\ContactType;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')]
    public function index(Request $request, MailerInterface $mail): Response
    {
        $abouts = $this->entityManager->getRepository(About::class)->findAll();
        $portfolios = $this->entityManager->getRepository(Portfolio::class)->findAll();


        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $contactFormData = $form->getData();

            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('your@mail.com')
                ->subject('Your got mail')
                ->text('expediteur : '.$contactFormData['email'].\PHP_EOL.
                    $contactFormData['message'],
                    'text/plain');
            $mail->send($message);

            $this->addFlash('message', 'Mail de contact envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig',[
            'abouts' => $abouts,
            'portfolios' => $portfolios,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/portfolio/{slug}{id}", name="portfolio.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Portfolio $portfolio
     * @param string $slug
     * @return Response
     */
    public function show(Portfolio $portfolio, string $slug): Response
    {
       if ($portfolio->getSlug() !== $slug) {
           return $this->redirectToRoute('portfolio.show',[
               'id' => $portfolio->getId(),
               'slug' => $portfolio->getSlug()
           ], 301);
       }

       return $this->render('portfolio/show.html.twig', [
           'portfolio' => $portfolio,
           'current_menu' => 'portfolios',
       ]);
    }
}
