<?php 
include '../functions.php'; 

$_GLOBAL['DEBUG'] = False;
$config = array();
$config['title'] = "One Random Word for each Part of Speech";
$config['add_count'] = True;

$pos_link = dict_link(28,"explanation");
$intro = "<p class='tab'>
        For a more thorough discussion of Palauan parts of speech, please refer to:
        <ul>
        <li>the 1997 Josephs <a href='/books/handbook1.php'>Handbooks</a> or 
        <li>the $pos_link in the 1990 dictionary.  
        </ul>
        <p class='tab'>
        The following is a simple list of the Palauan parts of speech with brief descriptions and an example
        word for each one.
        <br>
        <br>
        </p>
        ";


$query = 
  "select B.part as Type,B.explanation as Explanation,C.pal as Example,C.eng as Definition from
    ( select part,explanation from pos )
    as B
  inner join
    (select a.pal,a.pos,a.eng from (select * from all_words3 b where length(eng) > 0 order by rand()) a group by a.pos order by a.pos)
    C
  on B.part like C.pos
  order by B.part;
  ";
$config['where'] = $query; 
$config['intro'] = $intro;
#$config['where'] = "select a.pal,a.pos,a.eng from (select * from all_words3 b where length(eng) > 0 order by rand()) a group by a.pos order by a.pos";
table_page($config);
