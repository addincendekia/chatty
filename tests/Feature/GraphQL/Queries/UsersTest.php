<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\User;
use Database\Seeders\DummyUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\GraphQL\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    private $mockUser = [
        'email' => 'user-test@chatty.com',
        'name' => 'user test',
    ];

    private $graphQLHeaders = [];

    private $queryUsers = '{
        users {
            paginatorInfo {
                count
                currentPage
                firstItem
                hasMorePages
                lastItem
                lastPage
                perPage
                total
            }
            data {
                id
                name
                email
            }
        }
    }';

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([DummyUserSeeder::class]);

        $this->graphQLHeaders = [
            'Authorization' => 'Bearer ' . $this->signinUser()->token,
        ];
    }

    private function signinUser(): User
    {
        $authUser = User::factory(1)->create($this->mockUser)->first();
        $authUserToken = $authUser->createToken($authUser->name . ' personal token');

        $authUser->token = $authUserToken->plainTextToken;

        return $authUser;
    }

    /**
     * authenticated user query multiple user.
     */
    public function test_unauthenticated_user_not_allow_query_users(): void
    {
        $graphQLHeaders = $this->graphQLHeaders;
        unset($graphQLHeaders['Authorization']);

        $response = $this->graphQL(
            /** @lang GraphQL */
            $this->queryUsers,
            [],
            [],
            $graphQLHeaders,
        );

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->where('errors.0.message', 'Unauthenticated.');
        });
    }

    /**
     * authenticated user query multiple user.
     */
    public function test_authenticated_user_query_users(): void
    {
        $graphQLHeaders = $this->graphQLHeaders;

        $response = $this->graphQL(
            /** @lang GraphQL */
            $this->queryUsers,
            [],
            [],
            $graphQLHeaders,
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'users' => [
                    'paginatorInfo' => [
                        'count',
                        'currentPage',
                        'firstItem',
                        'hasMorePages',
                        'lastItem',
                        'lastPage',
                        'perPage',
                        'total',
                    ],
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                        ]
                    ]
                ]
            ]
        ]);
    }
}
