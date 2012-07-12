<?php include 'inc/header.php'; ?>
<h1>FlightGear Scenery Object Statistics</h1>
<?php
    $result=pg_query("SELECT count(mo_id) AS count FROM fgsoj_models;");
    $row = pg_fetch_assoc($result);
    $models=$row["count"];

    $result=pg_query("SELECT count(ob_id) AS count FROM fgsoj_objects;");
    $row = pg_fetch_assoc($result);
    $objects=$row["count"];

    $result=pg_query("SELECT count(si_id) AS count FROM fgs_signs;");
    $row = pg_fetch_assoc($result);
    $signs=$row["count"];

echo "<p align=\"center\">The database currently contains <a href=\"models.php\">$models models</a> placed in the scenery as <a href=\"objects.php\">$objects seperate objects</a>, plus $signs taxiway signs.</p>\n";

?>
<table class="main">
  <tr class="main">
    <td>
      <table>
        <tr><th colspan="2">Objects By Country</th></tr>
        <?php
          $query = "SELECT count(ob_id) AS count,co_name,co_code ";
          $query.= "FROM fgsoj_objects,fgs_countries ";
          $query.= "WHERE ob_country=co_code ";
          $query.= "GROUP BY co_code,co_name ";
          $query.= "ORDER BY count DESC ";
          $query.= "LIMIT 20";
          $result = pg_query($query);
          while ($row = pg_fetch_assoc($result)){
            echo "<tr>\n";
              echo "<td><a href=\"objects.php?country=".$row["co_code"]."\">".$row["co_name"]."</a></td>\n";
              echo "<td>".$row["count"]."</td>\n";
            echo "</tr>\n";
          }
        ?>
      </table>
    </td>
    <td>
      <table>
        <tr><th colspan="2">Models By Author</th></tr>
        <?php
          $query = "SELECT count(mo_id) as count,au_name,au_id ";
          $query.= "FROM fgs_models,fgs_authors ";
          $query.= "WHERE mo_author=au_id ";
          $query.= "GROUP BY au_id,au_name ";
          $query.= "ORDER BY count DESC ";
          $query.= "LIMIT 20";
          $result = pg_query($query);
          while ($row = pg_fetch_assoc($result)){
            echo "<tr>\n";
              echo "<td><a href=\"author.php?id=".$row["au_id"]."\">".$row["au_name"]."</a></td>\n";
              echo "<td>".$row["count"]."</td>\n";
            echo "</tr>\n";
          }
        ?>
      </table>
    </td>
  </tr>
  <tr class="main">
    <td align="center">
      <table>
        <tr><th colspan="2">Recently Updated Objects</th></tr>
        <?php
          $query = "SELECT ob_id,ob_text,ob_modified ";
          $query.= "FROM fgsoj_objects ";
          $query.= "ORDER BY ob_id DESC ";
          $query.= "LIMIT 10";
          $result = pg_query($query);
          while ($row = pg_fetch_assoc($result)){
            echo "<tr>\n";
              echo "<td><a href=\"objectedit.php?id=".$row["ob_id"]."\">".$row["ob_text"]."</a></td>\n";
              echo "<td>".$row["ob_modified"]."</td>\n";
            echo "</tr>\n";
          }
        ?>
      </table>
    </td>
    <td align="center">
      <table>
        <tr><th colspan="2">Recently Updated Models</th></tr>
        <?php
          $query = "SELECT mo_id,mo_name,mo_modified ";
          $query.= "FROM fgsoj_models ";
          $query.= "ORDER BY mo_id DESC ";
          $query.= "LIMIT 10";
          $result = pg_query($query);
          while ($row = pg_fetch_assoc($result)){
            echo "<tr>\n";
              echo "<td><a href=\"modeledit.php?id=".$row["mo_id"]."\">".$row["mo_name"]."</a></td>\n";
              echo "<td>".$row["mo_modified"]."</td>\n";
            echo "</tr>\n";
          }
        ?>
      </table>
    </td>
  </tr>
</table>
<?php include 'inc/footer.php';?>
