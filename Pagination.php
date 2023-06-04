<?php
//source unknown for logic of showPageNumbers()
//modified by drale.com - 1-19-2010
//added query_string reproduction and divs
//added showNext() and showPrev()

class Pagination {
	function getStartRow($page,$limit){
		$startrow = $page * $limit - ($limit);
		return $startrow;
	}	
	function showPageNumbers($totalrows,$page,$limit,$table,$orderby){
	
        // ugh, johnbent did something ugly here because he doesn't know how to do php and wants to allow the user to set the rows per page
        $query_string = "table=$table&limit=$limit&orderby=$orderby";
	
		$pagination_links = null;
	
		/*
		PAGINATION SCRIPT
		seperates the list into pages
		*/		
		 $numofpages = $totalrows / $limit; 
		/* We divide our total amount of rows (for example 102) by the limit (25). This 

	will yield 4.08, which we can round down to 4. In the next few lines, we'll 
	create 4 pages, and then check to see if we have extra rows remaining for a 5th 
	page. */
		
		for($i = 1; $i <= $numofpages; $i++){
		/* This for loop will add 1 to $i at the end of each pass until $i is greater 
	than $numofpages (4.08). */		
				
		  if($i == $page){
				$pagination_links .= '<div class="page-link"><span>'.$i.'</span></div> ';
			}else{ 
				$pagination_links .= '<div class="page-link"><a href="?page='.$i.'&'.$query_string.'">'.$i.'</a></div> '; 
			}
			/* This if statement will not make the current page number available in 
	link form. It will, however, make all other pages available in link form. */
		}   // This ends the for loop
		
		if(($totalrows % $limit) != 0){
		/* The above statement is the key to knowing if there are remainders, and it's 
		all because of the %. In PHP, C++, and other languages, the % is known as a 
		Modulus. It returns the remainder after dividing two numbers. If there is no 
		remainder, it returns zero. In our example, it will return 0.8 */
			 
			if($i == $page){
				$pagination_links .= '<div class="page-link"><span>'.$i.'</span></div> ';
			}else{
				$pagination_links .= '<div class="page-link"><a href="?table='.$table.'&page='.$i.'&'.$query_string.'">'.$i.'</a></div> ';
			}
			/* This is the exact statement that turns pages into link form that is used above */ 
		}   // Ends the if statement 
	
		return $pagination_links;
	}

	//added by drale.com - 1-19-2010
	function showNext($totalrows,$page,$limit,$table,$orderby,$text="next &raquo;"){	
		$next_link = null;
		$numofpages = $totalrows / $limit;

        PDebug("Using limit $limit in showNext");
		
		if($page < $numofpages){
			$page++;
			$next_link = '<div class="page-link"><a href="?table='.$table.'&limit='.$limit.'&orderby='.$orderby.'&page='.$page.'">'.$text.'</a></div>';
		}
		
		return $next_link;
	}
	
	function showPrev($totalrows,$page,$limit,$table,$orderby,$text="&laquo; prev"){	
		$prev_link = NULL;
		$next_link = null;
		$numofpages = $totalrows / $limit;

		PDebug("Using limit $limit in showPrev");
		
		if($page > 1){
			$page--;
			$prev_link = '<div class="page-link"><a href="?table='.$table.'&limit='.$limit.'&orderby='.$orderby.'&page='.$page.'">'.$text.'</a></div>';
		}
		
		return $prev_link;
	}
	
} 

function PDebug($where) {
    //echo "<br> DEBUG $where";
}


/*FUNCTIONS*/
function show_table($table,$where,$config) {
    $Pagination = new Pagination();
    //get total rows
    $totalrows = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM $table"));
    $tr = $totalrows['total'];

    //limit per page, what is current page, define first record for page
    // default values for the two
    $limit = $config['perpage'];    
    if(isset($_GET['page']) && is_numeric(trim($_GET['page']))){$page = mysql_real_escape_string($_GET['page']);}else{$page = 1;}
    PDebug("Using limit $limit");

    if ($_POST['limit']) {
        $limit = $_POST['limit'];
        $page = 1; // if user changed rows per page, reset page to page 1
        PDebug("Using post limit $limit");
    } else if ($_GET['limit']) {
        $limit = $_GET['limit'];
        PDebug("Using get limit $limit");
    }
    $startrow = $Pagination->getStartRow($page,$limit);

    PDebug("Using limit $limit");

    //IF ORDERBY NOT SET, SET DEFAULT
    if(!isset($_GET['orderby']) OR trim($_GET['orderby']) == ""){
        //GET FIRST FIELD IN TABLE TO BE DEFAULT SORT
        $sql = "SELECT * FROM $table LIMIT 1";
        $result = mysql_query($sql) or die(mysql_error());
        $array = mysql_fetch_assoc($result);
        //first field
        $i = 0;
        foreach($array as $key=>$value){
            if($i > 0){break;}else{
            $orderby=$key;}
            $i++;		
        }
        //default sort
        $sort="ASC";
    }else{
        $orderby=mysql_real_escape_string($_GET['orderby']);
    }	
    PDebug(7);

    //create page links
    if($config['showpagenumbers'] == true){
        $pagination_links = $Pagination->showPageNumbers($totalrows['total'],$page,$limit,$table,$orderby);
    }else{$pagination_links=null;}
    PDebug(5);

    if($config['showprevnext'] == true){
        $prev_link = $Pagination->showPrev($totalrows['total'],$page,$limit,$table,$orderby);
        $next_link = $Pagination->showNext($totalrows['total'],$page,$limit,$table,$orderby);
    }else{$prev_link=null;$next_link=null;}
    PDebug(6);

    //IF SORT NOT SET OR VALID, SET DEFAULT
    if(!isset($_GET['sort']) OR ($_GET['sort'] != "ASC" AND $_GET['sort'] != "DESC")){
        //default sort
            $sort="ASC";
        }else{	
            $sort=mysql_real_escape_string($_GET['sort']);
    }

    //GET DATA
    PDebug("Doing orderby $orderby");
    //if ($sort == "DESC") { $sort = "ASC"; /* never mind reverse sorting */ }
    $sql = "SELECT * FROM $table ORDER BY $orderby $sort LIMIT $startrow,$limit";
    PDebug($sql);
    PDebug( "$sql" );
    $result = mysql_query($sql) or die(mysql_error());

    //START TABLE AND TABLE HEADER
    echo "<table>\n<tr>";
    $array = mysql_fetch_assoc($result);
    foreach ($array as $key=>$value) {
        if($config['nicefields']){
        $field = str_replace("_"," ",$key);
        $field = ucwords($field);
        }
        
        //echo "$key, $field, $table, $limit, $orderby, $sort<br>";
        $field = columnSortArrows($key,$field,$table,$limit,$orderby,$sort);
        echo "<th>" . $field . "</th>\n";
    }
    echo "</tr>\n";

    //reset result pointer
    mysql_data_seek($result,0);

    //start first row style
    $tr_class = "class='odd'";

    //LOOP TABLE ROWS
    while($row = mysql_fetch_assoc($result)){

        echo "<tr ".$tr_class.">\n";
        foreach ($row as $field=>$value) {	
            echo "<td>" . $value . "</td>\n";
        }
        echo "</tr>\n";
        
        //switch row style
        if($tr_class == "class='odd'"){
            $tr_class = "class='even'";
        }else{
            $tr_class = "class='odd'";
        }
        
    }

    //END TABLE
    echo "</table>\n";

    $rows_per_page= "<form method='post'>&nbsp;Items per page: <input type='text' maxlength='5' size='5' name='limit' value='$limit'></form>";

    if(!($prev_link==null && $next_link==null && $pagination_links==null)){
    echo '<div class="pagination">'."\n";
    echo $prev_link;
    echo $pagination_links;
    echo $next_link;
    echo $rows_per_page;
    echo '<div style="clear:both;"></div>'."\n";
    echo "</div>\n";
    }
}

function columnSortArrows($field,$text,$table,$limit,$currentfield=null,$currentsort=null){	
	//defaults all field links to SORT ASC
	//if field link is current ORDERBY then make arrow and opposite current SORT
	
	$sortquery = "sort=ASC";
	$orderquery = "orderby=".$field;
    $tablequery = "table=".$table;
    $limitquery = "limit=".$limit;
    PDebug("using limit $limit in columnSortArrows");
	
        // currently only forward sorting works
	if($currentsort == "ASC"){
		$sortquery = "sort=DESC";
		$sortarrow = '<img src="images/arrow_up.png" />';
	}
	
	if($currentsort == "DESC"){
		$sortquery = "sort=ASC";
		$sortarrow = '<img src="images/arrow_down.png" />';
        // turn off the descend arrow
		$sortarrow = '<img src="images/arrow_up.png" />';
	}
	
	if($currentfield == $field){
		$orderquery = "orderby=".$field;
	}else{	
		$sortarrow = null;
	}
	
	return '<a href="?'.$orderquery.'&'.$tablequery.'&'.$limitquery.'&'.$sortquery.'">'.$text.'</a> '. $sortarrow;	
	
}

?>
