<?php

class UserController extends Controller
{            
    public function actionSignIn()
    {   
        if (Yii::app()->user->getId()) {
            $this->redirect(Yii::app()->user->returnUrl);
        } else {
            $form = new SigninForm;        
            if (isset($_POST['SigninForm']))
            {            
                $form->attributes = $_POST['SigninForm'];                        
                if ($form->validate() && $form->login()) {
                    Yii::app()->session['category'] = Category::model()->findAll();
                    $channel = null;
                    if (RedisNotification::checkRequirement()) {
                        $rn = new RedisNotification();                        
                        if ($rn->connect()) {
                            if (Yii::app()->user->isAdmin) {
                                $channel = $rn->checkIn('admin');
                            } else {
                                $channel = $rn->checkIn(Yii::app()->user->getId());                            
                            }                                                
                        }
                    }
                    Yii::app()->user->setState('redisChannel', $channel);
                    $this->redirect(Yii::app()->user->returnUrl);
                } 
            }
            $this->render('signin', array('form' => $form));
        }
    }
    
    public function actionSignUp($email, $key)
    {      
        $profile = Profile::model()->findByAttributes(
            array('email' => $email, 'secret_key' => $key));
        if ($profile != null && $profile->user == null) {                        
            $form = new SignUpForm;            
            if (isset($_POST['SignUpForm'])) {                                
                $form->attributes = $_POST['SignUpForm'];
                $form->profile_id = $profile->id;
                $form->validate();                
                if (!$form->hasErrors()) {
                    $user = new User;
                    $user->signUp($form->username, $form->password, $form->profile_id);
                    $profile->updateKey();
                    $signinForm = new SigninForm;
                    $signinForm->attributes = $_POST['SignUpForm'];
                    $signinForm->login();
                    Yii::app()->session['category'] = Category::model()->findAll();
                    $this->redirect(Yii::app()->homeUrl);
                }
            } 
            $this->render('signup', array('form' => $form));
        } else {
            Yii::app()->user->setFlash('fail', 'Invalid URL !');
            $this->redirect(Yii::app()->homeUrl);
        }
        
    }
    
    public function actionForgetPassword()
    {
        if (isset($_POST['ForgetPasswordForm'])) {
            $profile = ProfileOrUserFinder::findProfile($_POST['ForgetPasswordForm']['arg']);
            if ($profile != null && $profile->user != null) {
                $profile->sendResetPasswordLink();
                Yii::app()->user->setFlash('sucessful', 'We have sent you a link to reset your password. 
                    Please check your email');
                $this->redirect(Yii::app()->homeUrl);
            } else {
                Yii::app()->user->setFlash('fail', 'Sorry ! No User found !');                
            }
        }        
        $this->render('forget_password');        
    }
    
    public function actionResetPassword($email, $key)
    {        
        $profile = Profile::model()->findByAttributes(
            array('email' => $email, 'secret_key' => $key));
        if ($profile != null) {   
            $form = new ResetPasswordForm;   
            if (isset($_POST['ResetPasswordForm'])) {             
                $form->password = $_POST['ResetPasswordForm']['password'];
                $form->passwordConfirm = $_POST['ResetPasswordForm']['passwordConfirm'];
                $form->validate();
                if (!$form->hasErrors()) {
                    $user = $profile->user;
                    $user->password = md5($form->password);
                    $user->save();
                    $profile->updateKey(); 
                    Yii::app()->user->setFlash('sucessful', 'Your password has been changed !');
                    $this->redirect(Yii::app()->homeUrl);
                }
            }
            $this->render('reset_password', array('form' => $form));
        } else {
            Yii::app()->user->setFlash('fail', 'Invalid URL !');
            $this->redirect(Yii::app()->homeUrl);
        }
    }
    
    public function actionChangePassword() {
        $form = new ChangePasswordForm;
        if (isset($_POST['ChangePasswordForm'])){
            $form->oldPass = $_POST['ChangePasswordForm']['oldPass'];
            $form->newPass = $_POST['ChangePasswordForm']['newPass'];
            $form->passConfirm = $_POST['ChangePasswordForm']['passConfirm'];
            $form->validate();
            if (!$form->hasErrors()){
                $user = User::model()->findByPk(Yii::app()->user->id);
                if ($user->password != md5($form->oldPass)){
                    Yii::app()->user->setFlash('fail', 'Old password is incorrect!');
                    $this->refresh();
                } else {
                    $user->password = md5($form->newPass);
                    $user->save();
                    Yii::app()->user->setFlash('sucessful', 'Your password has been changed !');
                    $this->redirect(Yii::app()->homeUrl);
                }
            }
        }
        $this->render('change_password', array('form' => $form));
    }
    
    public function actionSignout() {        
        if (RedisNotification::checkRequirement()) {
            $rn = new RedisNotification();        
            if ($rn->connect()) {            
                $rn->checkOut(Yii::app()->user->getId());                                                                                        
            }
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
    
    public function actionFavorite() {
        $user = User::model()->findByPk(Yii::app()->user->getId());
        $favorite_devices = $user->favorite_devices;
        $this->render('favorite', array('favorite_devices' => $favorite_devices));
    }
    
    public function actionDelete($id) {
        $profile = Profile::model()->findByPk($id);
        if($profile === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }
        $profile->deleteUser();
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/profile/index'));            
    }
}
?>
