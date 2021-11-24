<?php

namespace App\Command;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CrawlCommand extends Command
{
    private const URL = 'https://www.bestjobs.eu/locuri-de-munca?location=bucuresti&keyword=symfony';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     * @param EntityManagerInterface $entityManager
     * @param string|null $name
     */
    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:crawl-jobs')
            ->setDescription('Crawls jobs')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Parsing jobs...');

        $response = $this->client->request(
            'GET',
            self::URL
        );

        $crawler = new Crawler($response->getContent());
        $cards = $crawler->filter('.card');

        $cardData = $cards->each(function ($node, $i) {
            return [
                'url' => $node->filter('div > div > a')->attr('href'),
                'title' => $node->filter('.card-body > .text-center > h2')->text(),
                'company' => $node->filter('.card-body > .text-center > h3 > small')->text(),
                'location' => $node->filter('.card-footer > div > div > .text-truncate > span > a')->text()
            ];
        });

        $count = count($cardData);
        $output->writeln("Found {$count} jobs! Processing...");

        foreach ($cardData as $key => $item) {
            $response = $this->client->request(
                'GET',
                $item['url']
            );

            $crawler = new Crawler($response->getContent());
            $jobDescription = $crawler->filter('.job-description')->html();

            $job = new Job();
            $job->setTitle($item['title']);
            $job->setCompany($item['company']);
            $job->setLocation($item['location']);
            $job->setDescription($jobDescription);

            $this->entityManager->persist($job);

            $output->writeln('Processed '.($key+1)." of {$count} jobs!");
        }

        $this->entityManager->flush();

        $output->writeln('Done!');

        return self::SUCCESS;
    }
}
