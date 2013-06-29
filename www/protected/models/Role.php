<?php

class Role extends CActiveRecord{	
	public static function model($className=__CLASS__){return parent::model($className);}
	public function tableName(){return 'roles';}
	public function rules(){		
		return array(
			array('role, role_eng', 'required'),
			array('role, role_eng', 'length', 'max'=>256),			
			array('id, role, role_eng', 'safe', 'on'=>'search'),
		);
	}

	public function relations(){		
		return array(
		);
	}
	
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'role' => 'Role',
			'role_eng' => 'Role Eng',
		);
	}
	
	public function search(){
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('role_eng',$this->role_eng,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}