<?php namespace Mews\Purifier;

/**
 * Laravel 5 HTMLPurifier package
 *
 * @copyright Copyright (c) 2015 MeWebStudio
 * @version   2.0.0
 * @author    Muharrem ERİN
 * @contact me@mewebstudio.com
 * @web http://www.mewebstudio.com
 * @date      2014-04-02
 * @license   MIT
 */

use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class Purifier
{

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var HTMLPurifier
     */
    protected $purifier;

    /**
     * Constructor
     *
     * @param Filesystem $files
     * @param Repository $config
     * @throws Exception
     */
    public function __construct(Filesystem $files, Repository $config)
    {
        $this->files  = $files;
        $this->config = $config;

        $this->setUp();
    }

    /**
     * Setup
     *
     * @throws Exception
     */
    private function setUp()
    {
        if (!$this->config->has('purifier')) {
            if (!$this->config->has('mews.purifier')) {
                throw new Exception('Configuration parameters not loaded!');
            }
            $this->config->set('purifier', $this->config->get('mews.purifier'));
        }

        $this->checkCacheDirectory();

        // Create a new configuration object
        $config = HTMLPurifier_Config::createDefault();

        // Allow configuration to be modified
        if (!$this->config->get('purifier.finalize')) {
            $config->autoFinalize = false;
        }

        $config->loadArray($this->getConfig());

        if ($def = $config->maybeGetRawHTMLDefinition()) {

            if ($addElement = $this->config->get('purifier.addElement')) {
                foreach($addElement as $item) {
                    call_user_func_array(array($def, "addElement"), $item);
                };
            }

            if ($addAttribute = $this->config->get('purifier.addAttribute')) {
                foreach($addAttribute as $item) {
                    call_user_func_array(array($def, "addAttribute"), $item);
                };
            }
        }

        // Create HTMLPurifier object
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Check/Create cache directory
     */
    private function checkCacheDirectory()
    {
        $cachePath = $this->config->get('purifier.cachePath');

        if ($cachePath) {
            if (!$this->files->isDirectory($cachePath)) {
                $this->files->makeDirectory($cachePath);
            }
        }
    }

    /**
     * @param null $config
     * @return mixed|null
     */
    protected function getConfig($config = null)
    {
        $default_config = [];
        $default_config['Core.Encoding']        = $this->config->get('purifier.encoding');
        $default_config['Cache.SerializerPath'] = $this->config->get('purifier.cachePath');

        if (!$config) {
            $config = $this->config->get('purifier.settings.default');
        } elseif (is_string($config)) {
            $config = $this->config->get('purifier.settings.' . $config);
        }

        if (!is_array($config)) {
            $config = [];
        }

        $config = $default_config + $config;

        return $config;
    }

    /**
     * @param      $dirty
     * @param null $config
     * @return mixed
     */
    public function clean($dirty)
    {
        if (is_array($dirty)) {
            return array_map(function ($item) {
                return $this->clean($item);
            }, $dirty);
        } else {
            //the htmlpurifier use replace instead merge, so we merge
            return $this->purifier->purify($dirty);
        }
    }

}