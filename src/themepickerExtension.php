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

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;
//use Bolt\Configuration\YamlUpdater;


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
			->setRoute('select-theme')
		;

		$submenuItemOne = MenuEntry::create('theme-submenu-one', '../theme-picker')
			->setLabel('Select theme')
			->setIcon('fa:paint-brush')
			->setPermission('useraction')
		;

		$submenuItemTwo = MenuEntry::create('theme-submenu-two', '../theme-toggle')
			->setLabel('Enable / Disable themes')
			->setIcon('fa:toggle-on')
			->setPermission('upload_theme')
		;

		$submenuItemThree = MenuEntry::create('theme-submenu-three', '../theme-upload')
			->setLabel('Upload theme')
			->setIcon('fa:upload')
			->setPermission('upload_theme')
		;


		$menu->add($submenuItemOne);
		$menu->add($submenuItemTwo);
		$menu->add($submenuItemThree);

		return [
			$menu,
		];
	}

    /**
     * {@inheritdoc}
     */
    protected function registerBackendRoutes(ControllerCollection $collection)
    {
				// Theme select
        // GET requests on the /bolt/theme-picker route
        $collection->get('/theme-picker', [$this, 'callbackBoltPicker']);

        // POST requests on the /bolt/theme-picker route
        $collection->post('/theme-picker', [$this, 'callbackBoltPicker']);

				// Theme enable / disable
				// GET requests on the /bolt/theme-picker route
        $collection->get('/theme-toggle', [$this, 'callbackBoltToggle']);

        // POST requests on the /bolt/theme-picker route
        $collection->post('/theme-toggle', [$this, 'callbackBoltToggle']);

				// Theme upload
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

			//echo $_SERVER['HTTP_HOST'] . "/bolt";
			//print_r($_SERVER);
			if (isset($_GET['theme']) && !empty($_GET['theme'])) {
				$file = $_SERVER['DOCUMENT_ROOT'] . '/../app/config/config.yml';

				$yaml = Yaml::parse(file_get_contents($file));
				$yaml['theme'] = $_GET['theme'];
				$new_yaml = Yaml::dump($yaml);
				file_put_contents($file, $new_yaml);

				// Reload page (Theme-picker)
				header("Location: " . "http://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL']);

				// Wait 5 seconds
				sleep(5);

				exit;
			}

			// Get themes from theme folder
			$folders = array_slice(scandir('theme/'), 2);

			// Delete from array function
			function deleteElement($element, &$array){
			    $index = array_search($element, $array);
			    if($index !== false){
			        unset($array[$index]);
			    }
			}

			// Read config file
			$yaml = new Parser();
			$config_load = $yaml->parse(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../app/config/config.yml'));

			// Get active theme from config
			$active_theme = $config_load['theme'];

			// Delete active theme from themes array
			deleteElement($active_theme, $folders);

			// Themes array to twig
			$themes = [
				'theme' => $folders,
			];

			$success = false;


			return $this->renderTemplate('theme-picker.twig', $themes);
    }

		// Theme Enable / disable
		public function callbackBoltToggle(Application $app, Request $request)
    {

			if (isset($_GET['theme']) && !empty($_GET['theme'])) {
				$file = $_SERVER['DOCUMENT_ROOT'] . '/../app/config/config.yml';

				$yaml = Yaml::parse(file_get_contents($file));
				$yaml['theme'] = $_GET['theme'];
				$new_yaml = Yaml::dump($yaml);
				file_put_contents($file, $new_yaml);

				// Reload page (Theme-picker)
				header("Location: " . "http://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL']);

				// Wait 5 seconds
				sleep(5);

				exit;
			}

			// Get themes from theme folder
			$folders = array_slice(scandir('theme/'), 2);

			// Delete from array function
			function deleteElement($element, &$array){
			    $index = array_search($element, $array);
			    if($index !== false){
			        unset($array[$index]);
			    }
			}

			// Read config file
			$yaml = new Parser();
			$config_load = $yaml->parse(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../app/config/config.yml'));

			// Get active theme from config
			$active_theme = $config_load['theme'];

			// Delete active theme from themes array
			deleteElement($active_theme, $folders);

			// Get active Themes
			$file = $_SERVER['DOCUMENT_ROOT'] . '/theme/base-2018/state.txt';

			// Create array for twig
			$statuses = array();
			foreach ($folders as $states) {
					$file = $_SERVER['DOCUMENT_ROOT'] . '/theme/' . $states . '/state.txt';

					$myfile = fopen($file, "r") or die("Unable to open file!");
					$state =  fread($myfile,filesize($file));
					$statuses[$states] = $state;
					//array_push($statuses, $state);
					fclose($myfile);
			}

			// Themes array to twig
			$themes = [
				'theme' => $statuses,
			];

			return $this->renderTemplate('theme-toggle.twig', $themes);
    }

		// Theme upload
		public function callbackBoltUpload(Application $app, Request $request)
    {
			$file = $_SERVER['DOCUMENT_ROOT'] . 'extensions/vendor/evalue8/themepicker/templates/upload.php';

			$replace = array("public_html");
			$file = str_replace($replace, "", $file);

			echo $file . '<br>';
			$action = [
		    'action' => $file,
			];

			return $this->renderTemplate('theme-upload.twig', $action);

			//return $this->renderTemplate('theme-upload.twig', $action);
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
			$asset = Stylesheet::create()
			->setFileName('koala.css')
			->setLate(true)
			->setPriority(5)
			->setZone(Zone::BACKEND)
			;

			return [
					$asset,
			];
      // Add some web assets from the web/ directory
      return [
          new Stylesheet('card.css'),
      ];
    }


		// Set new active theme
		public function setTheme($theme)
    {
			$file = $_SERVER['DOCUMENT_ROOT'] . '/../app/config/config.yml';

			$yaml = Yaml::parse(file_get_contents($file));
			$srcData = $yaml['theme'];
			//echo $srcData;
			$yaml['theme'] = $theme;
			$new_yaml = Yaml::dump($yaml);
			file_put_contents($file, $new_yaml);
    }


		// Create twig function from php functions
    protected function registerTwigFunctions()
    {
        return [
            'card_css_file' => 'cardCssFile',
						'set_theme' => 'setTheme',
        ];
    }

    /**
     * The callback function when {{ card_css_file() }} is used in a template.
     *
     * @return string
     */
    public function cardCssFile()
    {
        $context = [
            'something' => mt_rand(),
        ];

        return $this->renderTemplate('extension.twig', $context);
    }
}
