<?php 
include '../functions.php'; 
include '../quiz/quiz.php';
$title = "Palauan Dictionary Page";
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
  while($row = mysqli_fetch_row($r)) {
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

  $pbox = search_box("Palauan","plookup");
  $ebox = search_box("English","elookup");
  echo "<div class='tab'><table><tr><td>$pbox</td><td>$ebox</td></tr></table></div>\n";

  echo "<h2>Table of Contents for the 1990 NEW PALAUAN-ENGLISH DICTIONARY</h2>\n"; 
  echo "<table id='toc'><!--toc-->\n";
  $colone = "";
  $coltwo = "";
  for($i=0; $i<(int)sizeof($links)/2; $i++) {
    $colone .= $links[$i] . "<br>\n";
    $coltwooffset = (int)sizeof($links)/2+$i+1;
    if (isset($links[$coltwooffset])) {
      $coltwo .= $links[$coltwooffset] . "<br>\n";
    } else {
      $coltwo .= "<br>\n";
    }
    #echo "<tr><td id='toc'>" . $links[$i] . "</td><td id='toc'>" . $links[(int)sizeof($links)/2+$i+1] . "</td></tr>\n";
  }
  echo "<tr><td>$colone</td><td>$coltwo</td></tr>\n";
  echo "</table><!--toc-->\n";
  # add the quick jumps
  echo "<h3>Or jump to the page containing:</h3>\n";
  echo "<div class='tab'><table><tr><td>$pbox</td><td>$ebox</td></tr></table></div>\n";
}

?>

    <div id="content-container">
    <div class='bothtab'>
        
    <?php
      $query = NULL;
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['image'])) {
          $image = $_POST['image'];
          $pageno = $_POST['pageno'];
        } elseif (isset($_POST['plookup'])) {
          $search = $_POST['plookup'];
          $pageno = get_dict_page($search);
          $image = get_dict_image($pageno);
          $query = "P $search ";
          echo "<h3>Searching for $search</h3>";
        } elseif (isset($_POST['elookup'])) {
          $search = $_POST['elookup'];
          $pageno = get_dict_page($search,"e");
          $image = get_dict_image($pageno);
          $query = "E $search";
          echo "<h3>Searching for $search</h3>";
        } else {
          echo "wtf";
          $pageno = 355; // just show a random page
          $image = get_dict_image($pageno);
        }
          $page = show_page($image,$pageno);
      } else {
        echo "<div id='aside'>
              Acknowledgments:
              <ul>
                <li>Lewis S. Josephs
                <li>Masa-aki Emesiochel
                <li>Fr. Edwin G. McManus, S.J.
                <li>University of Hawaii Press
                <li>David Ramarui
                <li>Oikang Sebastian
                <li>Dr. A. Capell
                <li>Cecilia H. Hendricks
                <li>Clayton Carlson
                <li>et al. (see preface) 
              </ul>
              <span id='right'><a href='/misc/legal.txt'>Legal Disclaimer</a></span>
              </div>
              <div id='content'>
              "; 
        show_toc();
        echo "</div> <!--content-->\n";
      }
      belau_footer('josephs.php', ": $page $query"); 
    ?>
    </div>
    </div>

</body>
</html>
