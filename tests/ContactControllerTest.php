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

        $this->assertSelectorTextNotContains('body', 'This value is not a valid email address');
        $this->assertSelectorTextNotContains('body', 'This value is too long. It should have 256 characters or less');

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
        // Request the contact page
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists("#contact_email");
        $this->assertSelectorExists("#contact_message");

        // Submit the form
        $client->submitForm('Contacter', [
            'contact[email]' => 'test',
            'contact[message]' => str_repeat("a",300),
        ]);

        // This is the only way to find the error message attached to #contact_message field
        $errorMessageTag = $client->getCrawler()
            ->filter('#contact_message')
            ->previousAll()
            ->eq(0)
            ->filter('li');

        $this->assertSame('This value is too long. It should have 256 characters or less.', $errorMessageTag->text());

        $this->assertSelectorTextContains('form[name="contact"]', 'This value is not a valid email address');

        $this->assertSelectorTextContains('body > form > div:nth-child(2) > ul > li', '');
    }
}
