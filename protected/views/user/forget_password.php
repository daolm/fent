<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="none"></div>
<div style="font-size: 2em; color:red; text-align: center">Password Recovery</div>

<div class="row">
    <form method="Post">
       <fieldset class="seven centered columns">           
         <div style="height:50px"></div>
         <?php if(Yii::app()->user->hasFlash('sucessful')): ?>
            <div class="success alert">
                <?php echo Yii::app()->user->getFlash('sucessful'); ?>
            </div>
         <?php endif; ?>
         <?php if(Yii::app()->user->hasFlash('fail')): ?>
            <div class="danger alert">
                <?php echo Yii::app()->user->getFlash('fail'); ?>
            </div>
         <?php endif; ?>
          <div class="field">
            <input class="text input" id="email" name='ForgetPasswordForm[arg]' type="text" placeholder="Username or Email or Employee code" />
          </div>
          <div style="height:50px"></div>
          <div class="medium primary btn centered three columns"><input type="submit" value="Submit"></div>
       </fieldset>
    </form>
</div>
<div style="height:150px"></div>