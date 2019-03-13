<?php

namespace Ompmega\BlogShortcodes;

use Ompmega\BlogShortcodes\Models\Shortcode as ShortcodeModel;
use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Cms\Classes\Controller as CmsController;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Parser\RegexParser;
use System\Classes\SettingsManager;
use System\Classes\PluginBase;
use Backend;
use Event;

/**
 * Class Plugin
 * @package Ompmega\BlogShortcodes
 * @author Ompmega
 */
class Plugin extends PluginBase
{
    public $require = ['RainLab.Blog'];

    /**
     * @inheritdoc
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Blog Shortcodes',
            'description' => 'Easily create custom shortcodes for your blog using partials.',
            'author'      => 'Ompmega',
            'icon'        => 'oc-icon-bolt',
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerSettings(): array
    {
        return [
            'shortcodes' => [
                'label'       => 'Blog Shortcodes',
                'description' => 'Manage your shortcodes to use in your blog.',
                'icon'        => 'icon-code',
                'url'         => Backend::url('ompmega/blogshortcodes/shortcodes'),
                'category'    => SettingsManager::CATEGORY_CMS,
                'permissions' => ['ompmega.blogshortcodes.manage_shortcodes'],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerPermissions(): array
    {
        return [
            'ompmega.blogshortcode.manage_shortcodes' => [
                'tab'   => 'Shortcodes',
                'label' => 'Manage shortcodes'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        Event::listen('backend.menu.extendItems', function (\Backend\Classes\NavigationManager $manager) {
            $manager->addSideMenuItem('RainLab.Blog', 'blog', 'shortcodes', [
                'label' => 'Shortcodes',
                'icon'  => 'icon-code',
                'url'   => Backend::url('ompmega/blogshortcodes/shortcodes'),
                'order' => 400,
                'permissions' => ['ompmega.blogshortcodes.manage_shortcodes']
            ]);
        });

        //$handlers->add('url', new UrlHandler());

        Event::listen('markdown.beforeParse', function ($data) {
            /** @var $handlers \Thunder\Shortcode\HandlerContainer\HandlerContainer */
            $handlers = new HandlerContainer();

            $shortcodes = ShortcodeModel::remember(5, ShortcodeModel::SHORTCODE_CACHE)->get();

            // Loops all registered shortcodes.
            foreach ($shortcodes as $shortcode) {

                if ($handlers->has($shortcode->code)) {
                    continue;
                }

                /*if (!$shortcode->has_preview) {
                    continue;
                }*/

                $handlers->add($shortcode->code, function (ShortcodeInterface $s) use ($shortcode) {
                    $params = $s->getParameters();
                    $params['content'] = $s->getContent();
                    $params['bbcode'] = $s->getBbCode();

                    $shortcodeParams = str_replace('&quot;', '', $params);

                    $cms = new CmsController();
                    $buffer = $cms->renderPartial(sprintf('shortcodes/_%s', $shortcode->code), $shortcodeParams);
                    return str_replace(["\r", "\n"], '', $buffer);
                });
            }

            $data->text = (new Processor(new RegexParser(), $handlers))->process($data->text);
        });
    }
}
