<?php

class UserController extends Controller
{            
    public function actionSignIn()
    {               
        $form = new SigninForm;        
        if (isset($_POST['SignInForm']))
        {            
            $form->attributes = $_POST['SignInForm'];                        
            if ($form->validate() && $form->login()) {                                     
                $this->redirect(Yii::app()->user->returnUrl);
            } 
        }
        $this->render('signin', array('form' => $form));
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
                    Yii::app()->user->setFlash('sucessful', 'Congratulation. You have signed up successfully');
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
            if (isset($_POST['ResetPasswordForm'])) {
                $form = new ResetPasswordForm;                
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
            } else {
                $this->render('reset_password');
            }
        } else {
            Yii::app()->user->setFlash('fail', 'Invalid URL !');
            $this->redirect(Yii::app()->homeUrl);
        }
    }
}
?>
