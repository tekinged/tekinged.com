<?php 
include '../functions.php'; 

$config = array();
$config['title'] = "How to Learn Palauan";
start_simple_page($config);

echo "<h2>" . $config['title'] . "; Learning Palauan as a Foreign Language</h2>";
echo "<div class='bothtab'>\n";
intro();
add_links();
add_closing();
echo "</div><!-- bothtab -->\n";
end_simple_page(': dosuub');


############################################################################
############################################################################
############################################################################

function add_closing() {
  echo "<p>Happy Studying!  If you need offline access, you should also install ".
       "the <a href=/dosuub/app.php>dictionary on your phone/tablet</a>." .
       "<p>Please give us feedback in the comments below about " .
       "whether this ordering was useful to you and which of the links are not " .
       "as useful as others.  Finally, please let us know of other useful materials " .
       "which are not listed here.\n";
}

function add_links() {
  echo "<ol>";

  add_link("<b>Start with the basics</b>", NULL, false);
    echo "<ol class='lower-alpha'>\n";
    add_link("Basic Sounds", "/grammar/pronounce.php");
    add_link("Common Phrases and Conversation", "/dosuub/phrases.php");
    add_link("Practice Listening", "/dosuub/dorrenges.php", true, "[Use the Beginner Mode]");
    echo "</ol>\n";
    close_link();

  add_link("<b>Study some basic grammar</b>", NULL, false);
    echo "<ol class='lower-alpha'>\n";
    add_link("Palauan Revised Orthography Manual", "/misc/pdf.php?file=blailes_ortho_1990.compressed");
    add_link("Belauan Orthography and Grammar", "/misc/pdf.php?file=ortho_blailes_2000.compressed");
    echo "</ol>\n";
    close_link();

  add_link("<b>Learn about Pronouns</b>", NULL, false);
    echo "<ol class='lower-alpha'>\n";
    add_link("Read about Pronouns", "/grammar/pronouns.php", false);
    add_link("Memorize Pronouns", "http://www.memrise.com/course/381901/palauan/1/",true,"[Lesson 1]");
    add_link("Memorize Pronouns", "http://www.memrise.com/course/381901/palauan/2/",true,"[Lesson 2]");
    add_link("Memorize Pronouns", "http://www.memrise.com/course/381901/palauan/3/",true,"[Lesson 3]");
    echo "</ol>\n";
    close_link();

  add_link("<b>Read some books</b>", NULL, false);
    echo "<ol class='lower-alpha'>\n";
    add_link("Read the PREL Early Readers [K-3]", "/books/books.php?filter=childrens");
    add_link("Read the Palauan Language Workbook", "/books/books.php?filter=grammar");
    echo "</ol>\n";
    close_link();

  add_link("<b>Study some more grammar</b>", NULL, false);
    echo "<ol class='lower-alpha'>\n";
    add_link("Learn about Nouns", "/grammar/nouns.php");
    add_link("Learn about Adjectives", "/grammar/adjectives.php");
    add_link("Learn about Verbs", "/books/handbook1.php", true, "[Chapters 5 and 6]" );
    add_link("Learn about Little Words (a, er, el, etc.)", "/books/malsol.php", true, " [pages 20-37]");
    echo "</ol>\n";
    close_link();

  add_link("<b>Advanced Studies (do in any order and mix them up)</b>", NULL, false);
    echo "<ol class='lower-alpha'>\n";
    add_link("Practice listening", "/dosuub/dorrenges.php", true, "[Use the advanced mode]");
    add_link("Read CHARLOTTE EL BUBUU", "/books/books.php?filter=childrens");
    add_link("Read CHELDECHEDUCH ER A BELAU", "/misc/pdf.php?file=masaharu_compressed");
    add_link("Listen to some radio talk shows.", "http://palauwaveradio.com/?cat=93");
    add_link("Listen to the radio talk show that talks about this website!", "http://palauwaveradio.com/?p=6282", true, " [Discussion starts at 10:15]");
    add_link("Listen to some music.", "http://media.spacial.com/player/sam-vibe/black/250x100.html?sid=64495&rid=1171624&startstation=false&token=11a38ff722c890244c5dd9ba28d8e93d3eca03f1");
    add_link("Find more books to read.", "/books/books.php");
    add_link("Memorize the 3000 most frequently used words.", "/tmp/3000.php");
    add_link("Read the Josephs Grammar Handbooks", "/books/handbook1.php", true, "[Vols 1&2]");
    echo "</ol>\n";
    close_link();

  echo "</ol>";
}

function close_link() {
    echo "</li>\n";
}

function add_link($label,$link,$close=true,$extra=NULL) {
  if ($link) {
    $link = "<a href=$link>$label</a>";
  } else {
    $link = $label;
  }
  echo "<li>$link $extra";
  if ($close) {
    close_link();
  } else {
    echo "\n";
  }
}

function intro() {
  echo "<p>So you want to learn Palauan?  You've come to the right place! ";
  echo "<br>However be warned: Palauan is not an easy language to learn, especially for Westerners. " .
       "<p>But don't get discouraged!  <br>It's worth the effort; it's an awesome language and <i>Palauans are really fun to talk to</i>!\n";
  echo "<p>Let's begin!  The order of these links is just a suggestion; use them in any order that makes sense to you. Clearly, you " .
       "should skip the audio links if your only interest is in reading.  <br><i>HAVE FUN!</i>";
}



