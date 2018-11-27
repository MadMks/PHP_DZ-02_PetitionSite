<div class="col-8">
    <?php foreach($petitions as $petition) {?>
        <div><?php echo($petition->title); ?></div>
        <div><?php echo($petition->author_email); ?></div>
        <div><?php echo($petition->count); ?></div>
        <div>TODO: инфо о петиции</div>
        <div>TODO: подписать петицию</div>
        <br><br>
    <?php } ?>
</div>

<div class="col-4">
test col
</div>