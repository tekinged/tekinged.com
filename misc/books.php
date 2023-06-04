<?php 
include '../functions.php'; 
$title="Palauan Language Print Books Available for Purchase";
html_top($title);
belau_header($title);


$books = array(
  array("BELAU: Uchelel Belau er a Uab el me er a Miladeldil", "Steve Umetaro", 'umetaro', 
        '/misc/pdf.php?file=umetaro',
        'http://www.lulu.com/shop/steve-umetaro/belau-uchelel-belau-er-a-uab-el-me-er-a-miladeldil/paperback/product-22199050.html'),
  array("Palauan Early Reader", "PREL", 'early', 
        'http://earlyreaders.prel.org',
        'http://www.lulu.com/content/paperback-book/palauan-early-reader/16746816'),
  array("Rechuodel Volumes 1 and 2", "Palau Society of Historians", 'rechuodel',
        'http://tekinged.com/books/rechuodel95.php',
        'http://www.lulu.com/content/paperback-book/rechuodel/16746730'),
  array("Cheldecheduch er Belau el mo er a Rengalek", "Assorted", 'ngalek_cover',
        'http://tekinged.com/links.php#kids',
        'http://www.lulu.com/content/paperback-book/cheldecheduch-er-belau-el-mo-er-a-rengalek/16713249'),
  array("Malsol Palauan Language Workbook", "Yosko Malsol", 'malsol',
        'http://tekinged.com/books/malsol.php',
        'http://www.lulu.com/content/paperback-book/malsol-palauan-language-workbook/16533509')
);

function book_info($book) {
    $title = strtoupper($book[0]);
    $author = $book[1];
    $img = "lulu/$book[2].png";
    $free = $book[3];
    $url = $book[4];
    return (array($title, $author, $url, $img,$free));
}

function print_books($books) {
  #echo "<div class='buybook'>\n";
  #echo "<div class='thumbnail'>\n";
  echo "<div class='bookcover'>\n";
  #echo "<div id='popup'>\n";
  #echo "<div >\n";
  echo "<ul>\n";
  foreach ($books as $book) {
    list($title, $author, $url, $img,$free) = book_info($book);
    echo "<li>$title by $author\n";
    echo "<ul>
            <li><b>FREE</b> online version <a href=$free>available here</a>.</li>
            <li><b>BUY</b> it <a href='$url'>here<span><img src=$img width='100' alt='Cover'></span></a>.</li>
          </ul>\n";
  }
  echo "</ul>\n";
  echo "</div><!-- div buybook -->\n";
}

function print_intro() {
  echo "<p class='tab'>
      tekinged.com is happy to provide nicely bound copies of the following books available for purchase.  
      <i><b>Note that tekinged.com makes no money on any of these purchases.</b></i> 
      <p class='tab'>
      tekinged.com has merely 
      taken existing publically available texts and used a self-publishing website (lulu.com) to allow interested people 
      to purchase the least expensive possible print versions of Palauan language books.  All money paid goes
      to the self-publishing website for the cost of printing and sending the book. None comes to tekinged.com and tekinged.com
      has no relationship with the self-publishing website.
      <p class='tab'>
      tekinged.com will always continue to provide these books for <b>FREE</b> online for browsing, downloading, and self-printing.
      The option to <b>BUY</b> is only for those who want to pay for a nicely bound physical book. 
      ";
}

function print_bottom() {
  echo "<p class='tab'>
      Please help us find more publically available Palauan books so that we can add them to this page.<br>
      <a href=mailto:info@tekinged.com>Email us</a> if you have any more books or suggestions.
      ";

}

function print_aside($books) {
  $book = $books[array_rand($books)];
  list($title,$author,$url,$img,$free) = book_info($book);
  echo "<div id='aside'>
        <center>
        <a href=$url><img src=$img width=80%></a>
        </center>
        </div>
        ";
}

function print_content($books,$title) {
  echo "<div id='content'> 
        <h2>$title</h2>
        ";
  print_intro();
  print_books($books);          
  print_bottom();
  echo "</div>\n";
}

function main($books,$title) {
  start_content_container();
  print_aside($books);
  print_content($books,$title);
  belau_footer("/books.php"); 
  end_body_html();
}

main($books,$title);

?>
