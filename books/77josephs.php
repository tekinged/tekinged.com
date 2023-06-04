<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;
list($title,$search,$esearch,$img_base) = get_dictionary('77josephs'); 

$intro = "<p>
          This is the 1977 Lewis Josephs' dictionary, $title.  It has been superseded by the <a href='/misc/dictpage.php'>1990 Version</a>, 
          and is included here merely as an historical reference. The following is taken from the back cover:
          <p>
          $title provides an alphabetical
          listing of approximately 12,000 Palauan words. An English-Palauan
          finder list of 4,000 entries, based on selected words in the English
          definitions, is also included.
          <p>
          The work upon which the present dictionary is based is Fr. Edwin
          McManus' <a href='/books/68mcmanus.php'>'Word List and Grammar Notes—Palauan—English and English-Palauan,'</a> 
          circulated in 1955 and expanded and revised until
          the author's death in 1969. Dr. Josephs assumed responsibility for 
          the revision and publication of Fr. McManus' word list in 1973,
          under the auspices of the Pacific and Asian Linguistics Institute.
          <p>
          Every item in the McManus word list has been respelled according
          to the new standards of the Palau Orthography Committee. Lexical
          entries have been expanded and new entries added to include
          recent discoveries of meaning, usage, and derivational relationships.
          Part-of-speech affiliation has also been added to each item and,
          where applicable, morphological analysis and historical origin. Sample
          sentences are included to illustrate grammatical usage of
          idiomatic or difficult expressions. With these changes in or-
          thography and content, the dictionary is suitable for use with
          Josephs' <a href='/books/75josephs.php'>PALAUAN REFERENCE GRAMMAR.</a>
                    ";
$thm='/books/77josephs/mcmanus.png';
$big='/books/77josephs/mcmanus.png';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$toc = array();
$toc['Cover'] = 0;
$toc['McManus Bio'] = 8;
$toc['Contents'] = 10;
$toc['Preface'] = 12;
$toc['Intro'] = 16;
$toc['Terminology'] = 24;
$toc['Sounds'] = 34;
$toc['Phonetic Symbols'] = 50;
$toc['Palaun A-words'] = 56;
$toc['English A-words'] = 398;

$config['search'] = $search;
$config['esearch'] = $esearch;

$lastpage = 509;
$base = "/books/77josephs";
$url  = "$base.php";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>
