<?php 
session_start();
include '../functions.php'; 
include 'dicts.php';

function search_box($label,$field='search') {
  return "  
    <span itemprop='articleBody'>$label</span> <input type='text' name=$field />
  ";
}


function dict_search_boxes() {
      $sb = "<p class='tab'>\n";
      $sbox = search_box("Find Austronesian word: ");
      $ebox = search_box("Find Other Language word: ",'esearch');
      $sub = "<input type='submit' />";
      return "$sbox</br>$ebox</br>$sub</br>";
}

function make_select() {
  $select = ""; 
  global $Dictionaries;
  foreach ( $Dictionaries as $name => $Dictionary ) {
    $t = $Dictionary->title;
    $on = isset($_POST[$name]) ? "checked" : "";
    $select .= "<INPUT TYPE=CHECKBOX NAME='$name' $on> $t<br>\n";
  }
  return $select;
}

function make_form() {
  $form = "<form method='post'>\n";
  $form .= make_select();
  $form .= dict_search_boxes();
  $form .= "</form>\n";
  return $form;
}

function show_results($target,$direction) {
  global $Dictionaries;
  foreach ( $Dictionaries as $name => $Dictionary ) {
    if (isset($_POST[$name])) {
      $t = $Dictionary->title;
      $img_path = $Dictionary->images;
      print "<h3>$t</h3><br>\n";
      $q = $direction == "Austronesian" ? $Dictionary->search : $Dictionary->esearch;
      print "<div id='tab'>\n";
      if ($q) {
        $pageno = search_dict_pages($q,$target);
        $img = sprintf("%s%03d.png", $img_path, $pageno);
        echo "<img class='dict' src='$img'>\n";
      } else {
        print "This dictionary does not have search available for $direction words.<br>\n";
      }
      print "</div>\n";
    }
  }

}

function make_page() {
  $config = array();
  $config['title'] = "Multi Austronesian Dictionary Search";
  start_simple_page($config);

  echo "<h2>" . $config['title'] . "</h2>\n";

  $form = make_form();
  echo "<div>$form</div>\n<br>\n";

  if (isset($_POST['search']) or isset($_POST['esearch'])) {
    #print_r($_POST);
    if (isset($_POST['search']) and strlen($_POST['search']) > 0) {
      $direction = "Austronesian";
      $word = $_POST['search'];
    } else {
      $direction = "English";
      $word = $_POST['esearch'];
    }
    echo "<h2>Searching for $direction word: $word</h2>\n";
    echo show_results($word,$direction);
  }

  echo "<h2>Please suggest more dictionaries in the comments below!</h2>\n";

  end_simple_page();
}

make_page();
?>
