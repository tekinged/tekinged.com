<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;

list($title,$search,$esearch,$img_base) = get_dictionary('woleaian'); 

$intro = "<p>
          This is $title compiled by <a href='http://www.hawaii.edu/eall/korean-faculty/ho-min-sohn/'>Ho-Min Sohn</a>
          with assistance from Anthony Tawerilman and funding provided by the Trust Territories and the
          University of Hawaii. From the back cover:

          <p class='tab'>\"$title is the first compiled of this language.  It is
          designed as a reference work for native speakers in the Caroline Islands, for
          nonnative speakers who want to learn the language, and for linguists who are
          interested in the language for practical or theoretical purposes.  The
          dictionary contains some 6,200 Woleaian entries and an English-Woleaian Finder
          List of about 4,000 entries.\"

          <p>The 1975 companion WOLEAIAN REFERENCE GRAMMAR is located <a href=/books/woleaian_grammar.php>here</a>.  A list of Woleaian resources
          is <a href=/links.php#woleai>here</a>. The beginnings of a completely searchable Woleain dictionary (like the Palauan dictionary at tekinged.com)
          is <a href='http://woleai.tekinged.com'>here</a>.
          

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties between the Palauan and Woleaian languages. 
          ";

$thm='/books/woleaian_dict/images/page-000.png';
$big='/books/woleaian_dict/images/page-000.png';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$toc = array();
$toc['Cover'] = 0;
$toc['Preface'] = 8;
$toc['How To'] = 10;
$toc['Pronunciation'] = 14;
$toc['A-B Words'] = 24;
$toc['Ch Words'] = 35;
$toc['E-F Words'] = 38;
$toc['G Words'] = 53;
$toc['I Words'] = 87;
$toc['K Words'] = 94;
$toc['L Words'] = 101;
$toc['M Words'] = 111;
$toc['N Words'] = 127;
$toc['P Words'] = 133;
$toc['R Words'] = 142;
$toc['S Words'] = 147;
$toc['T Words'] = 162;
$toc['U Words'] = 180;
$toc['W Words'] = 183;
$toc['Y Words'] = 191;
$toc['English Word List'] = 202;
$toc['Back Cover'] = 386;
$lastpage = 387;
$base = "/books/woleaian_dict";
$url  = "$base.php";
$config['search'] = $search; 
$config['esearch'] = $esearch; 
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>
