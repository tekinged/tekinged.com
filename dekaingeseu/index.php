<?php 
include 'dekaingeseu.php';

function start_dekaingeseu() {
  session_start();
  include '../functions.php'; 
  $title="Dekaingeseu";
  html_top($title);
  belau_header($title);
  start_content_container();
}

$GLOBALS['DEBUG'] = false;
$TABLE = "kerresel_columns";

function intro() {
    echo "<p>Thanks for your interest in helping to create an online Palauan dictionary.  On this page, we will occasionally post requests for help.  If you don't feel comfortable with 
         any of current requests or if there aren't any right now, please check back in later to see if there is something else.  Or email us at info@tekinged.com to ask how you can contribute!
          <p>Here is the current list of tasks available:
          <ul>
              <li>Upload trivia questions: <a href='d_trivia.php'>Dekaingeseu!</a>
              <li>Upload pictures:
              <ul>
                <li><a href='pics_fish.php'>Fish</a>
                <li><a href='pics_trees.php'>Trees</a>
                <li><a href='pics_money.php'>Money</a>
                <li><a href='pics_birds.php'>Birds</a>
                <li><a href='pics_cheled.php'>Sea Food</a>
                <li><a href='pics_plants.php'>Plants</a>
                <li><a href='pics_fishing.php'>Fishing Terms</a>
                <li><a href='pics_misc.php'>Other</a>
              </ul>
              <li>Upload audio:
              <ul>
                <li>Alternative webpage to upload <a href='audio_examples2.php'>Example Sentences</a>. Record separately using whatever program you like. Then upload here.
                <li>Original webpage to upload <a href='audio_examples.php'>Example Sentences</a>. Record using the webpage, then copy/paste the resulting link.
              </ul>
          </ul>
          <p>Here is the list of finished or inoperative tasks:
          <ul>
              <li>Matching words with Palauan definitions to their equivalents with English definitions: <a href='match_ker.php'>Dekaingeseu!</a>.
              <li>Transcribing words from the Kerresel dictionary: <a href='import_kerresel.php'>Dekaingeseu!</a>.
              <li>Transcribing words from the Josephs dictionary: <a href='copy_jos75.php'>Dekaingeseu!</a>.
              <li>Uploading pictures of state flags: <a href='pics_flags.php'>State Flags</a>.
          </ul>
          <p>Some ideas for next dekaingeseu projects:
          <ul><li>Uploading audio.
              <li>Adding Palauan translations for words that only have English definitions.
              <li>Adding English translations for words that only have Palauan definitions.
              <li>Help tag words for the word groups like fish, places, legend.
              <li>Find and fix errors (e.g. bad grammar/spelling in the proverbs.
              <li>Upload pictures of fish, palauan money, places, etc.
          </ul>
          Please email us or comment below with any other ideas for how to make tekinged.com a more useful online Palauan language resource.
          </p>
          ";
}

function main() {
  start_dekaingeseu();
  print_help_aside();
  start_content();
  intro();
  end_content();
  belau_footer("/dekaingeseu.php");
  end_body_html();
}

main();
