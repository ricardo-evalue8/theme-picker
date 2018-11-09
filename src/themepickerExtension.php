<?php

namespace Bolt\Extension\evalue8\themepicker;

use Bolt\Extension\SimpleExtension;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\File\Stylesheet;
use Bolt\Controller\Zone;
use Bolt\Asset\Target;

use Bolt\Menu\MenuEntry;
use Bolt\Menu\AdminMenuBuilder;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * An extremely basic (thus far) login wallpaper extension.
 *
 * Spice up your /bolt/login screen by pulling images from Unsplash
 * and displaying them as the login background.
 *
 */
class themepickerExtension extends SimpleExtension
{
	protected function registerMenuEntries()
	{
		$menu = MenuEntry::create('theme-menu', 'theme')
			->setLabel('Theme Picker')
			->setIcon('fa:paint-brush')
			->setPermission('settings')
		;
		
		$submenuItemOne = MenuEntry::create('theme-submenu-one', '../../theme-picker')
			->setLabel('Select theme')
			->setIcon('fa:paint-brush')
		;

		$submenuItemTwo = MenuEntry::create('theme-submenu-two', '../../theme-upload')
			->setLabel('Upload theme')
			->setIcon('fa:upload')
		;

		$menu->add($submenuItemOne);
		$menu->add($submenuItemTwo);

		return [
			$menu,
		];
	}

    /**
     * {@inheritdoc}
     */
    protected function registerBackendRoutes(ControllerCollection $collection)
    {
        // GET requests on the /bolt/theme-picker route
        $collection->get('/theme-picker', [$this, 'callbackBoltPicker']);

        // POST requests on the /bolt/theme-picker route
        $collection->post('/theme-picker', [$this, 'callbackBoltPicker']);
		
		// GET requests on the /bolt/theme-picker route
        $collection->get('/theme-upload', [$this, 'callbackBoltUpload']);

        // POST requests on the /bolt/theme-picker route
        $collection->post('/theme-upload', [$this, 'callbackBoltUpload']);
    }

    /**
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function callbackBoltPicker(Application $app, Request $request)
    {
        return $this->renderTemplate('theme-picker.twig');
    }
	public function callbackBoltUpload(Application $app, Request $request)
    {
        return $this->renderTemplate('theme-upload.twig');
    }

    /**
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function callbackKoalaAdmin(Application $app, Request $request)
    {
        if ($request->isMethod('POST')) {
            // Handle the POST data
            return new Response('Thanks, Kenny', Response::HTTP_OK);
        }

        return new Response('Welcome to your admin page, Kenny', Response::HTTP_OK);
    }
	
	protected function registerAssets()
    {
        // Add some web assets from the web/ directory
        return [
            new Stylesheet('card.css'),
        ];
    }
}
