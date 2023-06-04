<?php 

function search_box($label,$field='search') {
  return "  
    <form method='post'>
    <span itemprop='articleBody'>$label</span> <input type='text' name=$field />
    <input type='submit' />
    </form>
";
}


function dict_search_boxes($search,$esearch) {
      $sb = "<p class='tab'>";
      if ($search) {
        $sbox = search_box("Find word: ");
      }
      if ($esearch) {
        $ebox = search_box("Find English word: ",'esearch');
      }
      return "$sb\n<table><tr><td>$sbox</td><td>$ebox</td></tr></table>\n";
}


Class Book {
    var $toc;
    var $lastpage;
    var $url;
    var $intro;
    var $aside;
    var $title;
    var $options;

    function Book($title,$toc,$lastpage,$aside,$intro,$url,$base,$options=Null) {
      $this->title = $title;
      $this->toc = $toc;
      $this->lastpage = $lastpage;
      $this->intro = $intro;
      $this->aside = $aside;
      $this->url = $url;
      $this->base_image = $base;
      $this->options = $options;

      $this->makePage();
    }

    function get_search_boxes() {
      return dict_search_boxes($this->options['search'], $this->options['esearch']);
    }

    function page_link($pageno, $value=Null) {
      $image = sprintf("%s%03d.png", $this->base_image, $pageno);
      if ($value == Null) {
        $value = "Page $pageno";
      }
      $html_str = "<form class='formlink' action='".$this->url."' method='post' >\n";
      $html_str .= "\t<input class='formlink' style='display:none;' name='image' type='text' value='".$image."' >\n";
      $html_str .= "\t<input class='formlink' style='display:none;' name='pageno' type='text' value='".$pageno."' >\n";
      $html_str .= "\t<input class='formlink' id='view_button' type='submit' value='".$value."' >\n";
      $html_str .= "</form>\n";
      return $html_str;
    }

    function makePage() {
      $cur_section = Null;
      html_top($this->title);
      belau_header($this->title);
      print "<div id='content-container'>\n<div class='bothtab'>";
      $rm = $_SERVER['REQUEST_METHOD'] ?? '';
      if ($rm == 'POST') {
        if (isset($_POST['search'])) {
          $q = $this->options['search']; 
          $pageno = search_dict_pages($q,$_POST['search']);
        } elseif (isset($_POST['esearch'])) {
          $q = $this->options['esearch']; 
          $pageno = search_dict_pages($q,$_POST['esearch']);
        } else {
          $pageno = $_POST['pageno'];
        }
        if (! isset($this->options['header']) ) {
          $this->options['header'] = NULL;
        } 
        if ($pageno==-1) {
          $this->makeIntro();
        } else {
          $img = sprintf("%s%03d.png", $this->base_image, $pageno);
          $navlinks = $this->navLinks($pageno);

          if ($this->options && $this->options['header']) {
            echo $this->options['header'] . "<br>\n";
          }

          if ($this->options && $this->options['header']) {
            echo "<table><tr><td>\n";
          }
          $cur_section = $this->addToc($pageno) ?? ''; 
          if ($this->options && ($this->options['search'] || $this->options['esearch'])) {
            $sb = $this->get_search_boxes();
            echo $sb;
          }
          echo "<div class='tab'>$navlinks</div><br>\n";
          echo "<img class='dict' src='$img'>\n";
          echo "<div class='tab'>$navlinks</div><br>\n";
        }
      } else {
        $this->makeIntro();
      }
      print "</div></div>";
      $pagenumber = $pageno ?? '';
      belau_footer($this->url, ": $cur_section $pagenumber" ); 
      end_body_html();
    }

    function addToc($currentpage=Null) {

      # figure out which section we are in
      $currentsection = Null;
      foreach ($this->toc as $section => $sectionstart) {
        if ($currentpage != Null && $currentpage >= $sectionstart) { 
          $currentsection = $section; 
        }
      }

      # start a selection pull-down
      echo "  
            <div id='below'> <!-- table of contents -->  
               <form method='post'>
                <h2><span itemprop='articleBody'>Table of Contents</span>: <select onchange='this.form.submit()' name='pageno' /></h2>
          ";

      # if we are at the main page, add a menu option to the pull-down
      # else add an option to get back to the main page
      if ($currentpage == Null) {
        echo "<option>-- Choose Section --</option>\n";
      } else {
        print "<option value='-1'>Home Page</option>\n";
      }
      # now add the sections
      foreach ($this->toc as $section => $pageno) {
        $selected = ($currentpage != NULL && $currentsection == $section) ? "selected" : "";
        print "<option value='$pageno' $selected>$section</option>\n";
      }
      # finish the selection pull-down
      echo "
                </select>
              </form>
            </div> <!-- table of contents -->
        ";
      return $currentsection;
    }

    function makeIntro() {
      print "<div id='aside'>";
      print $this->aside;
      print "<span id='right'><a href='/misc/legal.txt'>Legal Disclaimer</a></span>\n";
      print "</div> <!--aside-->\n";
      print "<div id='content'>";
      print $this->intro; 
      if ($this->options && (!empty($this->options['search']) || !empty($this->options['esearch']))) {
        $search_boxes = $this->get_search_boxes();
        echo $search_boxes;
      }
      $this->addToc();
      print "</div> <!--content-->\n";
    }

    function navLinks($pageno) {
        $navlinks = '';
        if ($pageno > 0) {
          $navlinks .= "Previous page: " . $this->page_link($pageno-1);
        }
        if ($pageno < $this->lastpage) {
          $navlinks .= "Next page: " . $this->page_link($pageno+1);
        }
        return $navlinks;
    }

}

?>
