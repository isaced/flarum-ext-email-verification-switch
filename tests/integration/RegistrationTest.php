<?php

namespace Isaced\EmailVerificationSwitch\Tests\integration;

use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RegistrationTest extends TestCase
{
    use RetrievesAuthorizedUsers;

    protected function setUp(): void
    {
        parent::setUp();

        // The locale catalogue cache writes into storage/locale, which the
        // setup script's bare directory list does not create.
        if (! is_dir($locale = $this->tmpDir().'/storage/locale')) {
            mkdir($locale, 0777, true);
        }

        $this->extension('isaced-email-verification-switch');
    }

    private function registerAs(string $username): \Psr\Http\Message\ResponseInterface
    {
        // Guest signup goes through the session CSRF flow, so a token is
        // fetched first, exactly like the forum frontend does.
        return $this->send(
            $this->requestWithCsrfToken(
                $this->request('POST', '/api/users', [
                    'json' => [
                        'data' => [
                            'attributes' => [
                                'username' => $username,
                                'email' => "$username@example.com",
                                'password' => 'a-strong-password',
                            ],
                        ],
                    ],
                ])
            )
        );
    }

    #[Test]
    public function registration_activates_the_account_immediately(): void
    {
        $response = $this->registerAs('newmember');

        $this->assertEquals(201, $response->getStatusCode());

        $user = $this->database()->table('users')->where('username', 'newmember')->first();

        $this->assertNotNull($user);
        $this->assertEquals(1, $user->is_email_confirmed);
    }

    #[Test]
    public function no_confirmation_token_is_stored_for_the_new_account(): void
    {
        $this->registerAs('nomailmember');

        $user = $this->database()->table('users')->where('username', 'nomailmember')->first();

        // Activation happens before the user is saved, so Flarum never reaches
        // the point of issuing an email confirmation token.
        $tokens = $this->database()->table('email_tokens')
            ->where('email', $user->email)
            ->count();

        $this->assertEquals(0, $tokens);
    }

    #[Test]
    public function editing_an_existing_unconfirmed_user_does_not_activate_them(): void
    {
        $this->prepareDatabase([
            'users' => [
                array_merge($this->normalUser(), ['is_email_confirmed' => 0]), // id 2
            ],
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/users/2', [
                'authenticatedAs' => 1,
                'json' => [
                    'data' => [
                        'type' => 'users',
                        'id' => '2',
                        'attributes' => ['username' => 'renamed'],
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $user = $this->database()->table('users')->where('id', 2)->first();

        $this->assertEquals('renamed', $user->username);
        $this->assertEquals(0, $user->is_email_confirmed);
    }
}
