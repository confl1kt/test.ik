<?php

class FolderScaner
{

    /**
     * Max level of searching in folders
     * @const MAX_SEARCH_LEVEL integer
     */
    const MAX_SEARCH_LEVEL = 2;

    public $SEARCH_EVERWHERE_IF_NO_DIRS = false;

    /**
     * Dir variable controls in which dirs to provide search
     * @var array
     */
    public $dirs = array();

    /**
     * Method wich be called to each item while search() is called
     * @var string
     */
    protected $_method;

    /**
     * Recursively searching all config valid files in pinted folders
     *
     * @param callback $callback
     * @param string $dir
     * @see dirs
     */
    public function findall($callback, $dir='', $level = 0)
    {
        $f = glob($dir . '/*');
        if(is_array($f))
        foreach ($f as $fname)
        {
            if (is_dir($fname))
            {
                if($level<self::MAX_SEARCH_LEVEL)
                {
                    self::findall($callback, $fname, $level+1);
                    continue;
                }
            }
            call_user_func($callback, $dir, $fname);
        }
    }

    /**
     * Walk over all pointed folders and looking for config files
     */
    public function search()
    {
        if(count($this->dirs)==0 && $this->SEARCH_EVERWHERE_IF_NO_DIRS)
            $this->dirs[]= '.';

        foreach($this->dirs as $dir)
            self::findall(array($this,$this->_method), yii::getPathOfAlias($dir));
    }
}

?>