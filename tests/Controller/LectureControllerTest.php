<?php

namespace App\Test\Controller;

use App\Entity\Lecture;
use App\Repository\LectureRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LectureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private LectureRepository $repository;
    private string $path = '/lecture/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Lecture::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lecture index');

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
            'lecture[lecturer]' => 'Testing',
            'lecture[location]' => 'Testing',
            'lecture[attendees_quantity]' => 'Testing',
            'lecture[subtitle]' => 'Testing',
            'lecture[description]' => 'Testing',
            'lecture[start_date]' => 'Testing',
            'lecture[end_date]' => 'Testing',
            'lecture[lecturer_image]' => 'Testing',
            'lecture[created_at]' => 'Testing',
            'lecture[updated_at]' => 'Testing',
            'lecture[event]' => 'Testing',
        ]);

        self::assertResponseRedirects('/lecture/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Lecture();
        $fixture->setLecturer('My Title');
        $fixture->setLocation('My Title');
        $fixture->setAttendees_quantity('My Title');
        $fixture->setSubtitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStart_date('My Title');
        $fixture->setEnd_date('My Title');
        $fixture->setLecturer_image('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setEvent('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lecture');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Lecture();
        $fixture->setLecturer('My Title');
        $fixture->setLocation('My Title');
        $fixture->setAttendees_quantity('My Title');
        $fixture->setSubtitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStart_date('My Title');
        $fixture->setEnd_date('My Title');
        $fixture->setLecturer_image('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setEvent('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'lecture[lecturer]' => 'Something New',
            'lecture[location]' => 'Something New',
            'lecture[attendees_quantity]' => 'Something New',
            'lecture[subtitle]' => 'Something New',
            'lecture[description]' => 'Something New',
            'lecture[start_date]' => 'Something New',
            'lecture[end_date]' => 'Something New',
            'lecture[lecturer_image]' => 'Something New',
            'lecture[created_at]' => 'Something New',
            'lecture[updated_at]' => 'Something New',
            'lecture[event]' => 'Something New',
        ]);

        self::assertResponseRedirects('/lecture/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLecturer());
        self::assertSame('Something New', $fixture[0]->getLocation());
        self::assertSame('Something New', $fixture[0]->getAttendees_quantity());
        self::assertSame('Something New', $fixture[0]->getSubtitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getStart_date());
        self::assertSame('Something New', $fixture[0]->getEnd_date());
        self::assertSame('Something New', $fixture[0]->getLecturer_image());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getEvent());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Lecture();
        $fixture->setLecturer('My Title');
        $fixture->setLocation('My Title');
        $fixture->setAttendees_quantity('My Title');
        $fixture->setSubtitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStart_date('My Title');
        $fixture->setEnd_date('My Title');
        $fixture->setLecturer_image('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setEvent('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/lecture/');
    }
}
