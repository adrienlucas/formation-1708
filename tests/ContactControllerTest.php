<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    /**
     * Happy path
     */
    public function testWeCanContact()
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists("#contact_email");
        $this->assertSelectorExists("#contact_message");

        $client->submitForm('Contacter', [
            'contact[email]' => 'test@smile.fr',
            'contact[message]' => 'test',
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Merci d\'avoir pris contact');
    }

    /**
     * Sad path / Unhappy path
     */
    public function testTheContactFormDisplaysErrors()
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists("#contact_email");
        $this->assertSelectorExists("#contact_message");

        $client->submitForm('Contacter', [
            'contact[email]' => 'test',
            'contact[message]' => str_repeat("a",300),
        ]);

        $this->assertSelectorTextContains('body > form > div:nth-child(1) > ul > li', 'This value is not a valid email address');
        $this->assertSelectorTextContains('body > form > div:nth-child(2) > ul > li', 'This value is too long. It should have 256 characters or less');
    }
}
