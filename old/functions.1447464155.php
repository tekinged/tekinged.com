<?php

function html_top_no_body($title,$js=false,$extra=Null) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=utf-8 />
        <link rel='stylesheet' type='text/css' href='/css/belau.css' />
        <link rel='apple-touch-icon' sizes='57x57' href='/apple-touch-icon-57x57.png?v=3'>
        <link rel='apple-touch-icon' sizes='60x60' href='/apple-touch-icon-60x60.png?v=3'>
        <link rel='apple-touch-icon' sizes='72x72' href='/apple-touch-icon-72x72.png?v=3'>
        <link rel='apple-touch-icon' sizes='76x76' href='/apple-touch-icon-76x76.png?v=3'>
        <link rel='apple-touch-icon' sizes='114x114' href='/apple-touch-icon-114x114.png?v=3'>
        <link rel='apple-touch-icon' sizes='120x120' href='/apple-touch-icon-120x120.png?v=3'>
        <link rel='apple-touch-icon' sizes='144x144' href='/apple-touch-icon-144x144.png?v=3'>
        <link rel='apple-touch-icon' sizes='152x152' href='/apple-touch-icon-152x152.png?v=3'>
        <link rel='apple-touch-icon' sizes='180x180' href='/apple-touch-icon-180x180.png?v=3'>
        <link rel='icon' type='image/png' href='/favicon-32x32.png?v=3' sizes='32x32'>
        <link rel='icon' type='image/png' href='/android-chrome-192x192.png?v=3' sizes='192x192'>
        <link rel='icon' type='image/png' href='/favicon-96x96.png?v=3' sizes='96x96'>
        <link rel='icon' type='image/png' href='/favicon-16x16.png?v=3' sizes='16x16'>
        <link rel='manifest' href='/manifest.json?v=3'>
        <link rel='shortcut icon' href='/favicon.ico?v=3'>
        <meta name='apple-mobile-web-app-title' content='tekinged.com'>
        <meta name='application-name' content='tekinged.com'>
        <meta name='msapplication-TileColor' content='#da532c'>
        <meta name='msapplication-TileImage' content='/mstile-144x144.png?v=3'>
        <meta name='theme-color' content='#ffffff'>
    ";
  
    if ($js) {
      echo "
        <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js' async></script>
        <script type='text/javascript' src='/js/jquery.jplayer.min.js' async></script>
        <script type='text/javascript' src='/js/jplayer.simple.js' async></script>
        <script type='text/javascript' src='/js/belau.js' async></script>
        <script src='//tinymce.cachefly.net/4.1/tinymce.min.js' async></script>
        <script>tinymce.init({
            menubar: false,
            toolbar: false,
            selector:'textarea'
          });</script>
      ";

    }

    if ($extra) {
      echo $extra;
    }

    echo "
        <meta name='google-site-verification' content='zE2LvYvBxsi-6WHDEhaWqwbtu1KMc60GTAZOs6J_MDw' />
        <title>Palauan Language Online: $title</title>
    </head>
    ";

    # google analytics. No longer needed since Dreamhost does this for me.
    # need it again though since we turned off the dreamhost doing it for us
    #echo "
    #  <script>
    #  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    #      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    #      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    #    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
#
#        ga('create', 'UA-56065529-1', 'auto');
#        ga('send', 'pageview');
#      </script>
#      ";
}

function html_top($title,$js=false,$extra=Null) {
  html_top_no_body($title,$js);
  echo "<body>\n";
}
  
function start_content() {
  print "<div id='content'>\n";
}

function start_content_container() {
  print "<div id='content-container'>\n";
}

# ends both content and content_container
function end_content() {
  print "</div>\n</div>\n";
}

function end_body_html() {
  print "
  </body>
  </html>
  ";
  mysql_close();  # close the mysql connection explicitly
}

function audio_play_button($filename,$word,$thumb=NULL) {
    $button =  "\t\t\t<span class='overlapoff'><div id='jp_container' class='demo-container'>$word
				\t\t<a href='$filename' class='track'><img src='/images/play2.png' class='playbutton'></a>
				\t\t<a class='jp-play' class='track' href='#'></a>
				\t\t<a class='jp-pause' href='#'><img src='/images/pause.svg' class='playbutton'></a></span>
        \t\t<span class='overlapoff'>$thumb</span>
        \t</div> <!-- jp_container -->\n";
    return $button;
}

function remove_empty_lines($string){
  return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
}

function print_table($query, $show_header=True,$show_titles=True,$add_count=False,$extra_fp=NULL) {
    // sending query
    $result = mysql_query($query);
    if (!$result) {
        print mysql_error();
        die("Query $query to show fields from table failed");
    }

    $fields_num = mysql_num_fields($result);

    list($hdr,$fields) = generate_table_header($result,$show_header,$add_count);
    echo $hdr;
    // printing table rows
    $counter=0;
    while($row = mysql_fetch_row($result))
    {
        $bg = "FFFFFF";
        if ($counter++%2==1) $bg = "C0C0C0";
        echo "<tr bgcolor='$bg'>";

        // $row is array... foreach( .. ) puts every element
        // of $row to $cell variable
        if ($add_count) { echo "<td>$counter</td>"; }
        for($i=0; $i<sizeof($row); $i++) {
            $cell = $row[$i];
            # remove empty blank lines
            # convert line breaks to <br>
            $cell = remove_empty_lines($cell);
            $cell = nl2br($cell);
            if ($extra_fp !== NULL and $i==0) { $cell = $extra_fp($row); }
            if ($show_titles) {
              $title = strlen($cell) ? "title='$cell: $fields[$i] of $row[0]'" : ""; 
            } else {
              $title = '';
            }
            echo "<td $title>$cell</td>";
        }

        echo "</tr>\n";
    }
    mysql_free_result($result);
    echo "</table>";
}

function not_vulgar() {
  return "(isnull(vulgar) or vulgar=2)";
}

function make_thumbnail($filename) {
  $thumb = pics_dir() . "/thumbs/" . basename($filename);
  Debug("Using $thumb");
  return "<a class='thumbnail' href='$filename'>"
       . "<img src='$thumb' class='playbutton'>"
       . "<span><img src='$filename'><br>$filename</span></a>";
}

function Warn($msg) {
    echo "WARN $msg<br>\n";
}

function print_contributors() {
  $q = "select contributor from contributors";
  $r = query_or_die("select contributor,committee,detective,archiver,spokes,web,soc from contributors order by contributor");
  echo "Contributors:<ul>\n";
  while($row = mysql_fetch_row($r)) {
    $c = $row[0];
    $tags = array(); 
    if ($row[1]==1) {
      $tags[] = 'C';  
    }
    if ($row[2]==1) {
      $tags[] = 'D';
    } 
    if ($row[3]==1) {
      $tags[] = 'A';
    } 
    if ($row[4]==1) {
      $tags[] = 'S';
    }
    if ($row[5]==1) {
      $tags[] = 'W';
    }
    if ($row[6]==1) {
      $tags[] = 'M';
    }
    $tag = join(",",$tags);
    echo "<li> $row[0] <b><sup>$tag</sup></b></li>\n";
  }
  echo "</ul>\n";
  echo "<b>A : Archiver</b><br>\n";
  echo "<b>C : Committee Member</b><br>\n";
  echo "<b>D : Word Detective</b><br>\n";
  echo "<b>M : Social Media</b><br>\n";
  echo "<b>S : Spokesperson</b><br>\n";
  echo "<b>W : Webmaster</b><br>\n";
}

function is_johnbent() {
  $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
  $jb = array();
  $jb[] = "69.254.135.15";
  $jb[] = "65.125.35.19";
  foreach ($jb as $jip) {
    if ($ip == $jip) {
      return 1;
    }
  }
  return 0;
}

function Debug($msg,$force=false) {
  if ($force || (is_johnbent() && isset($GLOBALS['DEBUG']) && $GLOBALS['DEBUG'])) { 
    $debug_file = "/tmp/johnbent.debug";
    echo "DEBUG $msg<br>\n"; 
    #file_put_contents($debug_file, $msg, FILE_APPEND | LOCK_EX);
  } else if ( is_johnbent() ) {
    # this breaks things however if we are doing json crap
    # don't display but hide in source
    #echo "<script type='text/plain' hidden>
    #      $msg
    #      </script>\n";
  }
}

function info($msg) {
    #echo "<h3>$msg</h3>\n";
    echo "$msg\n";
}


function rome($N){ 
        $c='IVXLCDM'; 
        for($a=5,$b=$s='';$N;$b++,$a^=7) 
                for($o=$N%$a,$N=$N/$a^0;$o--;$s=$c[$o>2?$b+$N-($N&=-2)+$o=1:$b].$s); 
        return $s; 
}

function get_dict_page($target,$direction='p') {
  $start = 0;
  $adjust = 0;
  if ($direction=='e') {
    $start = 350;
    $adjust = 1;
  }
  $q = "select * from dict_pages where id >= $start";
  $id = search_dict_pages($q,$target);
  $page = $id - 2 + 60 + $adjust; 
  Debug( "$target is found on page $page");
  return $page;
}

function search_dict_pages($q,$target) {
  $r = query_or_die($q);
  while($row = mysql_fetch_row($r)) {
    $id = $row[0];
    $marker = $row[1];
    Debug("Compare $target to $marker");
    if (strcasecmp($target,$marker) <= 0) {
      break;
    }
  }
  return $id;
}

function get_examples_link($page,$target) {
  $html_str = "<form class='formlink' action='/misc/".$page.".php' method='post' >\n";
  $html_str .= "\t<input class='formlink' style='display:none;' name='filter' type='text' value='".$target."' >\n";
  $html_str .= "\t<input class='formlink' id='view_button' type='submit' value='" .strtoupper($target) ."'>\n";
  $html_str .= "</form>\n";
  return $html_str;
}

function get_kerresel_link($target) {
  $q = "select id,last from dict_pages_kerresel";
  $pageno = search_dict_pages($q,$target);
  $html_str = "<form class='formlink' action='/books/kerresel.php' method='post' >\n";
  $html_str .= "\t<input class='formlink' style='display:none;' name='pageno' type='text' value='".$pageno."' >\n";
  $html_str .= "\t<input class='formlink' id='view_button' type='submit' value='Page ".$pageno."' >\n";
  $html_str .= "</form>\n";
  return $html_str;
}

function get_dict_link($target) {
  return dict_link(get_dict_page($target));
}

function get_dict_image($page) {
  return sprintf("/misc/images/dict-%03d.png", $page);
}

function dict_link($page,$value=NULL) {
  $pageno = $page - 57;
  if ($pageno < 0) {
    #$pageno = rome($pageno); 
  }
  if ($value==NULL) {
    $value = "Page $pageno";
  }
  $image = get_dict_image($page);
  $dict_link = dict_button_as_link($image,$value,$page);
  return $dict_link;
}

function dict_button_as_link($image,$value,$page){
    $html_str = "<form class='formlink' action='/misc/dictpage.php' method='post' >\n";
    $html_str .= "\t<input class='formlink' style='display:none;' name='image' type='text' value='".$image."' >\n";
    $html_str .= "\t<input class='formlink' style='display:none;' name='pageno' type='text' value='".$page."' >\n";
    $html_str .= "\t<input class='formlink' id='view_button' type='submit' value='".$value."' >\n";
    $html_str .= "</form>\n";
    return $html_str;
}

function button_as_link($word,$direction=NULL,$value,$page=NULL){
    $config = array(
      'page' => $page,
      'search' => $word,
      'direction' => $direction,
      'string' => $value,
      'field'  => 'lookup',
    );
    return real_button_as_link($config);
}

function real_button_as_link($config) {
    $page = $config['page'];
    $search = $config['search'];
    $direction = $config['direction'];
    $string = $config['string'];
    $field = $config['field'];
    
    if ($page == NULL) {
      $page = "/"; # our main search page
      //$page = $_SERVER['REQUEST_URI'];
    }
    $html_str = "<form class='formlink' action='$page' method='post' >\n";
    $html_str .= "\t<input class='formlink' style='display:none;' name='".$field."' type='text' value='".$search."' >\n";
    if($direction != NULL) {
      $html_str .= "\t<input class='formlink' style='display:none;' name='direction' type='text' value='".$direction."' >\n";
    }
    $html_str .= "\t<input class='formlink' id='view_button' type='submit' value='".$string."' >\n";
    $html_str .= "</form>\n";
    return $html_str;
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function add_submit($found,$direction) {
    $ep_selected = ''; 
    $pe_selected = ''; 
    $pp_selection = '';
    if ($direction == 'pe') {
      $pe_selected = 'selected';
    } else if ($direction == 'pp') {
      $pp_selected = 'selected';
    } else {
      $ep_selected = 'selected';
    }
    $another = ($found) ? " another" : "";
    echo "  
            <div class='tab'> <!-- submit box -->  
               <form method='post'>
                <h2><span itemprop='articleBody'>Search for" . $another . " word</span>: <input type='text' name='lookup' onkeyup=\"liveSearch(this.value,'" . $direction . "')\" /></h2>
                <div id='livesearchdiv'></div>
                <div class='tab'>
                <select name='direction'>
                 <option value='pe' $pe_selected>Palauan -> English</option>
                 <option value='pp' $pp_selected>Palauan -> Palauan</option>
                 <option value='ep' $ep_selected>English -> Palauan</option>
                </select>
                <input type='submit' />
                </div><!-- tab -->
              </form>
            </div> <!-- submit box -->
        ";
}

function add_input($label,$field,$large,$default=NULL) {
    if ($large) {
      $f = "<textarea rows='3' cols='40' name='$field'>$default</textarea>";
    } else {
      $f = "<input type='text' size='40' name='$field' value='$default' />";
    }
    echo "$label<div class='tab'>$f</div><br>\n";
}

function addlargeinput($msg,$placeholder='') {
  // should be able to use placeholder to put the initial msg but it isn't working....
  echo "
    <form method='post'>
      <textarea rows='12' cols='80' name='lookup' placeholder='$placeholder'>$msg</textarea>
      <br>
      <input type='submit' value='Check Spelling' />
    </form>
      ";
}

function scrape_ip() {
  $server = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
  $proxy = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "";
  $user = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : "";
  $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
  Debug("Scraped ip $server");
  return array($server,$proxy,$user,$agent);
}

function spelllog($string) {
  list($s,$p,$u,$a) = scrape_ip();
  $query = "INSERT INTO log_spellcheck (query,ip,agent,user,proxy) VALUES ('$string','$s','$a','$u','$p')";
  $result = mysql_query($query) or warn("Error in mysql_query for spelllog.  Probably string too long...");
}

function querylog($target,$found,$direction,$notes) {
    list($s,$p,$u,$a) = scrape_ip();
    $query = "INSERT INTO log_query (query,found,direction,notes,ip,agent,user,proxy) VALUES ('$target','$found','$direction','$notes','$s','$a','$u','$p')";
    $result = mysql_query($query) or warn(mysql_error()."<br />".$query);
}

// use this function to determine which visitors are bots
function _bot_detected() {
  if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
    return TRUE;
  }
  else {
    return FALSE;
  }
}

function visitlog($extra) {
  list($s,$p,$u,$a) = scrape_ip();
  $page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
  if ($extra) { $page .= $extra; }
  $table = 'log_visit';
  if (_bot_detected()) {
    $table = 'log_bots';
  }
  $query = "INSERT INTO $table (page,ip,agent,user,proxy) VALUES ('$page','$s','$a','$u','$p')";
  Debug($query);
  $result = mysql_query($query) or warn(mysql_error()."<br />".$query);
}

// this only works for select queries and not for inserts
function query_or_die($query) {
    $begin = microtime(true);
    $result = mysql_query($query);
    $num_rows = mysql_num_rows($result);
    $elapsed = microtime(true) - $begin;
    Debug("query_or_die $query found $num_rows and took $elapsed s");
    if (!$result) { die("Query $query failed"); }
    return $result;
}

Class FuzzyMatch {
    var $word;
    var $score;

    function getWord()  { return $this->word; }
    function getScore() { return $this->score; }

    function FuzzyMatch($word,$score) {
        $this->word = $word;
        $this->score = $score; 
    }
}

function find_fuzzy($input,$column) {
  Debug("Looking for $input in $column");
  // first get the list of possible words
  if ($column == 'pal') {
    $q = "select distinct($column) from all_words3 where length($column)>0 && pos != 'var.' && pos != 'cont.'";
  } else if ($column == 'pdef') {
    $q = "select pdef from pal_list"; 
  } else {
    $q = "select eng from eng_list"; 
  }
  $res = query_or_die($q);
  $array = array();
  while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
    $array[] = $row[0];
  }

  Debug("Fuzzy search for $input in $column in " . count($array) . " words");

  // loop through words to find the closest
  $target_matches = 5;
  $best = array();
  for ($i=0;$i<$target_matches;$i++) {
    $best[$i] = new FuzzyMatch('',1000);
  }
  foreach ($array as $word) {

      // calculate the distance between the input word,
      // and the current word
      $lev = levenshtein($input, $word);

      // if this distance is less than the next found shortest
      // distance, OR if a next shortest word has not yet been found
      if ($lev <= $best[2]->getScore()) { 
          // set the closest match, and shortest distance
          $best[$target_matches] = new FuzzyMatch($word,$lev);
          usort($best,"fuzzy_sort");
          Debug("Set closest so far to $word");
      }
  }
  return $best;
}


function search_allwords($base,$pos_regx) {
    $all_q = "select english,part_of_speech from all_words where palauan like '$base' and $pos_regx";
    list($alls,$num_rows) = check_table($all_q);
    if ($num_rows>0) {
        if ($num_rows>1) {
            Warn("WTF: $all_q return $num_rows rows");
        }
        return (mysql_fetch_row($alls) );
    } else {
        return NULL; 
    }
}

Class Cf {
  var $id;
  var $pal;

  function Cf($i,$p) {
    $this->id = $i;
    $this->pal = strtoupper($p);
  }

  function getMain() {
    return $this->pal;
  }

  function toLink() {
    $config = array(
      'page' => Null, 
      'search' => $this->id,
      'direction' => 'pe',
      'string' => $this->pal,
      'field'  => 'lookup_id',
    );
    return real_button_as_link($config);
  }

}

function pwords_to_plinks($palauan) {
    /*
    if (isset($_SESSION['direction'])) {
      $direction = $_SESSION['direction']; 
    } else {
      $direction = 'pe';
    }
    if ($direction == 'ep') {
      $direction = 'pe';
    }
    */
    Debug("Turning pwords $pdef into plinks.");
    $pwords = preg_split('/\s+/', $palauan);
    $plinks = array();
    foreach ($pwords as $pword) {
        // make them with GET 
      $plinks[] = "<a href=?p=$pword class='pdeflink' >$pword</a>";
        // or with POST
      //$plinks[] = button_as_link($pword,$direction=NULL,$pword,$page=NULL);
    }
    return implode(' ', $plinks);
}

Class PWord {
    var $pal;
    var $eng;
    var $pos;
    var $origin;
    var $oword;
    var $isroot;
    var $pdef;
    var $id;
    
    function PWord($p,$e,$pdef,$po=NULL,$origin=NULL,$id=NULL,$oword=NULL,$root=false) {
        $this->isroot = $root;
        $this->pal = $p; $this->eng = $e; $this->pos = $po; $this->origin=$origin; $this->id=$id;
        $this->pdef = $pdef;
        $this->oword = $oword;
        Debug("$p - Set eng to $e"); 

        // this is where we could turn pdef into a set of links
        if ($pdef != Null) {
          $this->pdef = pwords_to_plinks($pdef);
        }
    }

    function conditional_merge($a,$b,$d) {
      Debug("Merge $a with $b?");
      if ($a != NULL and $b != NULL and strlen($a) > 0 and strlen($b) > 0) {
        return implode($d,array($a,$b));
      } else {
        return (strlen($a) > 0 ? $a : $b);
      }
    }

    function merge($variant) {
      $var = $variant->getWord();
      Debug("Need to merge $var into self " . $this->pal);
      $this->pdef = $this->conditional_merge($this->pdef,$variant->pdef,'<br>');
      $this->pal  = $this->conditional_merge($this->pal,$variant->pal,'<br>/');
    }

    function originToString() {
      $origin = "";
      $closure = True;
      switch($this->origin) {
        case 'E': $origin = "[From English"; break;
        case 'G': $origin = "[From German"; break;
        case 'J': $origin = "[From Japanese"; break;
        case 'S': $origin = "[From Spanish"; break;
        case 'T': $origin = "[From Tagalog"; break;
        case 'M': $origin = "[From Malay"; break;
        case 'Y': $origin = "[From Yapese"; break;
        case NULL:
        case '': 
        case 'native': 
          $origin = ""; $closure = False; break;
        default: $origin = "[From " . $this->origin . "] "; break;
      } 
      if ($closure) {
        if ($this->oword) {
          $origin .= " <i>" . $this->oword . "</i>";
        }
        $origin .= "] ";
      }
      return $origin;
    }

    # this is the function for the PWord
    function toHtml($stem,$aclips,$pics,$text_only=false) {
        
        # combine the pdef into the def
        $this->eng = set_def(Null,$this->eng,$this->pdef,$this->id);
        
        $html = '';
        # if there is audio, create an audio button that holds the word
        if ( isset($aclips[$this->pal]) ) {
          $pword = audio_play_button($aclips[$this->pal],$this->pal,$thumb); 
        } else {
          $pword = $this->pal;
        }

        # collect the extra stuff: the pos, the audio button, the image thumbnail, the origin
        # currently only stems can have thumbnails but all can have audio
        $items = array();
        if ( isset($pics[$this->id]) ) {
          $items[] = make_thumbnail($pics[$this->id]);
        }
        if (strlen($this->pos)>0){
          $items[] = "<i>" . $this->pos . "</i>";
        }
        $origin = $this->originToString();
        if (strlen($this->originToString())>0) {
          $items[] = $this->originToString();
        }

        # if we are printing in text_only (e.g. mobile mode)
        if ($text_only) {
            # a bit of a shitty hack here
            # the PWord class doesn't have a separate var for pdef.
            # instead we munge pdef and edef together before creating PWord
            # if text_only mode, unmunge them in order to rearrange them somewhat...
            if ($this->isroot == true) {
              $pal = strtoupper($this->pal);
            } else {
              $pal = $this->pal;
            }
            if (!$stem) { $tab = "** "; } else { $tab = ""; }
            $def = str_replace("<br>","<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$this->eng);
            $html .= "$tab$pal, $this->pos, $def<br>\n";
            return $html;
        }

        # if we are printing in html mode
        if (!$stem) {
              $eng = $this->eng;
              if ($this->pos == "var." || $this->pos == "cont.") {
                # put variants and contractions in italics since the "definition" is the Palauan word
                $eng = "<i>$eng</i>";
              }
              $html .= "\t\t<tr class='words'><td class='col1-sub'>$pword</td><td class='col2'>$this->pos</td><td class='col3'>$eng</td></tr>\n";
        } else {
            if (count($items)) {
              $other = ", " . implode(', ', $items); 
            } else {
              $other = "";
            }
            # print the stem word in full view mode
            $html .= "<table><tr><td class='col1'>$pword</td><td class='col2'>$other</td><td class='col3'>$this->eng</td></tr></table>\n";
            #echo "\t\t<b>$pword</b>$other &nbsp;:&nbsp; $this->eng\n";
        }
        return $html;
    }

    function getWord() { return $this->pal; }
    function getEng()  { return $this->eng; }
    function getPos()  { return $this->pos; }
    function getId()   { return $this->id;  }
}

Class Entry {
    var $id;
    var $word;
    var $words;
    var $also;
    var $examples;
    var $proverbs;
    var $sentences; # user contributed sentences
    var $cfs;
    var $root;
    var $pdef;

    function getMain() { return $this->word->getWord(); }
    function id() { return $this->id; }
    function root() { return $this->root; }

    function Entry($pal,$eng,$pos,$origin,$db_id,$also,$oword,$root,$pdef) {
        $this->id = $db_id;
        $this->root = $root;
        $this->word = new PWord($pal,$eng,$pdef,$pos,$origin,$db_id,$oword,true);
        $this->words = array();
        $this->also = $also;
        Debug("New entry for $pal $eng $pos $origin");
    }

    function AddCf($cf) {
      $pword = id_to_pword($cf);
      $this->cfs[] = new Cf($cf,$pword);
    }
    
    function AddWord($p,$e,$pdef,$pos,$origin,$id,$oword) {
        Debug("New subentry for $p $e $pos $origin");
        $this->words[] = new PWord($p,$e,$pdef,$pos,$origin,$id,$oword,false);
    } 

    function merge_variants() {

      Debug("Before merging, we have " . count( $this->words) . " subwords.");

      # go through and split all words into two arrays.  One that is vars, one that isn't.
      $vars = array();
      $words = array();
      foreach($this->words as $word) {
        $pal = $word->getWord();
        if ($word->getPos() == 'var.') {
          $pword = $word->getWord();
          $vars[] = $word;
        } else {
          $words[$pal][] = $word; # needs to be an array in case there are homonyms
        }
      }

      #Debug("After segregating:");
      #print_r($words);

      # now go through and merge the vars with their main entries
      foreach($vars as $var) {
        $pal = $var->getWord();
        $ori = $var->getEng();
        Debug("Need to merge $pal into $ori");
        if (array_key_exists($ori,$words)) {
          foreach( $words[$ori] as $word) {
            $word->merge($var);
          }
        } else {
          # must need to merge with root
          $this->word->merge($var);  
        }
      }

      # now we have to copy over the merged words back into the original structure
      $this->words = array(); 
      foreach($words as $word_array) {
        foreach($word_array as $word) {
          $this->words[] = $word;
        }
      }

      Debug("After merging, we have " . count( $this->words) . " subwords.");
    }

    # function shared by proverbs and examples and sentences
    function extrasToHtml($what,$extras,$type) {
      $html = '';
      if (count($extras)==0) { return; } 
      $html .= "\t\t<tr><td colspan='3' class='col3'><div class='tab'><b><i>$what:</b></i></div></td></tr>\n";
      $html .= "\t\t\t<tr><td colspan='3' class='col3'>\n\t\t\t<table><!-- $what -->\n";
      foreach($extras as $extra) {
          $p = pwords_to_plinks($extra->getWord());
          $e = $extra->getEng();
          $i = $extra->getId();
          $a = audio_button($type,$i);
          $html .= "\t\t\t\t<tr><td class='col2'><div class='tab'>$a $p</div></td></tr>\n";
          $html .= "\t\t\t\t<tr><td class='col3'><div class='bigtab'>$e</div></td></tr>\n";
      }
      $html .= "\t\t</table><!-- $what --></td></tr>\n";
      return $html;
    }

    # this is the toHtml for top-level Entry which includes root word, sub words, cf's, examples, proverbs
    function toHtml($aclips,$pics,$text_only=false,$collapse=true) {

        # before we print ourselves to html, let's merge up the variants
        $this->merge_variants();
  
        $html = '';
        # count the amount of extras to help decide whether to collapse this word
        $extra_content = count($this->words) + count($this->examples) + count($this->proverbs) + count($this->sentences) + (count($this->cfs)>0 ? 1 : 0);

        # print out the root word
        $html .= $this->word->toHtml(true,$aclips,$pics,$text_only);
        if ($extra_content>0) {
          $uid = $this->id;
          if (!$text_only) { 
            if ($extra_content>2 && $collapse) { /* put extra content in a collapsible div */
              $html .= "\t<a href=\"javascript:ReverseDisplay('$uid')\"><img src='/images/arrow.png' class='playbutton' onclick='rotateMe(this);'></a>\n";
              $html .= "\t<div id='$uid' style='display:none;'>\n";
            }
            $html .= "\t<table class='tab'><!-- word -->\n"; // even if nothing in table, the empty table makes spacing look nice.
          }

          # now print out the sub words
          if (count($this->words)>0) {
              usort($this->words, "subentry_sort");
              foreach($this->words as $word) {
                  $html .= $word->toHtml(false,$aclips,$pics,$text_only);
              }
          }

          # now the 'See Also' fields and the examples and proverbs
          if (!$text_only) {
            if (count($this->cfs)>0) {
              $links = [];
              usort($this->cfs, "entry_sort");
              foreach($this->cfs as $cf) {
                $links[] = ($cf->toLink());
              }
              $str = "<span id='bigtext'>" . join(", ",$links) . "</span>";
              $html .= "\t\t\t<tr><td colspan='3' class='col3'>\n\t\t\tSee also: $str</td></tr>\n";
            }
            $html .= $this->extrasToHtml("Examples",$this->examples,"example");
            $html .= $this->extrasToHtml("Proverbs",$this->proverbs,"proverb");
            $html .= $this->extrasToHtml("More Examples",$this->sentences,"upload_sentence");
          }
          if (!$text_only) { 
            $html .= "\t</table><!-- word -->\n"; 
            if ($extra_content>2 && $collapse) {
              $html .= "\t</div><!-- divInfo -->\n";
            }
          }
        }
        return $html;
    }

    function extraQuery($table) {
        $q = "select * from $table where ";
        $words = $this->words;
        $words[] = $this->word;
        $id = $this->id;
        $limit=15;
        $added=0;
        foreach($words as $word) {
            $targ = $word->getWord();
            # use lower to make it a case insensitive search
            $q .= "lower(palauan) regexp '" . '[[:<:]]' . "$targ" . '[[:>:]]' ."' || ";
            $added++;
            if ($added >= $limit) { break; }
        }
        $q .= "stem = $id"; 
        $q .= " order by rand() limit 5";
        $r = query_or_die($q);
        return $r;
    }

    function addCfs() {
      $id = $this->id;
      $wherea = "a=$id";
      $whereb = "b=$id";
      foreach($this->words as $word) {
        $id = $word->getId();
        $wherea .= "||a=$id";
        $whereb .= "||b=$id";
      }
      $q = "select b from cf where $wherea union all select a from cf where $whereb";
      $r = query_or_die($q);
      while ($row = mysql_fetch_array($r, MYSQL_NUM)) {
        $this->AddCf($row[0]);
      }
    }

    function addProverbs() {
        $examps = $this->extraQuery("proverbs");
        while($row = mysql_fetch_assoc($examps)) {
            extract($row);
            $this->proverbs[] = new PWord($palauan,"$english<div class='tab'><i>$explanation</i></div>",NULL,NULL,NULL,$id); 
        }
    }

    function addExamples() {
        $examps = $this->extraQuery("examples");
        while($row = mysql_fetch_assoc($examps)) {
            extract($row);
            #print "EXAMPLE $id: $palauan<br>\n";
            $this->examples[] = new PWord($palauan,$english,NULL,NULL,NULL,$id); 
        }
        $more = $this->extraQuery("upload_sentence"); 
        while($row = mysql_fetch_assoc($more)) {
            extract($row);
            $this->sentences[] = new PWord($palauan,$eng,NULL,NULL,NULL,$id); 

        }
    }
}

function id_to_pword($id) {
  $q = "select pal from all_words3 where id=$id";
  $r = query_or_die($q);
  $row = mysql_fetch_row($r);
  return $row[0];
}

function check_action_noun($target,$prefix) {
    $prefix_len = strlen($prefix);
    $beginning = substr($target, 0, $prefix_len);
    if ($beginning==$prefix) {
        $stem = substr($target,$prefix_len);
        list($result,$num_rows) = check_table("SELECT eng from all_words3 where pal like '$stem'");
        if ($num_rows > 0) {
            $row = mysql_fetch_array($result); 
            return $stem;
        }
    }
    return NULL; 
}

function distance($near,$who) {
    return ($near) ? "near $who" : "far from $who";
}

function pronoun_to_english($row) {
    extract($row);
    $what = array();
    $plu = ($plural==1) ? "s" : "";
    if ($thing==1)  { $what[] = "object$plu"; }
    if ($animal==1) { $what[] = "animal$plu"; }
    if ($human==1)  { $what[] = ($plural) ? "people" : "person"; }
    $described = implode('/', $what);

    if ($near_speaker==0 || $near_speaker==1) {
        $position = distance($near_speaker,'speaker');
        $position .= " and ";
        $position .= distance($near_listener,'listener');
    } else { $position = ""; }
    return "$english $described $position";
    //$obj = ($row['thing']) ? "object" : "";
}

function ordinal_to_english($quantity) {
    switch($quantity) {
        case 1: return "first";
        case 2: return "second";
        case 3: return "third";
        case 4: return "fourth";
        case 5: return "fifth";
        case 6: return "sixth";
        case 7: return "seventh";
        case 8: return "eighth";
        case 9: return "ninth";
        case 10: return "tenth";
    }
    Warn("Unknown ordinal with $quantity");
}

function check_table($query) {
    Debug("Checking table with $query");
    $result = query_or_die($query);
    $num_rows = mysql_num_rows($result);
    return array($result,$num_rows);
}

function interesting_sort($a,$b) {
  # ugh.  We should figure out how to alpha sort the interesting
}

function fuzzy_sort($a,$b) {
  return ($a->getScore() > $b->getScore());
}

function subentry_sort($a,$b) {
  $a_pal = $a->getWord();
  $b_pal = $b->getWord();
  $a_pos = $a->getPos();
  $b_pos = $b->getPos();
  Debug("Sorting $a_pal [$a_pos] with $b_pal [$b_pos]");
  if (strpos($a_pos,'v.pf.') !== False and strpos($b_pos,'v.pf.') !== False) {
    return pos_compare($a_pos,$b_pos,5);
  } else if (strpos($a_pos,'n.poss.') !== False and strpos($b_pos,'n.poss.') !== False) {
    return pos_compare($a_pos,$b_pos,7);
  } else if (strpos($a_pos,'v.pf') !== False) {
    return -1;  # put all the v.pf's first
  } else if (strpos($b_pos,'v.pf') !== False) {
    return 1;
  } else if (strpos($a_pos,'n.poss') !== False) {
    return -1;  # put all the n.poss first
  } else if (strpos($b_pos,'n.poss') !== False) {
    return 1;
  } else if (strpos($a_pos,'expression') !== False and strpos($b_pos,'expression') !== False) {
    return strcmp($a_pal,$b_pal);
  } else if (strpos($a_pos,'expression') !== False) {
    return 1;  # put all the expressions last 
  } else if (strpos($b_pos,'expression') !== False) {
    return -1;
  } else {
    return strcmp($a_pal,$b_pal);
  }
}

function pos_compare($a_pos,$b_pos,$obj_offset) {
  $pos_sort = array(
    '1s' => 0,
    '2s' => 1,
    '3s' => 2,
    '1p' => 3,
    '2p' => 4,
    '3p' => 5
  );

  $a_obj = substr($a_pos, $obj_offset, 2); 
  $b_obj = substr($b_pos, $obj_offset, 2); 
  $a_index = $pos_sort[$a_obj];
  $b_index = $pos_sort[$b_obj];
  Debug("Need to compare v.pf's for POS $a_pos [$a_obj:$a_index] and POS $b_pos [$b_obj:$b_index]");
  if ($a_index == $b_index) {
    $a_len = strlen($a_pos);
    $b_len = strlen($b_pos);
    return ($a_len < $b_len ? -1 : 1);
  } else {
    return ($a_index < $b_index ? -1 : 1);
  }
  return -1;
}

function entry_sort($a,$b) {
  return strcasecmp($a->getMain(),$b->getMain());
}

function find_entries($query,$group=False,$tag=NULL) {
  return get_words($query,$group,$tag);
}

function make_search_col($col,$target) {
  return "$col regexp'" . '[[:<:]]' . $target . '[[:>:]]' ."'";

}

function get_all_entries2($target,$direction) {
  Debug("Looking for $target in $direction");
  # find all stems to which this word belongs
  $cols = [];
  if ($direction == 'pp') {
    $cols[] = make_search_col('pal',$target);
    $cols[] = make_search_col('pdef',$target);
  } else {
    $cols[] = make_search_col(($direction == 'ep') ? "eng" : "pal",$target);
  }
  $where = "where " . implode(' or ', $cols);
  $query = "select id,stem from all_words3 $where"; 
  return get_words($query);
}

function get_mp3_paths($type,$id) {
  switch($type) {
    case 'example': $subdir = "examples.palauan"; break;
    case 'proverb': $subdir = "proverbs.palauan"; break;
    case 'upload_sentence': $subdir = "upload_sentence.palauan"; break;
    case 'sounds': $subdir = "sounds.palauan"; break;
    case 'pdef': $subdir = "all_words3.pdef"; break;
    default: die("WTF: Unknown type $type in " . __FUNCTION__);
  }
  $file = implode('/',array($_SERVER['DOCUMENT_ROOT'],mp3_dir(),"$subdir/$id.mp3"));
  $url  = implode('/',array(mp3_dir(),"$subdir/$id.mp3"));
  return array($file,$url);
}

function mp3_dir() {
  return "/uploads/mp3s/";
}

function audio_button($type,$id) {
  #return getcwd() . "/" ;
  list($mp3,$url) = get_mp3_paths($type,$id);
  Debug("Checking for audio at $mp3");
  if (file_exists($mp3)) {
    return audio_play_button($url,NULL);
  } else {
    return NULL;
  }
}

function pics_dir() {
  return "/uploads/pics";
}

function print_words($entries,$target=Null,$force_collapse=False) {
    # look and figure out what all media we have
    $pics = array();
    $aclips = array();
    foreach (array('m4a','mp3') as $extension) {
      foreach (glob("mp3s/*.$extension") as $filename) {
        $aclips[basename($filename,".$extension")] = $filename;
      }
    }
    $pdir = $_SERVER['DOCUMENT_ROOT'] . pics_dir();
    Debug("Searching for pics in $pdir"); 
    foreach (glob("$pdir/*.jpg") as $filename) {
      #Debug("Adding $filename as a picture");
      $pics[basename($filename, '.jpg')] = pics_dir() . '/' . basename($filename);
    }

    # params to pass to the toHtml function 
    $collapse = (count($entries)>2 or $force_collapse==True) ? true : false;
    $text_only = false;

    # print the found entries.  In case there is audio, add the audio div
    if (count($entries)>0) {
      $found = true;
      $html =  "<div class='definitions'>\n";
      $html .= "<div id='jquery_jplayer'></div><!-- jquery_jplayer -->\n";
      foreach ($entries as $key => $val) {
          $html .= "<div class='even'>\n";
          $html .= $val->toHtml($aclips,$pics,$text_only,$collapse);
          $html .= "</div><!-- even -->\n";
      }
      $html .= "</div><!-- definitions -->\n";
  
      # highlight the target word
      if ($target != Null) {
        $pattern = '/([\b>\s])(' . $target . ')([;.,<\b\s])/i';
        #$style = 'style="color:#f00;background-color: #ffffff;"';
        $style = 'style="background-color: #FF8C00;"';
        $style = 'style="background-color: #FFFF00;"';
        $replace = '$1' . "<span $style >$2</span>" . '$3';
        $html = preg_replace($pattern, $replace, $html);
        #$html = str_replace('[\b>]chelebed[<\b]', "TARGET", $html);
      }
      echo $html;
    }
}

function get_count($table,$where=NULL) {
  $q = "SELECT COUNT(1) FROM $table $where";
  $result = query_or_die($q);
  $total = mysql_result($result, 0, 0);
  return $total;
}

function set_def($tag,$edef,$pdef,$id) {
  # put pdef in italics
  if ($pdef != NULL) {
    $a = audio_button('pdef',$id);
    $pdef = "$a<i>$pdef</i>";
  }
  # use pdef if requested or if no eng definition
  if ($tag == 'pdef' || $edef == NULL) {
    $def = $pdef; 
  } else {
    $defs = array();
    if ($edef != NULL and strlen($edef) > 0) {
      $defs[] = $edef;
    }
    if ($pdef != NULL and strlen(trim($pdef)) > 0) {
      #print "Adding $pdef (length " . strlen(trim($pdef)) . ") to def $edef<br>\n";
      $defs[] = "$pdef"; 
    } 
    $def = join("<br>",$defs); 
  }
  return $def;
}

function get_words($query,$group=True,$tag=NULL) {
  list($result,$num_rows) = check_table("$query");

  # turn off grouping if we get too many results, otherwise way too slow
  if ( $num_rows > 20 ) {
    $group = False; 
  }

  if ( $num_rows > 800 ) {
    echo "<h2>Sorry.  Too many results.  Please try a different search term.</h2>\n";
    querylog('XX',2,'xx',"Too many results: $num_rows");
    return NULL;
  } else if (is_johnbent() ) {
    #echo "<br>$query : $num_rows found<br>\n";
  }

  # a way to turn off grouping for debugging purposes
  if (is_johnbent()) {
    #$group = False;
  }

  # set up the array that we return to the user and the array that we use to weed repeated branches (see below)
  $entries = array();
  $roots = array();

  # foreach stem, find all words which branch from it
  # each word should have stem defined.  Some don't.  Set them to stem from themselves.
  # set also to stem themselves if grouping is disabled
  while($row = mysql_fetch_row($result)) {
    $stem = ($row[1]) ? $row[1] : $row[0];
    if ( ! $group ) { $stem = $row[0]; } # don't group

    # first find the stem itself, should be just one row
    $q = "select pal,pos,eng,pdef,origin,stem,id,oword from all_words3 where id = '$stem'";
    list($res,$num_rows) = check_table($q);
    assert($num_rows==1, "WTF: More than one result for $q");
    $row = mysql_fetch_assoc($res);
    $entry = new Entry($row['pal'],$row['eng'],$row['pos'],$row['origin'],$stem,Null,$row['oword'],$row['stem'],$row['pdef']);
    $entries{$stem} = $entry;

    if ( ! $group ) { 
      # don't group for branches if grouping is off 
      # however do add a cf back to the root 
      if ($row['stem'] != Null && $row['id'] != $row['stem']) {
        $entry->AddCf($row['stem']);
        continue;
      } else {
        $roots[$row['id']] = 1; 
      }
    }

    # now each branch
    $q = "select pal,pos,eng,pdef,origin,id,oword from all_words3 where stem = '$stem' and id != '$stem'";
    $qres = query_or_die($q);
    while($row = mysql_fetch_assoc($qres)) {
      #$def = set_def($tag,$row['eng'],$row['pdef']);
      $entry->AddWord($row['pal'],$row['eng'],$row['pdef'],$row['pos'],$row['origin'],$row['id'],$row['oword']);
    }
    $entry->addCfs();
    #if ( $group ) {
    # when doing large groups, adding the examples and the proverbs is a large slowdown
    # we need to add an index matching word id's to example and proverb id's
    # doing the full scan for multiple words is slow
    $entry->addExamples();
    $entry->addProverbs();
    #}
  }

  Debug("Fetched words.");

  # when we are not grouping, we are still grouping the root word
  # then when the root word and a bunch of stem words are returned, it's confusing
  # because the stem words show up individually as well as in the root group
  # we should go through and remove words which are stems belonging to a root word
  # that is being returned
  if (! $group) {
    #print_r($roots);
    $trimmed = array();
    foreach ($entries as $i => $a) {
      $keep = 0;
      if ($a->root() == $a->id()) {
        #print "Keeping $i because it is root word.<br>";
        $keep = 1;
      } else if ( ! array_key_exists($a->root(),$roots) ) {
        $root = $a->root();
        #print "Keeping $i because its root $root is not being returned.<br>";
        $keep = 1;
      } 
      if ($keep == 1) {
        $trimmed[$i] = $a;
      }
    }
    $entries = $trimmed;
  }

  # now sort the entries before returning them.  Use usort and a custom sort function.
  usort($entries, "entry_sort");
  return $entries;
}

$GLOBALS['interesting'] = array(
  'animal'  => array("a.tags regexp 'bird|cheled|charm'","Animals",'table'),
  'daob'    => array("a.tags rlike 'daob'","Areas of the Ocean", 'table'),
  'birds'   => array("a.tags rlike 'bird'","Birds",'table'),
  'body'    => array("a.tags rlike 'body'","Body Parts",'table'),
  'buil'    => array("a.tags rlike 'buil'","Buil (moon words)",'table'),
  'cerem'   => array("a.tags rlike 'ceremony'","Ceremonies",'table'),
  'cheled'  => array("a.tags rlike 'cheled'","Cheled (sea food)",'table'),
  'color'   => array("a.tags rlike 'color'","Colors",'table'),
  'quiz'    => array("pal in (select Palauan from quiz_hard) and pal not like 'bai' and pal not like 'buk'", "Difficult Quiz Words",'words'),
  'chief'   => array("a.tags rlike 'chief'", 'Dui (titles)','table'),
  'english' => array("a.origin not rlike 'native' and a.origin rlike 'E'", "English Borrowed",'table'),
  'fish'    => array("a.tags rlike 'fish' and a.tags not rlike 'fishing'","Fish",'table'),
  'fishing' => array("a.tags rlike 'fishing'","Fishing Terms",'table'),
  'flowers' => array("a.tags rlike 'bung'","Flowers",'table'),
  'game'    => array("a.tags rlike 'game'","Games",'table'),
  'german'  => array("a.origin rlike 'G'", "German Borrowed",'table'),
  'greet'   => array("a.tags rlike 'greet'","Greetings",'table'),
  'japan'   => array("a.origin rlike 'J'", "Japanese Borrowed",'table'),
  'kinship' => array("a.tags rlike 'kin'","Kinship",'table'),
  'legends' => array("a.tags rlike 'legend'","Legends",'table'),
  'undef'   => array("isnull(a.eng) and a.stem=a.id","Need English",'table'),
  'verify'  => array("custom","Need Verification",'table',"select palauan as 'Possible Word', notes as 'Notes', source as 'Source' from notes where isnull(DONE) or DONE=0 or DONE!=1"),
  'joseph'  => array("not isnull(a.josephs) and a.josephs!=1","New Words Since Josephs",'table'),
  'odor'    => array("a.tags rlike 'odor'","Odors",'table'),
  'mlai'    => array("a.tags rlike 'mlai'","Parts of Boats",'table'), 
  'blai'    => array("a.tags rlike 'blai'","Parts of Houses",'table'), 
  'place'   => array("a.tags rlike 'place'","Places",'table'),
  'plants'  => array("a.tags regexp 'bung|plant'","Plants",'table'),
  'reng'    => array("a.tags rlike 'reng'","Reng Phrases",'table'),
  'shapes'  => array("a.tags rlike 'shape'", "Shapes", 'table'),
  'spanish' => array("a.origin rlike 'S'", "Spanish Borrowed",'table'),
  'flags'   => array("a.tags rlike 'flag'", "State Flags", 'table'),
  'pdef'    => array("not isnull(a.pdef)","Tekoi ma Omesodel",'table'),
  'terms'   => array("a.tags rlike 'address'","Terms of Address",'table'),
  'trees'   => array("a.tags rlike 'tree'","Trees",'table'),
  'tagalog' => array("a.origin not rlike 'native' and a.origin rlike 'T'", "Tagalog Borrowed",'table'),
  'variants'=> array("a.pos like 'var.'","Variant Spellings",'table'),
  'money'   => array("a.tags rlike 'money'","Udoud (money)",'table'),
  'pictures'=> array("a.id in (select allwid from pictures where uploaded=1)", "Words with Pictures",'table'),
);

function html_page($config) {
  html_top($config['title'],True);
  belau_header($config['title']);
  start_content_container();
  if ($config['aside']) {
    echo "<div id='aside'>\n";
    echo "<div class='tab'>\n";
    echo $config['aside'];
    echo "</div><!-- tab -->\n";
    echo "</div><!-- aside -->\n";
  }
  start_content();
  if ($config['intro']) {
    echo $config['intro'];
  }
  include($config['html']);
  end_content();
  belau_footer(curPageURL());
  end_body_html();
}

function show_words($query,$group=True) {
  $entries = get_words($query,$group);
  print_words($entries);
}

function table_page($config) {
  start_simple_page($config);
  print "<h2>" . $config['title'] . "</h2>";
  if ($config['intro']) {
    print $config['intro'];
  }
  $show_title = False; # used to be true
  print_table($config['where'],True,$show_title,$config['add_count'],$config['extra_fp']);
  end_simple_page();
}

function start_simple_page($config) {
  html_top($config['title'],True);
  belau_header($config['title']);
  start_content_container();
  echo "<br><p>&nbsp;";
  echo "<div class='tab'>"; # instead of content
  db_connect();
}

function end_simple_page($extra=NULL) {
  end_content();
  belau_footer(curPageURL(),$extra);
  end_body_html();
}

function words_page($config) {
  start_simple_page($config);
  $query = "select id,stem from all_words3 where " . $config['where'];
  $group = (isset($config['group']) ? $config['group'] : True); 
  show_words($query,$group);
  end_simple_page();
}

function belau_header($title) {

    echo "
    <span itemscope itemtype='http://schema.org/Article'></span>
    <a class='aheader' href='/'>
    <div id='header' alt='A Tekoi er a Belau ma Omesodel'>
        <h1 itemprop='name'>Palauan Language Online: $title</h1>
    </div><!-- header -->
    </a>
    <div id='menuwrapper' onclick=''>
        <ul>
            <li><a href='/'>Search</a></li>
            <li><a href='/misc/browse.php'>Browse</a></li>
            <li><a href='#'>Quizzes</a>
              <ul>
              <li><a href='/quiz/classic.php'>Vocab</a></li>
              <li><a href='/quiz/pics.php'>Pictures</a></li>
              <li><a href='/quiz/q_proverbs.php'>Proverbs</a></li>
              <li><a href='/quiz/q_trivia.php'>Trivia</a></li>
              <li><a href='/quiz/audio.php'>Audio</a></li>
              <li><a href='/quiz/pronouns.php'>Pronouns</a></li>
              <li><a href='/quiz/pos.php'>Parts of Speech</a></li>
              </ul>
            <li><a href='/random.php'>Random Words</a></li>
            <li><a href='#'>Dosuub</a>
                <ul>
                <li><a href='/dosuub/'>Learn Palauan</a></li>
                <li><a href='/grammar/pronounce.php'>Pronunciation</a></li>
                <li><a href='/dosuub/dorrenges.php'>Dorrenges</a></li>
                <li><a href='/dosuub/phrases.php'>Common Phrases</a></li>
                <li><a href='/dosuub/app.php'>Phone/Tablet</a></li>
                <li><a href='/dosuub/confusion.php'>Common Errors</a></li>
                <li><a href='/grammar/nouns.php'>About Nouns</a></li>
                <li><a href='/grammar/pronouns.php'>About Pronouns</a></li>
                <li><a href='/grammar/adjectives.php'>About Adjectives</a></li>
                <li><a href='/grammar/numbers.php'>About Numbers</a></li>
                <li><a href='/grammar/baby.php'>About Baby Words</a></li>
                <li><a href='/grammar/cont.php'>Contractions</a></li>
                <li><a href='/grammar/pos.php'>Parts of Speech</a></li>
                <li><a href='/books/malsol.php'>1999 Workbook</a></li>
                <li><a href='/books/handbook1.php'>1997 Handbooks</a></li>
                <li><a href='/grammar/tkel.php'>1996 Conversational</a></li>
                </ul>
            </li>
            <li><a href='#'>Dekaingeseu</a>
                <ul>
                <li><a href='/dekaingeseu/d_trivia.php'>Trivia Q's</a>
                <li><a href='/dekaingeseu/d_sentence.php'>Example Sentences</a>
                <li><a href='/dekaingeseu/a_audio.php'>Record Audio</a>
                <li><a href='/dekaingeseu/copy_charlotte.php'>Charlotte el Bubuu</a>
                <li><a href='/dekaingeseu/rubak.php'>Rubak ma Daob</a>
                <li><a href='/dekaingeseu/pics_fish.php'>Fish Pics</a>
                <li><a href='/dekaingeseu/pics_trees.php'>Tree Pics</a>
                <li><a href='/dekaingeseu/pics_money.php'>Money Pics</a>
                <li><a href='/dekaingeseu/pics_birds.php'>Bird Pics</a>
                <li><a href='/dekaingeseu/pics_cheled.php'>Sea Food Pics</a>
                <li><a href='/dekaingeseu/pics_plants.php'>Plant Pics</a>
                <li><a href='/dekaingeseu/pics_fishing.php'>Fishing Pics</a>
                <li><a href='/dekaingeseu/pics_charm.php'>Animal Pics</a>
                <li><a href='/dekaingeseu/pics_misc.php'>Other Pics</a>
                </ul>
            <li><a href='/misc/proverbs.php'>Proverbs</a></li>
            <li><a href='#'>List . . .</a>
                <ul>
            ";
                #<li><a href='/dekaingeseu'>Older Tasks</a>
            foreach ($GLOBALS['interesting'] as $i => $a) {
              echo "<li>" . button_as_link($i,NULL,ucfirst($a[1]),'/show_words.php') . "</li>\n";
            }  
            echo "
                </ul>
            </li>
            <li><a href='/books/books.php'>Books</a>
            </li>
            <li><a href='#'>More</a>
                <ul>
                <li><a href='/about.php'>About / Contact Us</a></li>
                <li><a href='https://www.facebook.com/tekinged'>News</a></li>
                <li><a href='/links.php'>Links</a></li>
                <li><a href='/hanahuda/'>Hanahuda Rules</a></li>
                <li><a href='/spell.php'>Spell Checker</a></li>
                <li><a href='/frequency.php'>Word Frequency</a></li>
                <li><a href='/misc/examples.php'>Example Sentences</a></li>
                <li><a href='/books/austro.php'>Other Austronesian</a></li>
                </ul>
            </li>
        </ul>
    </div><!-- menu-wrapper -->
    ";
    db_connect();
}

function belau_footer($comments_id=NULL,$extra_visitlog=NULL) {
    if ($comments_id) {
        // var disqus_title = 'a unique title for each page where Disqus is present';
        // var disqus_url = 'a unique URL for each page where Disqus is present';
      echo "
        <script type='text/javascript'>
        var disqus_shortname = 'tekinged'; // Required - Replace example with your forum shortname
        var disqus_identifier = '$comments_id';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
        </script>
        <noscript>Please enable JavaScript to view the comments for this page.</a></noscript>
        ";

        echo "
          <a id='comments'></a>
          <div id='footer'>
          <div id='disq_foot'>
          <div id='disqus_thread'></div>
          </div><!-- disq_foot -->
            Email info@tekinged.com for questions, comments, etc.
          </div><!-- footer -->
          ";
    } else {
        echo "
          <div id='footer'>
            <b>tekinged.com</b> : Our online Palauan language portal.<br>
As of July 14, 2015, sponsored by and approved by the <a href=http://palaulanguagecommission.blogspot.com>Palau Language Commission</a>.
<br>
            <i>Now, and forever, free to use and free of ads.</i><br>
          </div><!-- footer -->
            ";
    }

    visitlog($extra_visitlog);
}

function db_connect() {
    $db_host = 'mysql.tekinged.com';
    $db_user = 'johnbent';
    $db_pwd = 'chemelekelbuuch';

    $database = 'belau';

    # try to set a timeout . . . 
    ini_set ("mysql.connect_timeout", "3"); 
    $link = mysql_connect($db_host, $db_user, $db_pwd);
    if (!$link) {
      print "<h2><p>Shoot.  We seem to be having database connection issues.<br>"
          . "Continue using the site but much functionality won't work.  :(<br>"
          . "Please email info@tekinged.com to report."
          . "<p>While you wait for tekinged.com, you can check out "
          . "<a href=http://www.trussel2.com/pal/>trussel2</a> which maintains a derived copy of our words database.  "
          . "</h2>";
        #die("Can't connect to database");
      return; # nothing else we can do here.
    }

    if (!mysql_select_db($database))
    die("Can't select database");
    return $link;
}

// this functions search through a row.  If substring is false, it must be perfect match
function search_row($row,$target,$substring,$header_array) {
    $match = false; 
    $row_text = "<tr>";
    $header_text = "<tr>";
    for($i=0; $i<sizeof($row); $i++) {
        $cell = $row[$i];
        if (strlen($cell)>0) {
            $row_text .= "<td>$cell</td>";
            $header_text .= "<th>$header_array[$i]</th>";
            if ($substring) {
                if (stripos($cell,$target) !== false) $match=true;
            } else {
                if (strcasecmp($cell,$target) == 0) $match=true; 
            }
        }
    }
    $row_text .= "</tr>";
    $header_text .= "</tr>";
    return array($match,$row_text,$header_text);
}

# this function doesn't work due to that preg_replace not working ...
function strip_punctuation($string) {
    $string = strtolower($string);
    #$string = preg_replace("/[:punct:]+/", "", $string);
    $string = str_replace(" +", "_", $string);
    return $string;
}

// user might want a table without the header row
// pass false to show_header to turn it off
function generate_table_header($result,$show_header=True,$add_count=False) {
    $table_header = "<table border='1' class='sortable'>";
    $fields_num = mysql_num_fields($result);

    $background = "bgcolor='#CC6600'";

    $fields = array();

    // printing table headers
    if ($show_header) {
        $table_header .= "<thead><tr>\n";
        if ($add_count) { $table_header .= "<th $background</th>"; }
        for($i=0; $i<$fields_num; $i++)
        {
            $field = mysql_fetch_field($result);
            $table_header .= "<th $background>{$field->name}</th>";
            $fields[] = $field->name;
        }
        $table_header .= "</tr></thead><tbody>\n";
    }
    return array($table_header,$fields);
}

function search_table($table,$target,$substring=false) {

    $printed_header=false;

    // sending query
    $result = mysql_query("SELECT * FROM {$table}");
    if (!$result) {
        die("Query to show fields from table failed");
    }
    
    list($table_header,$header_array) = generate_table_header($result);

    $found = false;

    // printing table rows
    while($row = mysql_fetch_row($result))
    {
        list($match,$row_text,$header_text) = search_row($row,$target,$substring,$header_array);
        if ($match) {
            if (!$found) echo "<h2>$target found in $table</h2>";
            $found = true;
            echo "<table border='1' class='db-table'><tr>";
            echo $header_text;
            echo $row_text; 
            echo "</tbody></table>";
        }
    }
    mysql_free_result($result);
    return($found);
}
?>

