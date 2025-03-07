<?php
$this->pageTitle = Yii::app()->name . ' - Contact Us';
$this->breadcrumbs = array(
    'Contact',
);
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'contact-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
?>
<?php echo $form->errorSummary($model, null, null, array('class' => 'erro')); ?>

<h1>Contact Us</h1>

<?php if (Yii::app()->user->hasFlash('contact')): ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('contact'); ?>
    </div>

<?php else: ?>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="form">



        <p class="note">Fields with <span class="required">*</span> are required.</p>

        

        <div class="row">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'subject'); ?>
            <?php echo $form->textField($model, 'subject', array('size' => 60, 'maxlength' => 128)); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'body'); ?>
            <?php echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
        </div>

        <?php if (CCaptcha::checkRequirements()): ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'verifyCode'); ?>
                <div>
                    <?php $this->widget('CCaptcha'); ?>
                    <?php echo $form->textField($model, 'verifyCode'); ?>
                </div>
                <div class="hint">Please enter the letters as they are shown in the image above.
                    <br/>Letters are not case-sensitive.</div>
            </div>
        <?php endif; ?>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Submit'); ?>
        </div>



    </div><!-- form -->

<?php endif; ?>
<?php $this->endWidget(); ?>
