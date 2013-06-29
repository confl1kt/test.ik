<?php

class Installed
{
    public $time;

    public function  __construct()
    {
        $this->time= time();
    }
}

class AutoInstall
{
    const FILENAME = 'installed.info';

    public $alias = 'application.install';

    protected $installed = array();

    public function __construct($alias = 'application.install')
    {
        $this->alias = $alias;
        $this->loadInfo();
    }

    public function loadInfo()
    {
        if (file_exists(self::FILENAME))
        {
            $this->installed = unserialize(file_get_contents(self::FILENAME));
        }
        else
            $this->saveInfo();
    }

    public function saveInfo()
    {
        file_put_contents(self::FILENAME, serialize($this->installed));
    }

    public function Install()
    {
        foreach(glob( yii::getPathOfAlias($this->alias) . '/*.ai.sql') as $filename)
        {
            if(!isset($this->installed[$filename]))
            {
                $this->installed[$filename] = new Installed;
                
                try
                {
                    importDump::import($filename);                
                }
                catch(exception $e)
                {
                    print $e->getMessage();
                }
                
            }
        }
        

        $this->saveInfo();
    }

}

?>