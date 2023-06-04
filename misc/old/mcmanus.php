<?php 
include '../functions.php'; 
include '../misc/quiz.php';
$title = "McManus Palauan Dictionary";
html_top($title);
belau_header($title);
reset_quiz();

$GLOBALS['DEBUG'] = false;

function search_box($lang,$name) {
    return "  
               <form method='post'>
                <span itemprop='articleBody'>Find $lang word</span>: <input type='text' name='$name' />
                <input type='submit' />
              </form>
        ";
}

function show_page($page,$pageno) {
        $navlinks = '';
        if ($pageno > 0) {
          $navlinks .= "Previous page: " . dict_link($pageno-1);
        }
        if ($pageno < 559) {
          $navlinks .= "Next page: " . dict_link($pageno+1);
        }
        $pbox = search_box("Palauan","plookup");
        $ebox = search_box("English","elookup");
        $this_url = curPageURL();
        echo "<br><a href='$this_url'>Table of Contents</a><br>\n";
        echo "<div class='tab'><table><tr><td>$navlinks</td><td>$pbox</td><td>$ebox</td></tr></table></div><br>\n";
        echo "<img class='dict' src='$page'>\n";
        echo "<br><div class='tab'>$navlinks</div><br>\n";
        return $page;
}

function show_toc() {
  echo "<h2>Table of Contents for the 1990 NEW PALAUAN-ENGLISH DICTIONARY</h2>\n"; 
  $extras = array();
  $extras['Cover'] = 0;
  $extras['Title'] = 6;
  $extras['McManus bio'] = 8;
  $extras['About Lewis Josephs'] = 555;
  $extras['Preface'] = 14;
  $extras['Intro'] = 20;
  $extras['Parts of Speech'] = 28;
  $extras['Pronounciation'] = 39;
  $extras['Abbreviations'] = 52;
  $links = array();
  foreach ($extras as $extra => $page) {
    $links[] = dict_link($page,$extra);
  }
  $q = "select * from dict_pages";
  $r = query_or_die($q);
  $adjust = 0;
  while($row = mysql_fetch_row($r)) {
    $page = $row[0] - 1 + 60 + $adjust;
    if ($page==408) { 
      $link = dict_link($page+2,"English to Palauan Section");
      $adjust = 1;  // change adjust when we get to english section
    } else {
      $word = $row[1];
      $link = dict_link($page,"Page $page: $word -");
    }
    $links[] = $link;
  }

  echo "<table id='toc'><!--toc-->\n";
  for($i=0; $i<(int)sizeof($links)/2; $i++) {
    $colone .= $links[$i] . "<br>\n";
    $coltwo .= $links[(int)sizeof($links)/2+$i+1] . "<br>\n";
    #echo "<tr><td id='toc'>" . $links[$i] . "</td><td id='toc'>" . $links[(int)sizeof($links)/2+$i+1] . "</td></tr>\n";
  }
  echo "<tr><td>$colone</td><td>$coltwo</td></tr>\n";
  echo "</table><!--toc-->\n";
  # add the quick jumps
  echo "<h3>Or jump to the page containing:</h3>\n";
  $pbox = search_box("Palauan","plookup");
  $ebox = search_box("English","elookup");
  echo "<div class='tab'><table><tr><td>$pbox</td><td>$ebox</td></tr></table></div>\n";
}

?>

    <div id="content-container">
    <div class='bothtab'>
        
    <?php
        $pic='misc/mcmanus/mcmanus.jpg';
        $big='misc/mcmanus/McManus.jpg';
        echo "<div id='aside'>
              <center>
              <a href='$big'>
                <img src='$pic' />
              </a>
              </center>
              </div>
              "; 
        echo "<div id='content'>\n";
        $bio = dict_link(8,"here");
        echo "Father McManus and David Ramarui wrote the first Palauan-English dictionary in 1948 which was typewritten by Cecilia Hendricks in 1950.  This dictionary probably
              should not be used as a language reference since it is very different from, and has been superseded by, the Josephs 1990 <a href='/misc/dictpages.php'>Dictionary</a> and 
              Reference Grammars.  However, it 
              remains a fascinating historical reference.
              <ul>
              <li>Read a bio of Father McManus $bio or <a href='https://www.manresa-sj.org/stamps/1_McManus.htm'>here</a>.
              <li>Download the dictionary as a 30MB <a href='misc/mcmanus/mcmanus.pdf'>PDF</a>
              </ul>\n";
        echo "Or view individual pages one at a time (grammar notes start on page 64:<br>\n"; 
        echo "<ul>\n";
        foreach (glob("mcmanus/pngs/*.png") as $filename) {
          echo "<li><a href='$filename'>$filename</a></li>\n";
        }
        echo "</ul>\n";
        echo "</div> <!--content-->\n";
      belau_footer('mcmanus.php',NULL); 
    ?>
    </div>
    </div>

</body>
</html>
