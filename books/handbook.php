<?php 

$GLOBALS['DEBUG'] = false;

function make_handbook($toc,$vol,$last,$base) {
  $url  = "$base.php";
  $img_base = "$base/images/page-";
  $book = new Book(get_title($vol),$toc,$last,get_aside($vol),get_intro($vol),$url,$img_base);
}

function get_volumes() {
  $volumes = array(
              'vol1'=>array("Volume 1", "handbook1.php"),
              'vol2'=>array("Volume 2", "handbook2.php"),
              'tea1'=>array("Teacher's Manual 1", "teach1.php"),
              'tea2'=>array("Teacher's Manual 2", "teach2.php"),
              );
  return $volumes;
}

function get_title($volume) {
  $v = get_volumes();
  return "Lewis Josephs' 1997 Grammar Handbook " . $v[$volume][0];
}

function get_intro($volume) {
  $vols = get_volumes();
  $title = get_title($volume); 
  unset( $vols[$volume] );
  $links = "<ul>";
  foreach ($vols as $k => $v) {
    $t = $v[0];
    $u = $v[1];
    $links .= "<li><a href='$u'>$t</a>\n";
  }
  $links .= "</ul>";
  $intro = "<h2>$title</h2><br>

          <p>In 1997, Lewis Josephs revised his <a href='/books/75josephs.php'>1975 Reference Grammar</a> into a two volume Handbook of Palauan Grammar
          with accompanying teachers' guides.  These handbooks are intended to be more accessible to non-linguists and are specifically designed
          for Palauan school children.  On this page, we are thrilled to offer $title of these excellent books.  

          <p>
          The other volumes are available here:
          $links

          <p>
          A <a href='/misc/pdf.php?file=hreview'>review</a> of these handbooks by the retired University of Hawaii linguistics professor Robert Gibson  
          may also be of interest to some readers.  We are very happy to fulfill Dr. Gibson's wish that this work &quot;<i>ought to be available to anyone 
          interested in the Austronesian family of languages.  My hope is that its publication locally in Palau will not make it inaccessible or overly 
          difficult to obtain for those living elsewhere.</i>&quot;
          ";
  return $intro;
}

function get_aside($volume) {
  $aside = "<center><h3>Special Acknowledgement</h3><br></center>
            <div class='tab'><p id='tab'>The Ministry of Education is indebted to Masa-Aki N. Emesiochl, Director of the Bureau of Curriculum and Instruction, who provided
               the inspiration and means for taking initiatives to improve Palauan language studies and instruction for Palau Public Schools.  His 
               vision for this Palauan study text as a critical tool to improve instruction in the Palauan language has been the driving force for 
               its ultimate completion.  We are grateful for his unwavering commitment toward the educational advancement and future dreams of our
               children.
            </p></div>
              "; 
  return $aside;
}


?>
