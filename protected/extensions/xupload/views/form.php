<!-- The file upload form used as target for the file upload widget -->
<?php if ($this->showForm) echo CHtml::beginForm($this -> url, 'post', $this -> htmlOptions);?>
<div class="row fileupload-buttonbar">
	<div class="seven columns">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <div class="btn medium primary fileinput-button">                
                <button><?php echo $this->t('1#Add images|0#Choose file', $this->multiple); ?></button>                
                <?php
                if ($this -> hasModel()) :
                    echo CHtml::activeFileField($this -> model, $this -> attribute, $htmlOptions) . "\n";
                else :
                    echo CHtml::fileField($name, $this -> value, $htmlOptions) . "\n";
                endif;
                ?>                
            </div>
        
	</div>
	<div class="five columns">
		<!-- The global progress bar -->
		<div class="progress progress-success progress-striped active fade">
			<div class="bar" style="width:0%;"></div>
		</div>
	</div>
</div>
<!-- The loading indicator is shown during image processing -->
<div class="row"><div class="fileupload-loading"></div>
<br>
<!-- The table listing the files available for upload/download -->
<table class="table table-striped">
	<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
</table>
<?php if ($this->showForm) echo CHtml::endForm();?>
</div>
