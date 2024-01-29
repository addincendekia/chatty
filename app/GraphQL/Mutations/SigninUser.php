<?php

namespace App\GraphQL\Mutations;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SigninUser
{
    /**
     * Return a value for the field.
     *
     * @param  null  $root Always null, since this field has no parent.
     * @param  array{}  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $request = new LoginRequest();
        $request->merge($args['input']);

        $request->authenticate();

        /** @var \App\Models\User */
        $user = Auth::user();

        $user->tokens()->delete();

        $personalToken = $user->createToken($user->name . ' personal token');
        $user->token = $personalToken->plainTextToken;

        return $user;
    }
}
