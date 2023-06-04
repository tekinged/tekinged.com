<?php 
include '../functions.php'; 
include 'book.php';

$GLOBALS['DEBUG'] = false;

$title = "1975 Josephs Reference Grammar";
$base = "/books/75josephs";
$url  = "$base.php";
$img_base = "$base/pngs/page-";

$intro = "In 1975, Lewis Josephs, with the assistance of Helen Wilson, Masa-aki Emesiochel, 
          Masaharu Tmodrang, Donald Topping, and Dawn Reid, published the Palauan Reference Grammar presented here. 
          <br>
          <br>
          In 1999, in cooperation with the Palau Ministry of Education, he published a hard-bound two-volume set called 
          Handbook of Palauan Grammar. With more than 800 pages, is much expanded on this original grammar book. Intended for 
          high school students in Palau, it is less technically linguistic and is presented in more practical terms. 
          We are very happy to have <a href='handbook1.php'>Volume 1</a> now available.
          ";
$aside = "<center>
          <img src='$base/pngs/cover.jpg' />
          $title
          </center>
          ";
$pic="$base/pngs/cover_thumb.jpg";
$big="$base/pngs/cover.jpg";
$aside = "<center>
              <a href='$big'>
                <img src='$pic' />
              </a>
              </center>
              "; 


$toc = array();
$toc['Detailed Content Listing']                  = 3;   # 
$toc['Preface']                                   = 8;   # 
$toc['Sounds and Spelling']                       = 9;   # Book  1 
$toc['Nouns']                                     = 26;  # Book 34  : floor(B/2) + 9 = 26
$toc['Noun Possession']                           = 35;  # Book 52  : floor(B/2) + 9 = 26
$toc['Pronouns']                                  = 48;  # Book 78  : floor(B/2) + 9 = 26
$toc['Verbs']                                     = 65;  # Book 112 : floor(B/2) + 9 = 26
$toc['Verb Marker and Perfective Verbs']          = 82;  # Book 146 : floor(B/2) + 9 = 26
$toc['State Verbs']                               = 94;  # Book 170 : floor(B/2) + 9 = 26
$toc['Noun Derivation']                           = 102; # Book 187 : floor(B/2) + 9 = 26
$toc['Causative Verbs']                           = 109; # Book 200 : floor(B/2) + 9 = 26
$toc['Reciprocal Verbs']                          = 118; # Book 219 : floor(B/2) + 9 = 26
$toc['Reduplication and Further Verb Affixation'] = 124; # Book 230 : floor(B/2) + 9 = 26
$toc['Imperfective vs. Perfective Verbs']         = 135; # Book 253 : floor(B/2) + 9 = 26
$toc['Directional Verbs (mo, eko, me)']           = 142; # Book 266 : floor(B/2) + 9 = 26
$toc['Relational Phrases']                        = 147; # Book 276 : floor(B/2) + 9 = 26
$toc['Dependent Clauses']                         = 158; # Book 299 : floor(B/2) + 9 = 26
$toc['Object Clauses']                            = 171; # Book 324 : floor(B/2) + 9 = 26
$toc['Sentence Formation']                        = 175; # Book 333 : floor(B/2) + 9 = 26
$toc['Negation']                                  = 190; # Book 362 : floor(B/2) + 9 = 26
$toc['Hypothetical Verb Forms']                   = 200; # Book 383 : floor(B/2) + 9 = 26
$toc['Passive Sentences']                         = 209; # Book 400 : floor(B/2) + 9 = 26
$toc['Question Formation and Question Words']     = 213; # Book 408 : floor(B/2) + 9 = 26
$toc['Direct and Indirect Quotation']             = 223; # Book 428 : floor(B/2) + 9 = 26
$toc['Reason, Result, and Time Clauses']          = 228; # Book 438 : floor(B/2) + 9 = 26
$toc['Relative Clauses']                          = 234; # Book 450 : floor(B/2) + 9 = 26
$toc['Modifiers']                                 = 239; # Book 461 : floor(B/2) + 9 = 26
$toc['Numbers']                                   = 244; # Book 470 : floor(B/2) + 9 = 26
$toc['Connecting Words Me and E']                 = 250; # Book 482 : floor(B/2) + 9 = 26
$toc['Notes']                                     = 256; # Book 495 : floor(B/2) + 9 = 26
$toc['Appendix']                                  = 271; # Book 525 : floor(B/2) + 9 = 26
$toc['Glossary']                                  = 272; # Book 527 : floor(B/2) + 9 = 26
$toc['Bibliography']                              = 282; # Book 547 : floor(B/2) + 9 = 26
$toc['Index']                                     = 283; # Book 549 : floor(B/2) + 9 = 26
$lastpage = 287;
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>
