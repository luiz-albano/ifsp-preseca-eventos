<?php

namespace App\Test\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParticipantControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ParticipantRepository $repository;
    private string $path = '/participant/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Participant::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Participant index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'participant[kind]' => 'Testing',
            'participant[ra]' => 'Testing',
            'participant[name]' => 'Testing',
            'participant[email]' => 'Testing',
            'participant[reason]' => 'Testing',
            'participant[accepted_terms]' => 'Testing',
            'participant[user_agent]' => 'Testing',
            'participant[ip]' => 'Testing',
            'participant[created_at]' => 'Testing',
            'participant[updated_at]' => 'Testing',
            'participant[course]' => 'Testing',
        ]);

        self::assertResponseRedirects('/participant/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Participant();
        $fixture->setKind('My Title');
        $fixture->setRa('My Title');
        $fixture->setName('My Title');
        $fixture->setEmail('My Title');
        $fixture->setReason('My Title');
        $fixture->setAccepted_terms('My Title');
        $fixture->setUser_agent('My Title');
        $fixture->setIp('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setCourse('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Participant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Participant();
        $fixture->setKind('My Title');
        $fixture->setRa('My Title');
        $fixture->setName('My Title');
        $fixture->setEmail('My Title');
        $fixture->setReason('My Title');
        $fixture->setAccepted_terms('My Title');
        $fixture->setUser_agent('My Title');
        $fixture->setIp('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setCourse('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'participant[kind]' => 'Something New',
            'participant[ra]' => 'Something New',
            'participant[name]' => 'Something New',
            'participant[email]' => 'Something New',
            'participant[reason]' => 'Something New',
            'participant[accepted_terms]' => 'Something New',
            'participant[user_agent]' => 'Something New',
            'participant[ip]' => 'Something New',
            'participant[created_at]' => 'Something New',
            'participant[updated_at]' => 'Something New',
            'participant[course]' => 'Something New',
        ]);

        self::assertResponseRedirects('/participant/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getKind());
        self::assertSame('Something New', $fixture[0]->getRa());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getReason());
        self::assertSame('Something New', $fixture[0]->getAccepted_terms());
        self::assertSame('Something New', $fixture[0]->getUser_agent());
        self::assertSame('Something New', $fixture[0]->getIp());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getCourse());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Participant();
        $fixture->setKind('My Title');
        $fixture->setRa('My Title');
        $fixture->setName('My Title');
        $fixture->setEmail('My Title');
        $fixture->setReason('My Title');
        $fixture->setAccepted_terms('My Title');
        $fixture->setUser_agent('My Title');
        $fixture->setIp('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setCourse('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/participant/');
    }
}
