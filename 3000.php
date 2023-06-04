<?php 
include 'functions.php'; 

$GLOBALS['DEBUG'] = false;

function aside() {
  return "
    <a href=/images/frequency.png><img width=100% src=/images/flag_word_frequency.png></a>\n
  ";
}

function intro() {
    return
      "<p class='tab'>In early 2016, we went through all of the Palauan text we had digitized and made a list of the most frequently used words.
      We went through all of the entries and combined many forms of the same words as follows:
      <ul>
        <li>Possessed noun forms
          <ul><li>For example, BLIK, BLIMAM, etc. were merged into BLIL</ul>
        <li>Perfective verb forms
          <ul><li>For example, MEDENGELTERIR was merged into MEDENGELII</ul>
        <li>Action nouns 
          <ul><li>For example, OMELIM was merged into MELIM</ul>
        <li>Past tense imperfective verbs
          <ul><li>For example, ULUSBECH was merged into OUSBECH</ul>
        <li>Variant spellings were merged into highest occuring variant
          <ul><li>For example, ELECHANG was merged into CHELECHANG</ul>
        <li>Contractions were split into their constituent words
          <ul><li>For example, ERA was split into ER and A</ul>
        <li>Words with word-final -NG dropped were merged into their full forms
          <ul><li>For example, OBA was merged into OBANG </ul>
      </ul>

      <p class='tab'>
      For English speakers trying to learn Palauan as a second language, this list has been transformed into a 
      <a href=http://www.memrise.com/course/381901/the-first-3000-palauan-words/>memrise course</a>.  Please leave comments about that
      course either on this page or in the 
      <a href=http://www.memrise.com/course/381901/the-first-3000-palauan-words/forum/>memrise forums</a>.
      
      <p class='tab'>
      Unfortunately, this list is surely inaccurate because we only had access to a small corpus of Palauan text.  Our entire corpus contained
      only approximately 300,000 words selected from only a few sources and therefore disproportionaly skewed.  For example, according to
      our corpus the Palauan word RAIONG meaning 'lion' is one of the most frequently used Palauan words.  Clearly this is not accurate but this
      happens because one of our sources is a translation of Ernest Hemingway's novel The Old Man and the Sea in which the main character often
      dreams of lions.  
      <p class='tab'>Here is the list of sources and the total number of words from each:
      <tt>
      <ul>
      <li>166,319 words from the definitions in the monolingual Palauan dictionary 
      <li>&nbsp;45,008 words from the translation of Charlotte's Web
      <li>&nbsp;30,987 words from the translation of the Old Man and the Sea 
      <li>&nbsp;12,310 words from sentences found in the Palauan-English dictionary and from Justin Nuger's dissertation 
      <li>&nbsp;&nbsp;8,787 words from sentences uploaded by volunteers
      <li>&nbsp;&nbsp;4,725 words from Palauan text found in various Facebook comments
      <li>&nbsp;&nbsp;2,650 words from trivia questions uploaded by volunteers
      <li>&nbsp;&nbsp;2,449 words from a partial translation of The Island of the Blue Dolphin
      <li>&nbsp;&nbsp;2,006 words from proverbs found in the McKnight paper as well as others found around the web        
      </ul>
      </tt>
  
      <p class='tab'>The distribution appears to follow a zipf curve: <a href=/images/freq.png><img src=/images/freq.png width=100px></a>.
  
    <p class='tab'>
    For a automatically updated list of words drawn from a larger corpus but not cleaned, please see
    our other <a href=/frequency.php>Palauan Word Frequency</a> page. 
    ";

}

function body() {
  $table = table_to_string("select perc as Percent,pal as Word,pos as 'Part of Speech',eng as English,pdef as Belkul from freq_defs order by perc DESC");
  return "<p id='tab'>Without further ado, the words themselves:</p>$table\n";
}

db_connect();
$config['title'] = "The 3000 Most Frequently Used Palauan Words";
$config['aside'] = aside();
$config['intro'] = intro();
$config['body'] = body(); 
tekinged_page($config);

?>
</body>
</html>
