<?php

namespace Jeffreyvr\PaverForWordpress\Api;

use Jeffreyvr\Paver\Endpoints\Handler;

class Endpoints
{
    public function permission($request)
    {
        return current_user_can('edit_post', $request->get_param('post_id'));
    }

    public function __construct()
    {
        // A single route serves every action; Handler dispatches on the
        // `action` in the request body. The closure drops WordPress's request
        // object so Handler reads the raw body itself, as the endpoints do.
        register_rest_route('paver/v1', '/editor', [
            'methods' => 'POST',
            'callback' => fn () => Handler::run(),
            'permission_callback' => [$this, 'permission'],
        ]);
    }
}
