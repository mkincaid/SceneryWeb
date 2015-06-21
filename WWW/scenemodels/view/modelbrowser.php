<?php
require 'view/header.php';
?>

<h1><?php echo $title;?></h1>
<table>
    <tr class="bottom">
        <td align="center">
        <a href="app.php?c=Models&a=browse&offset=<?php echo $offset-$pagesize;if (isset($modelGroupId)) {echo "&amp;shared=".$modelGroupId;}?>">Prev</a>
        <a href="app.php?c=Models&a=browse&offset=<?php echo $offset+$pagesize;if (isset($modelGroupId)) {echo "&amp;shared=".$modelGroupId;}?>">Next</a>
        </td>
    </tr>
    <tr>
        <td>
        <script type="text/javascript">var noPicture = false</script>
        <script src="inc/js/image_trail.js" type="text/javascript"></script>
        <div id="trailimageid" style="position:absolute;z-index:10000;overflow:visible"></div>
<?php
        foreach ($modelMetadatas as $modelMetadata) {
?>
            <a href="/app.php?c=Models&a=view&id=<?php echo $modelMetadata->getId();?>">
            <img title="<?php echo $modelMetadata->getName().' ['.$modelMetadata->getFilename().']';?>"
                src="modelthumb.php?id=<?php echo $modelMetadata->getId();?>" width="100" height="75"
                onmouseover="showtrail('modelthumb.php?id=<?php echo $modelMetadata->getId();?>','','','1',5,322);"
                onmouseout="hidetrail();"
                alt="" />
        </a>
<?php
        }
?>
        </td>
    </tr>
    <tr class="bottom">
        <td align="center">
        <a href="app.php?c=Models&a=browse&offset=<?php echo $offset-$pagesize;if (isset($modelGroupId)) {echo "&amp;shared=".$modelGroupId;}?>">Prev</a>
        <a href="app.php?c=Models&a=browse&offset=<?php echo $offset+$pagesize;if (isset($modelGroupId)) {echo "&amp;shared=".$modelGroupId;}?>">Next</a>
        </td>
    </tr>
</table>
<?php require 'view/footer.php';?>