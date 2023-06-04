//<![CDATA[

function liveSearch(str,direction) {
  if (str.length==0) { 
    document.getElementById("livesearchdiv").innerHTML="";
    document.getElementById("livesearchdiv").style.border="0px";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    // datalist doesn't work in safari so do the autocomplete suggestions differently
    // we also do this same detection in livesearch.php
    // it would be better to do it only here and have livesearch return the same thing
    // and then move the formatting from livesearch.php into here but fuck it. my js isn't good.
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      var is_safari = navigator.userAgent.indexOf("Safari") > -1;
      var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
      if ((is_chrome)&&(is_safari)) {is_safari=false;}
      //if ((is_chrome)&&(is_opera)) {is_chrome=false;}
      if (is_safari) {
        document.getElementById("livesearchdiv").innerHTML=xmlhttp.responseText;
      } else {
        // document.getElementById("livesearch").style.border="1px solid #A5ACB2";
        console.log("browser is not safari");
        $('#livesearch').empty();
        $('#livesearch').append(xmlhttp.responseText);
        //var dataList = $("#livesearch");
        //dataList.append(xmlhttp.responseText);
      }
    }
  }
  xmlhttp.open("GET","livesearch.php?q="+str+"&d="+direction,true);
  xmlhttp.send();
}

function HideContent(d) {
  document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
  document.getElementById(d).style.display = "block";
}
function ReverseDisplay(d) {
  if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
  else { document.getElementById(d).style.display = "none"; }
}

function rotateMe(e) {
    $(e).toggleClass("rotate");
}

//]]>

