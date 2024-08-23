<?php

namespace Jeffreyvr\PaverForWordpress;

use Jeffreyvr\Paver\Blocks\Renderer;
use Jeffreyvr\PaverForWordpress\Paver;
use Jeffreyvr\Paver\Blocks\BlockFactory;

class Editor
{
    public function __construct()
    {
        add_action('admin_init', function() {
            $postTypes = Paver::instance()->getOption('post_types', []);

            foreach ($postTypes as $postType) {
                if ($this->usePaverEditor()) {
                    add_filter('use_block_editor_for_' . $postType, '__return_false', 10);

                    remove_post_type_support($postType, 'editor');
                }

                add_action('save_post', [$this, 'save']);

                add_filter($postType . '_row_actions', [$this, 'addRowAction'], 10, 2);
            }
        });

        add_action('wp_enqueue_scripts', [$this, 'media']);

        add_action('add_meta_boxes', [$this, 'register'], -999);

        add_filter('the_content', [$this, 'content']);

        add_filter('redirect_post_location', [$this, 'redirect'], 10, 2);

        add_action('admin_bar_menu', [$this, 'adminMenuBar'], 80);

        add_filter('template_include', function($template) {
            if($this->postUsesPaver() && file_exists(get_stylesheet_directory() . '/paver.php')) {
                return get_stylesheet_directory() . '/paver.php';
            }

            return $template;
        });

    }

    function adminMenuBar($wp_admin_bar)
    {
        if (!current_user_can('edit_posts')) {
            return;
        }

        $post = get_post();
        $postTypes = Paver::instance()->getOption('post_types', []);

        if ($post && is_singular($postTypes)) {
            $wp_admin_bar->add_node(array(
                'id'    => 'paver-edit',
                'title' => 'Edit (Paver)',
                'href'  => $this->editWithPaver($post),
                'is_subitem' => false,
                'meta'  => array(
                    'class' => 'paver-edit-page',
                    'title' => 'Edit this page with Paver',
                ),
            ));
        }
    }

    function redirect($location, $post_id)
    {
        if ($this->usePaverEditor()) {
            return add_query_arg('paver-editor', '', $location);
        }

        return $location;
    }

    function usePaverEditor()
    {
        return isset($_REQUEST['paver-editor']);
    }

    function editWithPaver($post)
    {
        if (is_numeric($post)) {
            $post = get_post($post);
        }

        return add_query_arg('paver-editor', '', get_edit_post_link($post->ID));
    }

    function addRowAction($actions, $post)
    {
        $newActions = [];

        $newActions['edit_paver'] = '<a href="' . add_query_arg('paver-editor', '', get_edit_post_link($post->ID)) . '">' . __('Edit (Paver)', 'textdomain') . '</a>';

        return array_merge($newActions, $actions);
    }

    function register()
    {
        $postTypes = Paver::instance()->getOption('post_types', []);

        foreach ($postTypes as $postType) {
            add_meta_box(
                'paver',
                'Paver',
                [$this, 'render'],
                $postType,
                'normal',
                'high'
            );
        }
    }

    function save($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        if (array_key_exists('paver_editor_content', $_POST)) {
            update_post_meta($post_id, '_paver_editor_content', $_POST['paver_editor_content']);

            remove_action('save_post', [$this, 'save']);

            wp_update_post([
                'ID' => $post_id,
                'post_content' => strip_tags($this->renderForPage($post_id), '<p><a><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><img><figure><figcaption><iframe><video><audio><source><blockquote><code><pre><br><hr>')
            ]);

            add_action('save_post', [$this, 'save']);
        }
    }

    function media()
    {
        if (! is_page() || ! is_main_query()) {
            return;
        }

        $content = get_post_meta(get_the_ID(), '_paver_editor_content', true);

        if (empty($content)) {
            return;
        }

        wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], null, [
            'strategy' => 'defer'
        ]);

        foreach (paver()->blocks() as $key => $block) {
            $blockInstance = BlockFactory::createById($key);

            foreach ($blockInstance->styles as $style) {
                wp_enqueue_style($style['handle'], $style['src'], $style['deps']);
            }

            foreach ($blockInstance->scripts as $script) {
                wp_enqueue_script($script['handle'], $script['src'], $script['deps']);
            }
        }
    }

    function renderForPage($postId = null)
    {
        if (is_null($postId)) {
            $postId = get_the_ID();
        }

        $blocks = get_post_meta(get_the_ID(), '_paver_editor_content', true);

        return Renderer::blocks($blocks);
    }

    function postUsesPaver()
    {
        $postTypes = Paver::instance()->getOption('post_types', []);

        if (is_singular($postTypes) && is_main_query()) {

            $blocks = get_post_meta(get_the_ID(), '_paver_editor_content', true);

            return !empty($blocks);
        }

        return false;
    }

    function content($content)
    {

        if ($this->postUsesPaver()) {
            $content = $this->renderForPage();
        }

        return $content;
    }

    public function editorStyles()
    {
        global $editor_styles;
        $styles = '';

        if (!empty($editor_styles)) {
            foreach ($editor_styles as $style) {
                $style_path = locate_template($style);
                if ($style_path) {
                    $content = file_get_contents($style_path);
                    $content = str_replace('body#tinymce.wp-editor.content', 'body', $content);
                    $styles .= '<style>'. $content . '</style>';
                }
            }
        }

        return $styles;
    }

    function styles()
    {
        $output = $this->editorStyles();

        foreach (paver()->blocks() as $key => $block) {
            $blockInstance = BlockFactory::createById($key);

            foreach ($blockInstance->styles as $style) {
                $output .= '<link rel="stylesheet" href="' . $style['src'] . '">';
            }
        }

        return $output;
    }

    function scripts()
    {
        $output = '';

        foreach (paver()->blocks() as $key => $block) {
            $blockInstance = BlockFactory::createById($key);

            foreach ($blockInstance->scripts as $script) {
                $output .= '<script src="' . $script['src'] . '"></script>';
            }
        }

        return $output;
    }

    function render($post)
    {
        if (! $this->usePaverEditor()) {
            echo '<p>Want to use the Paver editor instead?</p>';
            echo '<a href="'.$this->editWithPaver($post).'" class="button button-primary">'.file_get_contents('../resources/svgs/icon.svg').'Use Paver</a></p>';
            return;
        }

        $blocks = get_post_meta($post->ID, '_paver_editor_content', true);

        paver()->frame->headHtml = $this->styles();
        paver()->frame->footerHtml = $this->scripts();

        paver()->locale = explode('_', get_locale())[0] ?? 'en';

        echo paver()->render($blocks, [
            'showSaveButton' => false
        ]);

        echo '<input type="hidden" name="paver-editor" value="1">';
    }
}
