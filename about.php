<?php 
$GLOBALS['DEBUG'] = TRUE;
include 'functions.php'; 
html_top("Palau-English Dictionary");
belau_header("About");
?>

    <div id="content-container">

        <div id="aside">
            <?php print_contributors() ?>
            <br>
            <br>
            <a href=dekaingeseu/>More contributors</a><br>
            <a href='misc/terms.txt'>Terms of Use</a>
        </div>
        <div id="content"> 
            <h2>Contact Us</h2>
            <p>To stay apprised of our recent activity, plans, and updates, please visit and "Watch"
            our <a href=https://github.com/tekinged/missing>GitHub repository</a>. Please feel free to
            join that community and start new Discussions and Issues and participate in the conversation.
            That is the mechanism by which changes to our dictionary are made. All are welcome to join
            the conversation!

            <p>To provide feedback, please use the GitHub Discussion above or
            please email us at <a href="mailto:info@tekinged">info@tekinged.com</a> or
            leave a comment below. We love and welcome feedback of all types so please do
            not hesitate to contact us with any concerns or suggestions or other
            comments.</p>

            <h2>About tekinged.com</h2>
            <p>This website is an effort to create an online Palau language portal containing
            many resources including a Palauan-English dictionary which
            can be used to look up words as well as to help learn the grammar rules.  It is
            intended to help Palauans learn English, to help foreigners learn Palauan, and to
            create an online repository of the Palauan language stored in the cloud to ensure
            longevity.</p>

            <p>tekinged.com is very proud to be approved by and sponsored by the 
            <a href=http://palaulanguagecommission.blogspot.com>Palau Language Commission</a> (PLC)
            as of July 14, 2015. 
            Established in 2009 by 
            <a href=http://www.mvariety.com/regional-news/19655-toribiong-signs-law-creating-palau-language-commission>President Toribiong</a>
            and as 
            <a href=http://www.mvariety.com/regional-news/16853-senate-oks-establishment-of-palau-language-commission>ratified by the Senate</a>,
            the PLC is the official body responsible for maintaining and defining the Palauan Language and its orthography.
            In 2012, the PLC declared that it <a href=/misc/pdf.php?file=plc_adopt>adopted the grammar and orthography</a> as 
            defined by Dr. Lewis S. Josephs and Mr. Masa-Aki N. Emesiochl
            and described in their most recent <a href=/misc/dictpage.php>1990 dictionary</a> and <a href=/books/handbook1.php>1997 grammar handbooks</a>.
            Accordingly, tekinged.com follows these conventions as accurately as we can.  Please report any errors so that we might continue to adhere
            to the official standards.

<!--
            <p>One of the difficulties in learning Palauan is that many Palauan 
            words exist in multiple complicated forms.  For example, Palauan nouns have both
            an unpossessed form such as <i>mlai</i> and a possessed form such as <i>mlik</i>.
            Unfortunately there are no rules as yet discovered to convert between these so
            learners of Palauan must learn each form separately.  There are also seven possessed
            forms of each possessed nouns and these forms do have rules (with some exceptions).</p>

            <p>Palauan verbs are even more complicated.  Here are a few examples of their many forms: 
            <ul>
            <li><i>omuked</i>: Imperfective 
            <li><i>ombibtar</i>: Reduplicated
            <li><i>ketemall</i>: Reciprocated
            <li><i>bilskak</i>: Passive, past perfective, 1st person singular suffix
            <li><i>olbedau</i>: Perfective, 2nd person singlar suffix
            <li><i>kimodengelterir</i>: Perfective, 1st person plural exclusive prefix, third person plural suffix
            </ul>
            And many many more, check the column headings in the 
            <a href='table.php?table=verbs'>list of verbs</a> 
            for a hint of the full complexity.  And that list is still woefully incomplete.

            <p>
            Therefore, one of the goals of this dictionary is to include all the forms of all the
            Palauan words so that a learner of Palauan can find any word that they encounter.  This is
            not true of the existing Josephs dictionary which has only some of the forms.  However,
            to include all of the forms by entering each manually would be extremely time-consuming and error prone.
            Therefore, a complementary goal of this dictionary is to allow administrators to only 
            enter a few forms and have the dictionary itself do the possible conversions.  For example,
            <a href='scripts/populate_nouns.txt'>here is the file</a> that is used for nouns.  An
            administrator merely enters the unpossessed form and the first person singular possessed
            form and the dictionary can auto-populate the other possessed forms.  A third goal is to
            put all of the words into well-organized database tables to ensure maximum usability such
            as later inclusion into a possible Palau-English online translator for example.
            </p>

            <p>
            Currently, this project is extremely incomplete.  To complete it entails at least four
            main tasks:
            <ol>
            <li>Create the database tables and schemas
            <li>Create the website infrastructure
            <li>Populate the tables manually with the minimum amount of forms
            <li>Populate the remaining forms automatically with computer programs that understand the grammar rules
            </ol>
            </p>

            The majority of the work so far has been preparing the infrastructure for nouns.  Getting the database
            schema correct for the verbs and writing the programs to autopopulate them will be challenging.  Here is
            a partial list of known possible TODO items:

            <ol>
            <li>Autopopulation of verbs formed from action nouns such as <i>omlai</i>
            <li>Autopopulation of plural nouns such as <i>rechad</i>
            <li>The verb schema and the verb autocompletion programs
            <li>Decide whether to create an adjective table and autopopulate it from the state verbs such as <i>mesaik</i>
            <li>Decide whether to create a separate noun table for places such as <i>Ngermetengel</i> or just include them in nouns
            <li>Add a pronouns table
            <li>Find and fix any mistakes due to mistaken manual entry
            <li>Find and fix all mistakes due to exceptions not handled in the autopopulate programs such as <i>tmui</i> which the program incorrectly produced as the 2nd person plural form of <i>tik</i> 
            <li>Add a column for language of origin for borrowed words
            <li>Once the table schemas and supporting infrastructure is correct, figure out how to populate it with all Palauan words
            </ol> 

-->

            <p>Any and all help, comments, and suggestions are appreciated: <a href="mailto:info@tekinged.com">info@tekinged.com</a>.  Here is a link to update the
            databases through your browser, <a href="edit.html">Update DB</a>, although you will need to request an access code first.  Sulang!
            
        </div>
        </div>

        <?php belau_footer("about.php"); ?>


</body>
</html>
