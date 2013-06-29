<?php

/**
 * Class for searching and merging configs, before application is inited.
 * Usefull for automating modules configuration
 *
 * In folders, you want to process, you shoold place files with naming *.config.php
 * It must return array: <? return array(); ?>
 *
 * To stop this process add to config ['params']['IgnoreOtherConfigs'] = true
 *
 * @author  Maxim Ezhov <ezhov.maxim@gmail.com>
 * @version 1.0
 */
class ConfigInFolders extends FolderScaner
{

    /**
     * This constant controls enabling of caching result in file
     * @const CACHE_IN_FILE
     */
    const CACHE_IN_FILE = false;

    /**
     * Resulting config array
     * 
     * @var array $configs
     */
    public $configs = array();

    public $sourceConfig = array();

    static $currentInstance;

    protected $_method = 'addConfig';

    /**
     * Merge config in file $fname into full stack
     * 
     * @param null $dir - just for capability
     * @param string $fname
     * @return void
     */
    public function addConfig($dir, $fname)
    {
        //TODO: preg match it too slow
        if(preg_match('/[a-z0-9]+\.config\.php$/i', $fname)==0)
                   return;
        
        $array = include($fname);
        
        $this->configs = CMap::mergeArray($this->configs, $array);
        Yii::trace('Merge to:' . $fname, 'application.components.ConfigInFolders');
    }

    /**
     * Returns a value indicating whether the specified module is installed.
     * Can work before application started
     *
     * @param string $id
     * @return boolean
     */
    public function hasModule($id)
    {
        return isset($this->sourceConfig['modules'][$id]);
    }


    /**
     * Return current instance of class
     *
     * @return ConfigInFolders instance
     */
    static public function singleton()
    {
        return static::$currentInstance;
    }

    /**
     * Returns merged config from all peaces
     *
     * @param array $dirs
     * @param array $config
     * @return array valid config
     */
    static public function merge($dirs, $config = array())
    {
        Yii::trace('Start merging', 'application.components.ConfigInFolders');

        if(isset($config['params']['IgnoreOtherConfigs']))
            return $config;

        //TODO: refactor caching in file
        if(static::CACHE_IN_FILE && file_exists('cfg.cache'))
            return unserialize (file_get_contents ('cfg.cache'));

        static::$currentInstance = $cif = new self;
        $cif->dirs = $dirs;
        $cif->sourceConfig = $config;
        $cif->search();

        $result = CMap::mergeArray($config, $cif->configs);

        if(static::CACHE_IN_FILE)
            file_put_contents ('cfg.cache', serialize ($result));

        Yii::trace('End merging without cache', 'application.components.ConfigInFolders');

        return $result;
    }

}

?>