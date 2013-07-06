<div class="row">
    <h1>Favorite list</h1>
    <?php
        $this->renderPartial('/device/_list_device', array('devices' => $favorite_devices));
    ?>
</div>
