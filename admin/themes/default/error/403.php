<?php
$pageTitle = __('403: Forbidden');
echo head(array('bodyclass'=>'error403 primary', 'title'=>$pageTitle)); ?>

<div id="primary" class="file-forbidden">
<?php echo flash(); ?>

    <p><?php echo __('Sorry, you do not have permission to access this page.'); ?></p>
    
    <p><?php 
    $siteAdministratorEmail = '<a href="mailto:'. option('administrator_email') . '">' . __('site administrator') . '</a>';
    echo __('If you think you should have permission, try contacting the %s.', $siteAdministratorEmail); ?>
    </p>
</div>
<?php echo foot(); ?>
