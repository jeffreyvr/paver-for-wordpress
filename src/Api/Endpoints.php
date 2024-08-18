<?php

namespace Jeffreyvr\PaverForWordpress\Api;

use Jeffreyvr\Paver\Endpoints\Fetch;
use Jeffreyvr\Paver\Endpoints\Render;
use Jeffreyvr\Paver\Endpoints\Options;
use Jeffreyvr\Paver\Endpoints\Resolve;

class Endpoints
{
    function permission($request)
    {
        return current_user_can('edit_post', $request->get_param('post_id'));
    }

    public function __construct()
    {
        register_rest_route('paver/v1', '/editor/options', [
            'methods' => 'POST',
            'callback' => [new Options, 'handle'],
            'permission_callback' => [$this, 'permission'],
        ]);

        register_rest_route('paver/v1', '/editor/render', [
            'methods' => 'POST',
            'callback' => [new Render, 'handle'],
            'permission_callback' => [$this, 'permission'],
        ]);

        register_rest_route('paver/v1', '/editor/fetch', [
            'methods' => 'POST',
            'callback' => [new Fetch, 'handle'],
            'permission_callback' => [$this, 'permission'],
        ]);

        register_rest_route('paver/v1', '/editor/resolve', [
            'methods' => 'POST',
            'callback' => [new Resolve, 'handle'],
            'permission_callback' => [$this, 'permission'],
        ]);
    }
}
