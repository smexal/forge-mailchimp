<?php if(!class_exists('raintpl')){exit;}?><div class="forge-mailchimp-form">
    <p class="feddow"><?php echo $before;?></p>
    <form class="ajax" action="<?php echo $action;?>" callback="forgeMailchimp.formCallback">
    <?php echo $form;?>
    </form>
</div>
