<?php 

Class Dictionary {
  var $title;
  var $search;
  var $esearch;
  var $images;

  function __construct($t,$s,$e,$i) {
    $this->title = $t;
    $this->search = $s;
    $this->esearch = $e;
    $this->images = $i;
  }

};

$Dictionaries = array(
  'woleaian'   => new Dictionary( 'THE 1976 WOLEAIAN-ENGLISH DICTIONARY',
                                  'select page,last from dict_pages_woleaian',
                                  'select page,last from dict_pages_woleaian where id>175',
                                 '/books/woleaian_dict/images/page-'),
  'ponapean'   => new Dictionary( 'THE 1976 PONAPEAN-ENGLISH DICTIONARY',
                                  'select page,last from dict_pages_ponapean',
                                  'select page,last from dict_pages_ponapean where id>121',
                                 '/books/ponapean_dict/images/page-'),
  'yapese'     => new Dictionary( 'THE 1977 YAPESE-ENGLISH DICTIONARY',
                                  'select page,last from dict_pages_yapese',
                                  'select page,last from dict_pages_yapese where id>76',
                                  '/books/yapese_dict/images/page-'),
  '68mcmanus'  => new Dictionary( 'THE 1968 MCMANUS PALAUAN-ENGLISH DICTIONARY',
                                  'select page,last from dict_pages_68mcmanus', 
                                  'select page,last from dict_pages_68mcmanus where id>170',
                                  '/books/68mcmanus/page-'),
  '77josephs'  => new Dictionary( 'THE 1977 JOSEPHS PALAUAN-ENGLISH DICTIONARY',
                                  'select page,last from dict_pages_77josephs', 
                                  'select page,last from dict_pages_77josephs where id>340',
                                  '/books/77josephs/images/page-'),
  'worterbuch' => new Dictionary( 'THE 1913 PALAUAN GERMAN DICTIONARY', 
                                  'select page,last from dict_pages_worterbuch', 
                                  'select page,last from dict_pages_worterbuch where id>167',
                                  '/books/worterbuch/page-'),
  'josephs'    => new Dictionary( 'THE 1990 JOSEPHS PALAUAN-ENGLISH DICTIONARY',
                                  'select page,first from dict_pages', 
                                  'select page,first from dict_pages where id>349',
                                  '/misc/images/dict-'),
  'kerresel'   => new Dictionary( 'KERRESEL A KLECHIBELAU',
                                  'select id,last from dict_pages_kerresel',
                                  NULL ,
                                  '/books/kerresel/images/page-'),
  'mcmanus'    => new Dictionary( 'THE 1948 MCMANUS PALAUAN-ENGLISH DICTIONARY',
                                  NULL,
                                  'select page,last from dict_pages_48mcmanus',
                                  '/books/mcmanus/pngs/mcmanus-'),
                                 
);

function get_dictionary($which) {
  global $Dictionaries;
  $d = $Dictionaries[$which];
  $t = $d->title;
  $s = $d->search;
  $e = $d->esearch;
  $i = $d->images;
  return array($t,$s,$e,$i);
}
