<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\GraphQL\TestCase;

final class SigninUserTest extends TestCase
{
    use RefreshDatabase;

    private $mockUser = [
        'email' => 'user-test@chatty.com',
        'name' => 'user test',
    ];

    private $querySignin = '
        mutation ($email: String!, $password: String!) {
            signinUser(input: {
                email: $email
                password: $password
            }) {
                name
                email
                token
            }
        }
    ';

    public function setUp(): void
    {
        parent::setUp();

        User::factory(1)->create($this->mockUser);
    }

    public function test_signin_user_with_invalid_credential(): void
    {
        $response = $this->graphQL(
            /** @lang GraphQL */
            $this->querySignin,
            [
                'email' => $this->mockUser['email'],
                'password' => 'passworddd'
            ]
        );

        $response->assertOk();

        $response->assertJsonPath('errors.0.message', "These credentials do not match our records.");
    }

    public function test_signin_user_with_valid_credential(): void
    {
        $response = $this->graphQL(
            /** @lang GraphQL */
            $this->querySignin,
            [
                'email' => $this->mockUser['email'],
                'password' => 'password'
            ]
        );

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['data.signinUser.name', 'data.signinUser.email', 'data.signinUser.token'])
                ->where('data.signinUser.email', $this->mockUser['email'])
                ->where('data.signinUser.name', $this->mockUser['name'])
                ->whereType('data.signinUser.token', 'string');
        });

        $this->assertDatabaseHas('personal_access_tokens', ['name' => $this->mockUser['name'] . ' personal token']);
    }
}
