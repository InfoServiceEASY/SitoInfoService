<div class="list-group list-group-flush">
    <?php 
    for($i=0; $i < count($sidebar_link); $i++){
        echo "<a href= $sidebar_link[$i] class='list-group-item list-group-item-action bg-light'>$sidebar_text[$i]</a>";
    }?>
</div>    