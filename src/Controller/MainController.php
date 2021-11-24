<?php

namespace App\Controller;

use App\Constant\Works;
use App\Entity\CV;
use App\Entity\Job;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/", name="app_jobs_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SearchType::class);

        $jobs = $entityManager->getRepository(Job::class)->findAll();

        return $this->render('index.html.twig', ['form' => $form->createView(), 'jobs' => $jobs]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     * @Route("/_internal/search", name="app_jobs_search", methods={"POST"})
     */
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $formData = $form->getData();

        if (empty($formData) || !array_key_exists('search', $formData)) {
            throw $this->createNotFoundException();
        }

        $query = $formData['search'];

        $jobsRepository = $entityManager->getRepository(Job::class);
        $jobs = $jobsRepository->searchByQuery($query);

        $html = $this->renderView('_jobs.html.twig', ['jobs' => $jobs]);

        return new Response($html);
    }

    /**
     * @param Job $job
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/jobs/{job}", name="app_jobs_show", methods={"GET"})
     */
    public function show(Job $job, EntityManagerInterface $entityManager): Response
    {
        $cvRepository = $entityManager->getRepository(CV::class);
        $cvs = $cvRepository->findRelevantCVs(Works::PROGRAMMER);

        return $this->render('show.html.twig', ['job' => $job, 'cvs' => $cvs]);
    }
}
