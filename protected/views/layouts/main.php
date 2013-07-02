<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />            
        <?php            
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery-ui.css');            
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/gumby.css');            
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/style.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/form.css');
        ?>
        <?php
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-ui.js');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/modernizr-2.6.2.min.js');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/constant_define.js');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/function_define.js');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/search.js');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/request.js');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/notification.js');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/auto_load.js');
            
            Yii::app()->clientScript->registerCoreScript('jquery');
        ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div class="container" id="page">
            <div id="header">                  
                <div class="valign row" style="margin-left: 50px;">
                    <div class="logo">
                        <?php echo CHtml::image(Yii::app()->baseUrl.'/images/framgia.png', null, array('width' => '70')); ?>
                    </div>
                    <div>
                        <h2><?php echo Yii::app()->name; ?></h2>                      
                    </div>
                </div>
            </div> 
            <?php if (!Yii::app()->user->isGuest) {
                $this->renderPartial('/layouts/_deviceNavigation'); 
            }?>
            <div id="content">
                <?php echo $content; ?>
            </div>                        
                    
            <div class="clear"></div>
            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by Framgia.<br/>
                All Rights Reserved.<br/>                        
                <?php echo Yii::powered(); ?>                   
            </div><!-- footer -->
        </div><!-- page -->
        
        <script src='<?php echo Yii::app()->baseUrl; ?>/js/gumby.min.js'></script> 
        <script src='<?php echo Yii::app()->baseUrl; ?>/js/socket.io.js'></script> 
        <script src='<?php echo Yii::app()->baseUrl; ?>/js/notification_client.js'></script> 
        <?php 
            if (!Yii::app()->user->isGuest) {                
        ?>
                <script>
                    $(function(){
                        nc = new NotificationClient("<?php echo Yii::app()->user->getState('redisChannel') ?>");   
                    });
                </script>
        <?php                 
            }
        ?>
    </body>
</html>
