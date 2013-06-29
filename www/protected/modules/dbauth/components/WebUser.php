<?

class WebUser extends CWebUser {

    private $_model = null;
    public $_roles = array();
    
    function getRoles() {
        if ($user = $this->getModel()) {
            return explode(',', $user->Role->role_eng);
        }
    }

    function getRole() {
        if ($user = $this->getModel()) {
            return $user->Role->role_eng;
        }
    }

    public static function hasRole($role, $uid = 0) {
        if ($uid == 0)
            $uid = Yii::app()->user->getId();

        if (!is_array($role))
            $role = array($role);

        $user = User::model()->findByPk($uid);
        if (isset($user->Role->role_eng)) {
            $roles = explode(',', $user->Role->role_eng);
            foreach ($roles as $urole) {
                if (in_array($urole, $role))
                    return true;
            }
        }
        return false;
    }

    public function getModel($rolesOnly = false) {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = User::model()->with('Role')->findByPk($this->id);
        }
        return $this->_model;
    }

}

?>