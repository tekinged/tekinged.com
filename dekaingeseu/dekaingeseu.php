<?php 

# To use this class:
# follow the rules above each function.  Look at match_ker.php for an example

function get_contributions() {
  $contributions = array();
  $TABLE = 'dekaingeseu';
  $q = "select name,count as c from $TABLE order by c;";
  $r = query_or_die($q);
  while($row = $r->fetch_assoc()){ 
    $who = $row['name'];
    $count = $row['c'];
    $contributions[$who] = $count;
  }
  arsort($contributions);
  return $contributions;
}

function print_help_aside() {
  $contributors = get_contributions();
  
  echo "<div id='aside'>\n";
  echo "<div class='tab'>\n";
  echo "Top Contributors:\n"; 
  echo "<ol type='1'>";
  foreach ( $contributors as $who => $count ) {
    print "<li>$who : $count\n";
  }
  echo "</ol>";
  echo "</div><!-- tab -->\n";
  echo "</div><!-- aside -->\n";
}

# use this if you want to get multiple submit buttons
function submitButton($label,$value,$style=Null) {
  $submit = "<button name='taskresult' $style type='submit' value='$value'>$label</button>";
  return $submit;
}

function skipButton($label,$style=Null) {
  return "<a href='$url'> <input type='button' $style value='$label' /> </a>";
}

function quitButton($label,$style=Null) {
  return "<a href='/'> <input type='button' $style value='$label' /> </a>";
}

# use this to get the submit,cancel,quit buttons
# NULL for label,value if you don't want a submit button
function submitButtons($label,$value,$style=Null) {
  $url = curPageURL();
  if ($label != NULL) {
    $submit = "<button name='taskresult' $style type='submit' value='$value'>$label</button>";
  } else {
    $submit = NULL;
  }
  $skip = skipButton("SKIP. Give me another.",$style);
  $quit = quitButton("QUIT. I am done for now.",$style);
  return "$submit
          $skip
          $quit
         ";
}

Class Dekaingeseu {
    var $title;
    var $config;
    var $worker;
    var $table;
    var $url;

    # the passed in config must have:
    # intro, table strings
    # callback function 'make_task'
    #   make_task makes a task and creates a $_POST form however it likes to receive the task result later 
    #   it must return an array with 'id' as the integer id for the task in the database table 
    #   or if it returns NULL, then that's fine too. That's for more open-ended tasks where user choose which task
    # callback function 'record_task'
    #   record task pulls the result from the $_POST created in make_task and returns a comma separate list key=value pairs for the fields it wants updated in the database table
    # the table for each set of tasks must have these fields:
    #   id
    #   varchar assignee
    #   timestamp submitted
    #   timestamp assigned
    function __construct($title,$config) {
      Debug("Setting title to $title");
      $this->title = $title;
      $this->intro = $config['intro'];
      $this->config = $config;
      $this->table = $config['table'];
      $this->url = curPageURL();
      $this->makePage();
      Debug("Setting url to $this->url");
    }

    function getCount($w=NULL) {
      $TABLE = $this->table;
      $q = "select count(*) as c from $TABLE $w";
      $r = query_or_die($q);
      $row = $r->fetch_assoc(); 
      return $row['c'];
    }

    function addProgress() {
      $total    = $this->getCount($this->config['get_total']);
      if (array_key_exists('get_count',$this->config)) {
        $where    = $this->config['get_count'];
        $pending  = $this->getCount($where);
        $done = $total - $pending;
        $perc = sprintf("%.2f", 100*($done/$total));
        $pmsg = "$perc percent done ($done completed tasks out of $total).";
      } else {
        $pmsg = "$total uploaded so far.";
      }
      Debug("Adding progress.");
      print "<p id='tab'><div id='dekaingeseu-progress'>
                  Total progress so far on $this->title<br>
                  <ul><li>$pmsg</ul>
              </div></p>";
      /*
      print "<p id='tab'><div id='dekaingeseu-progess'>
              Progress so far: 
            </div><!-- dekaingeseu-progress'>
            </p>
            ";
      */
    }

    function addContribution() {
      $who = $this->worker;
      Debug("who is $who");
      $u = "INSERT INTO dekaingeseu (name,count) VALUES ('" . $who . "',1) ON DUPLICATE KEY UPDATE count = count + 1;";
      Debug ($u);
      #. $who . "', 1) ON DUPLICATE KEY UPDATE count = count + 1;"
      $this->actualUpdate($u);
    }

    function updateTable($update,$why) {
      $where = $this->getWhereForUpdate($why);
      if ($where != NULL) { 
        $this->reallyUpdateTable($update,$where);
      }
    }

    function reallyUpdateTable($update,$where) {
      $table = $this->table;
      $u = "update $table set $update where $where";
      $this->actualUpdate($u);
    }

    function actualUpdate($u) {
      Debug("Updating with $u");
      $r = mysqli_query($GLOBALS['mysqli'],$u) or die("mysqli error");
      #$affected = $r->affected_rows;
      #Debug("Affected rows: $affected");
    }

    function getWhereForUpdate($why) {
      if (isset($_POST['taskid']) and $why == 'complete') {
        # much safer way to get the ID!  The session can change but the webpage won't.
        # so if a user has multiple tabs open, each will post the correct id!
        $id = $_POST['taskid'];
        # we use the taskid twice.  Once to assign the task and once to complete it
        # the $_POST comes from the previous page so we need to use it to complete the previous
        # the $_SESSION should have just been recently set
      } else {
        $id = $_SESSION['taskid'];
      }
      if ( $id == NULL ) {
        # this is OK now since the upload pictures ones allow user to choose their own task
        #die("WTF: Null $id in " . __FILE__ . ": " . __FUNCTION__ . "<br>\n");
        return NULL;
      }
      return "id=$id";
    } 

    function recordTask($update) {
      if ($update != Null) {
        Debug(__FUNCTION__ . ": $update<br>\n");
        $u = "submitted=now(),$update";
        $this->updateTable($u,'complete');
        print "<h2>Thanks $this->worker for finishing the previous task!</h2><br>";
      }
      $this->addContribution();
    }

    function assignTask($name) {
      $u = "assignee='$name', assigned=now()";
      $this->updateTable($u,'assign');
    }

    function requestName() {
      echo "<p>Thanks for your interest in helping to create an online Palauan dictionary.";
      echo "<p>" . $this->intro . "</p>";

      echo "<form method='post'>\n";
      add_input("Enter your name so your contribution will be counted (or use anonymous if you'd like): ", 'name',False);
      echo "</form>\n";
    }

    function startPage() {
      html_top($this->title);
      belau_header($this->title);
      start_content_container();
      if (!empty($_SESSION['worker'])) {
        $this->worker = $_SESSION['worker'];
      }
    }

    function endPage() {
      Debug("making footer with $this->url");
      end_content();
      belau_footer($this->url); 
      end_body_html();
    }

    function setWorker($who) {
      $this->worker = $who;
      $_SESSION['worker'] = $who;
      $_SESSION['completed'] = 0;
    }

    function addAside() {
      print_help_aside();
    }
    
    function createTask() {
      Debug( "Need to create a task for you now $this->worker\n" );
      if (array_key_exists('q_find',$this->config)) {
        if (array_key_exists('order',$this->config)) {
          $orderby = $this->config['order'];
        } else {
          $orderby = 'rand()';
        }
        $q = $this->config['q_find'] . " and isnull(assigned) ORDER BY $orderby LIMIT 1";
        list($res,$num_rows) = check_table($q);
        if ($num_rows <= 0) {
          print "No more work to be done.  Thanks for helping!<br>\n";
          return;
        }
        $row = mysqli_fetch_assoc($res);
        $task = $this->config['make_task']($row,$this->config); # call our function pointer
      } else {
        $task = $this->config['make_task'](); # call our function pointer
      }
      if ($task == Null) { 
        # this can happen because the user might choose their own task
        #print "WTF: The assigned task could not be created.<br>\n";
        $_SESSION['taskid'] = 0;
        return;
      }
      $_SESSION['taskid'] = $task['id'];
      $this->assignTask($this->worker); 
    }

    function unassignOld() {
      $u = "assigned=NULL";
      $t = $this->config['timeout'];
      $w = "!isnull(assigned) and isnull(submitted) and unix_timestamp(now()) - unix_timestamp(assigned) > $t";
      $this->reallyUpdateTable($u,$w);
    }

    function makePage() {
      $this->startPage();

      if (isset($_POST['name'])) {
        $this->setWorker($_POST['name']);
      }

      if ($this->worker == Null) {
        $this->addAside();
      }
    
      start_content();
      $this->unassignOld();

      if ($this->config['extra_update'] != Null) {
        $this->actualUpdate($this->config['extra_update']);
      }

      if ($this->worker == Null) {
        $this->requestName();
      } else {
        if (isset($_POST['taskresult'])) {
          $update = $this->config['record_task']($_POST['taskresult'],$this->config);
          $this->recordTask($update); 
        }
        $this->createTask();
      }

      $this->addProgress();

      Debug("Ending page now");
      $this->endPage();
    }

}

?>
