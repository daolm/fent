<?php

class SearchController extends Controller {
    
    public function actionIndex($key_word) {
        $device = Device::model()->find('serial_number = :serial_number OR management_number = :management_number OR name = :name', 
                array(':serial_number' => $key_word, ':management_number' => $key_word, ':name' => $key_word));
        if (isset($device)) {
            $this->redirect(array('device/view', 'id' => $device->id));
        }
        $profile = Profile::model()->find('employee_code=:employee_code OR name = :name', 
                array(':employee_code' => $key_word, ':name' => $key_word));
        if (isset($profile)) {
            $this->redirect(array('profile/view', 'id' => $profile->id));
        }
        
        $devices = Device::model()->findAll();
        $as = new ApproximateSearch($devices, array('name', 'management_number', 'serial_number') , $key_word);
        $devices_found = $as->search();
        
        $profiles = Profile::model()->findAll();
        $as = new ApproximateSearch($profiles, array('name'), $key_word);
        $profiles_found = $as->search();
        $this->render('results', array('devices_found' => $devices_found, 'profiles_found' => $profiles_found));
    }
}

?>