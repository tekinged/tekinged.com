<?php 
include 'functions.php'; 
include 'Pagination.php';
$title="Palauan Word Count Frequency";
html_top($title);
belau_header($title);

$GLOBALS['DEBUG'] = false;

function clean_string($string) {
    error_reporting(E_ALL);

    $string = strtolower($string);

    # helper search-replace function
    function s_r($s,$r,&$string) {
      #print "s_r with $s, $r, $string";
      $string = preg_replace($s,$r,$string,-1,$count);
      if ($count>0) { Debug("Replaced $count of $s with $r"); }
      return $string;
    }

    // remove punctuation
    $string = preg_replace('/[\W]+/', ' ', $string); // remove punctuation

    // replace variants and misspellings
    $query = "select a,b from pos_var union select a,b from misspellings";
    $result = query_or_die($query);
    while($row = mysql_fetch_assoc($result)) {
        extract($row);
        $target = '/\b'.$b.'\b/'; 
        s_r($target,$a,$string);
    }

    // treat some expressions as a single word
    $query = "select pal from all_words3 where pos like 'expression';";
    $result = query_or_die($query);
    while($row = mysql_fetch_assoc($result)) {
        $pal = $row['pal'];
        $new_pal = ' ' . str_replace(" ","_",$pal) . ' '; # make it a single word using underscore
        $target = '/\b'.$pal.'\b/'; 
        s_r($target, $new_pal, $string);
    }

    // treat reng expressions as a single word
    $query = "select Palauan from tags_idioms where Palauan rlike 'rengul'";
    $result = query_or_die($query);
    while($row = mysql_fetch_assoc($result)) {
        $pal = $row['Palauan'];
        $pal = str_replace("(","",$pal); $pal = str_replace(")","",$pal); # rip off parens
        $pal = substr($pal,0,-2); # rip off the 'ul'
        $new_pal = ' ' . str_replace(" ","_",$pal) . ' '; # make it a single word using underscore
        $target = '/\b'.$pal.'\w*\b/';  # match it regardless of a renguk/rengmam/etc.
        s_r($target,$new_pal,$string);
    }
    #echo "string is " . strlen($string . "<br>";
    //print_table("select * from idioms where palauan rlike 'rengul' && not isnull(literal) order by rand() limit 7");
    return $string;
}

# problem with grouping now: diak is going into dui . . . 
function group_words($words,$count) {
  $begin = time();
  $elapsed = time() - $begin; 
  echo "Grouping $count words took $elapsed seconds<br>\n";
  return $grouped;
}

function get_frequencies() {
    $query = "select palauan from all_paltext";
    #$query .= "limit 100"; # go faster for testing
    #echo $query . "<br>\n";
    PDebug( "Running query $query");
    echo "<p class='tab'>";
    $result = query_or_die($query);
    $full_str = "";
    while($row = mysql_fetch_row($result)) {
        $full_str .= $row[0];
    }
    # clean string isn't working ... Argh!
    #$full_str = clean_string($full_str);
    $full_str = strtolower($full_str); # at least remove upper/lower issues
    $words = str_word_count($full_str, 1, '_');
    $wc = count($words);
    Debug("Need to check $wc words");
    $words = array_count_values($words);
    $distinct_words = count($words);
    #$words = group_words($words,$wc);
    $tr = count($words);
    return array($words,$tr,$wc,$distinct_words);
}

function intro() {
    echo "<div id='content-container'>\n";
    echo "<h2>$title</h2>\n";
    echo "<p class='tab'>This page does a word count across Palauan language text snippets that have been collected online.  ";
    echo "It includes facebook and other blog comments and posts as well as other miscellaneous sources.";
    echo "It currently does a fair bit of processing so you might need to be a bit patient while you wait for it.</p>\n";
    echo "<p class='tab'>A very cool <a href=/images/frequency.png>visual word map</a> of this (excluding some of the little words) ";
    echo "was kindly created by <a href=https://scholar.google.com/citations?user=V34LNhMAAAAJ>Nathan DeBardeleben</a>.";
    echo "<p class='tab'>In early 2016, we published a version of this list after doing various processing on it to merge multiple
          entries to try to get a more accurate and useful list: <a href=/3000.php>3000 Most Frequently Used Palauan Words</a>. ";

    $sources = table_to_string(
      "SELECT source,SUM(LENGTH(palauan) - LENGTH(REPLACE(palauan, ' ', ''))+1) AS words FROM all_paltext GROUP BY source ORDER BY words DESC;",
      false, false,false);
    echo "<p class='tab'>Here are the sources for this analysis as well as the words from each: $sources"; 
    echo "<p class='tab'>And here is the list of words:";
    flush();
}

function get_vars() {
    // pull the values from the post action and the various current values of things
    $limit = 500;  /* default limit if not set */
    $table = 'freq'; /* this is a fake table but just set it since Pagination class expects it */
    if(isset($_GET['page']) && is_numeric(trim($_GET['page']))){$page = mysql_real_escape_string($_GET['page']);}else{$page = 1;}
    if (isset($_POST['limit']) && $_POST['limit']) {
        $limit = $_POST['limit'];
        $page = 1; // if user changed rows per page, reset page to page 1
        PDebug("Using post limit $limit");
    } else if (isset($_GET['limit']) && $_GET['limit']) {
        $limit = $_GET['limit'];
        PDebug("Using get limit $limit");
    }
    if(!isset($_GET['orderby']) OR trim($_GET['orderby']) == ""){
        $orderby='count';
    } else {
        $orderby=mysql_real_escape_string($_GET['orderby']);
    }
    if(!isset($_GET['sort']) OR ($_GET['sort'] != "ASC" AND $_GET['sort'] != "DESC")){
        //default sort
            $sort="DESC";
        }else{	
            $sort=mysql_real_escape_string($_GET['sort']);
    }
    PDebug( "limit: $limit, page: $page, orderby: $orderby, table: $table<br>\n");
    $sort = "ASC"; # let's turn off DESC sort
    return array($limit,$page,$orderby,$table,$sort);
}


function get_header_fields($limit,$orderby,$sort,$table) {
    $word_field = columnSortArrows('word',"Word","freq",$limit,$orderby,$sort);
    $count_field = columnSortArrows('count',"Count","freq",$limit,$orderby,$sort);
    $perc_field = columnSortArrows('perc', "Percent","freq",$limit,$orderby,$sort);
    return array($word_field,$count_field,$perc_field);
}
function get_plinks($Pagination,$page,$limit,$tr,$table,$orderby) {
    $startrow = $Pagination->getStartRow($page,$limit);
    $pagination_links = $Pagination->showPageNumbers($tr,$page,$limit,$table,$orderby);
    $prev_link = $Pagination->showPrev($tr,$page,$limit,$table,$orderby);
    $next_link = $Pagination->showNext($tr,$page,$limit,$table,$orderby);
    return array($startrow,$pagination_links,$prev_link,$next_link);
}

intro();

/* set up some pagination, get words first since we need tr */
/*$begin = time();
list($words,$tr,$wc,$distinct) = get_frequencies();
$Pagination = new Pagination();
list($limit,$page,$orderby,$table,$sort) = get_vars();
list($startrow,$pagination_links,$prev_link,$next_link) = get_plinks($Pagination,$page,$limit,$tr,$table,$orderby);
list($word_field,$count_field,$perc_field) = get_header_fields($limit,$orderby,$sort,$table);

// sort the words appropriately 
if ($orderby=='count' || $orderby=='perc') {
    PDebug( "sort by arsort<br>");
    arsort($words);
} else {
    PDebug( "sort by ksort<br>");
    ksort($words);
}

// start the table 
PDebug( "$table, $limit, $orderby, $sort<br>");
echo "\n<table>\n<tr>\n";
echo "<th></th><th>$word_field</th>\n<th>$count_field</th>\n<th>$perc_field</th></tr>\n";

// now each row 
$tr_class = "class='odd'";
PDebug( "orderby is $orderby split from $startrow to $limit<br>\n");
$rows = array_splice($words,$startrow,$limit);
$i = 1;
foreach ($rows as $key => $value) {
    $key = str_replace('_', ' ', $key); # remove underscores we used to group expressions for counting
    $perc = number_format(100*$value/$wc,3,'.','');
    echo "<tr $tr_class><td>$i</td><td class='col1'>$key</td><td class='col2'>$value </td><td class='col3'>$perc</td></tr>\n";
    $tr_class = ($tr_class == "class='odd'") ? "class='even'" : "class='odd'";
    $i++;
}
echo "</table>\n";  // end the table 
$elapsed = time() - $begin; 
echo "<p>Word frequency from a set of $wc total words with $distinct distinct words grouped in $tr groups took $elapsed seconds.</p>\n";
*/


/*
// add the button links 
if(!($prev_link==null && $next_link==null && $pagination_links==null)){
    $rows_per_page= "<form method='post'>&nbsp;Items per page: <input type='text' maxlength='5' size='5' name='limit' value='$limit'></form>";
    echo '<div class="pagination">'."\n";
    echo $prev_link;
    echo $pagination_links;
    echo $next_link;
    echo $rows_per_page;
    echo '<div style="clear:both;"></div>'."\n";
    echo "</div>\n";
}
echo "</p>\n";
*/

echo "<p>Does Palauan word frequency follow a <a href='http://bit.ly/YobBwa'>zipf distribution</a> as do <a href=https://colala.bcs.rochester.edu/papers/piantadosi2014zipfs.pdf>most languages</a>?</p>\n";

include("tmp/freq_table.html");


belau_footer(curPageURL()); 
?>
</body>
</html>
