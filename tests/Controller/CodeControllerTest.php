<?php

namespace App\Test\Controller;

use App\Entity\Code;
use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CodeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CodeRepository $repository;
    private string $path = '/code/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Code::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Code index');

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
            'code[hash]' => 'Testing',
            'code[url]' => 'Testing',
            'code[created_at]' => 'Testing',
            'code[updated_at]' => 'Testing',
            'code[used_by]' => 'Testing',
            'code[lecture]' => 'Testing',
        ]);

        self::assertResponseRedirects('/code/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Code();
        $fixture->setHash('My Title');
        $fixture->setUrl('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setUsed_by('My Title');
        $fixture->setLecture('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Code');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Code();
        $fixture->setHash('My Title');
        $fixture->setUrl('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setUsed_by('My Title');
        $fixture->setLecture('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'code[hash]' => 'Something New',
            'code[url]' => 'Something New',
            'code[created_at]' => 'Something New',
            'code[updated_at]' => 'Something New',
            'code[used_by]' => 'Something New',
            'code[lecture]' => 'Something New',
        ]);

        self::assertResponseRedirects('/code/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getHash());
        self::assertSame('Something New', $fixture[0]->getUrl());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getUsed_by());
        self::assertSame('Something New', $fixture[0]->getLecture());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Code();
        $fixture->setHash('My Title');
        $fixture->setUrl('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setUsed_by('My Title');
        $fixture->setLecture('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/code/');
    }
}
