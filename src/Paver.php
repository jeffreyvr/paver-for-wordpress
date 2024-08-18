<?php

namespace Jeffreyvr\PaverForWordpress;

use Jeffreyvr\WPSettings\WPSettings;
use Jeffreyvr\PaverForWordpress\Api\Endpoints;
use Jeffreyvr\PaverForWordpress\Editor as WordPressEditor;
use Jeffreyvr\Paver\Paver as BasePaver;

class Paver extends BasePaver
{
    public WordPressEditor $wordPressEditor;

    public WPSettings $wordPressSettings;

    public array $options;

    public string|array $wordpressViewPath = __DIR__.'/../resources/views/';

    public string|array $wordpressAssetPath = __DIR__.'/../assets/';

    function bootForWordPress()
    {
        $this->wordPressEditor = new WordPressEditor();

        $this->api->setEndpoints([
            'fetch' => '/wp-json/paver/v1/editor/fetch',
            'render' => '/wp-json/paver/v1/editor/render',
            'options' => '/wp-json/paver/v1/editor/options',
            'resolve' => '/wp-json/paver/v1/editor/resolve'
        ]);

        $this->assetPath = [
            __DIR__ . '/../assets/',
            $this->assetPath,
        ];

        add_action('add_meta_boxes', [$this, 'setApiData']);
        add_action('admin_menu', [$this, 'settings']);

        add_action('rest_api_init', function () {
            new Endpoints();
        });

        $this->options = get_option('paver', []);
    }

    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    function setApiData()
    {
        $this->api->setPayload([
            'post_id' => get_the_ID(),
        ]);

        $this->api->setHeaders([
            'X-WP-Nonce' => wp_create_nonce('wp_rest')
        ]);
    }

    function settings()
    {
        $settings = new WPSettings(__('Paver'));

        $settings->set_menu_icon('data:image/svg+xml;base64,'.base64_encode(file_get_contents(__DIR__ . '/../resources/svgs/icon.svg')));

        $section = $settings->add_tab(__('General', 'paver'))
            ->add_section('General');

        $section->add_option('select-multiple', [
            'name' => 'post_types',
            'description' => __('Select post types to enable the Paver editor for.', 'textdomain'),
            'label' => __('Post types', 'textdomain'),
            'options' => fn() => get_post_types(['public' => true])
        ]);

        $settings->make();

        $this->wordPressSettings = $settings;
    }
}
