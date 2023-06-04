<?php 
include '../functions.php'; 
$title = "Palauan Pronouns";
html_top($title);
belau_header($title);
?>

    <div id="content-container">

        <div id="aside">
            Quick links:
            <ul>
            <li><a href="#personal">Personal pronouns</a><br>
            <li><a href="#relative">Relative pronouns</a><br>
            <li><a href="#demonstrative">Demonstrative pronouns</a><br>
            <li><a href="#indefinite">Indefinite pronouns</a><br>
            <li><a href="#reflexive">Reflexive pronouns</a><br>
            <li><a href="#interrogative">Interrogative pronouns</a><br>
            <li><a href="#possessive">Possessive pronouns</a><br>
            <li><a href="#affix">Affix pronouns</a><br>
            </ul>
        </div>
        <div id="content"> 
            <?php echo "<h2>$title</h2>"; ?>
            <p>Note about this page: this is a short attempt to list some of the simple rules
            for Palauan pronouns.  For
            a much longer and comprehensive explanation, please refer to Lewis Josephs'
            definitive <a href="http://www.jstor.org/stable/3623305">Palauan grammar book</a>.
            </p>

            <p>According to this <a href="http://www.english-grammar-revolution.com/list-of-pronouns.html">website</a>,
            English has at least these following types of pronouns: personal, relative, demonstrative, indefinite, 
            reflexive, interrogative, and possessive pronouns.  As such, this page
            attempts to list the corresponding Palauan pronouns for these same types.
            </p>

            <a id="personal"><h2>Personal pronouns</h2></a>

            Here are the personal pronouns for both English and Palaun:
            <table class="pronouns">
            <tr>
                <td></td><td colspan=2 bgcolor='#00FFFF'>English Personal Pronouns</td><td colspan=2 bgcolor='#00FFFF'>Palauan Personal Pronouns</td>
            </tr>
            <tr>
                <td></td><th>Singular</th><th>Plural</th><th>Singular</th><th>Plural</th>
            </tr>
            <tr>
                <th rowspan='2'>First Person</th>
                    <td rowspan=2>I, me</td><td rowspan=2>we, us</td>
                    <td rowspan=2><b>ngak</b>, ak</td><td><b>kid</b>, kede</td></tr></tr><td><b>kemam</b>, aki</td>
            </tr>
            <tr>
                <th>Second Person</th>
                    <td>you</td><td>you</td>
                    <td><b>kau</b>, ke</td><td><b>kemiu</b>, kom</td>
            </tr>
            <tr>
                <th>Third Person</th>
                    <td>he, him, she, her, it</td><td>they, them</td>
                    <td><b>ngii</b>, ng</td><td><b>tir</b>, te</td>
            </tr>
            </table>
            <p>There are at least a few differences:
            <ul>
            <li>Palauan is less ambiguous in the 2nd person as it distinguishes between singular and plural whereas English does not.
            <li>Palauan is less ambiguous in the 1st person plural as it distinguishes between one which includes the 
                listener (<i>kid, kede</i>) and one which excludes (<i>kemam, aki</i>).
            <li>English is less ambiguous in the 3rd person singular as it has separate forms for male (he,him), female 
                (she,her), and non-human (it) whereas Palauan combines these into <i>ngii</i> and <i>ng</i>.
            <li>The rules for choosing between the multiple forms within each (e.g. I/me and <i>ngak/ak</i>) are not the same.  For English, 
                the difference is object/subject whereas in Palauan the difference is emphatic/nonemphatic.  In the table above, the emphatic 
                form of each Palauan pronoun is indicated with boldface font.
            </ul>
            </p>

            <a id="relative"><h2>Relative pronouns</h2></a>
            <p>The English relative pronouns include the following:that, which, who, whom, whose, whichever, whoever, whomever.</p>
            <p>The Palauan relative pronouns include the following: <i>ngara, keskelel, ker, keltang, oingarang, se el, se er a, techa, tela</i>.</p>

            <a id="demonstrative"><h2>Demonstrative pronouns</h2></a>
            
            <p>The English demonstrative pronouns are that, this, those, and these.  They are split by count (i.e. singular or plural) and by distance from the speaker (i.e. near or far).
            For these four English pronouns, there are 16 Palauan equivalents which further split by distance from listener and by human, animal, or thing.  Here is a table showing both
            the English and Palauan demonstrative pronouns:
            <br>

            <table>
            <tr>
            <td>
                <table>
                <tr><td></td>                                 <th>Count</th><th>Dstnc Spkr</th><th>Dstnc Lstnr</th><th>Describes</th></tr>
                <tr class=english><td>this</td>               <td>sing</td> <td>near</td><td>n/a </td> <td>any</td></tr>
                <tr class=english><td>that</td>               <td>sing</td> <td>far </td><td>n/a </td> <td>any</td></tr>
                <tr class=english><td>these</td>              <td>plur</td> <td>near</td><td>n/a </td> <td>any</td></tr>
                <tr class=english><td>those</td>              <td>plur</td> <td>far </td><td>n/a </td> <td>any</td></tr>

                <tr class=palauan><td><i>tiang</i></td>       <td>sing</td> <td>near</td><td>near</td> <td>thing</td></tr>
                <tr class=palauan><td><i>tiei</i></td>        <td>sing</td> <td>near</td><td>far </td> <td>thing</td></tr>
                <tr class=palauan><td><i>tilechang</i></td>   <td>sing</td> <td>far </td><td>near</td> <td>thing</td></tr>
                <tr class=palauan><td><i>sei</i></td>         <td>sing</td> <td>far </td><td>far </td> <td>thing</td></tr>

                <tr class=palauan2><td><i>ngikang</i></td>     <td>sing</td> <td>near</td><td>near</td> <td>human,animal</td></tr>
                <tr class=palauan2><td><i>ngilei</i></td>      <td>sing</td> <td>near</td><td>far </td> <td>human,animal</td></tr>
                <tr class=palauan2><td><i>ngilechang</i></td>  <td>sing</td> <td>far </td><td>near</td> <td>human,animal</td></tr>
                <tr class=palauan2><td><i>ngikei</i></td>      <td>sing</td> <td>far </td><td>far </td> <td>human,animal</td></tr>

                <tr class=palauan><td><i>aikang</i></td>      <td>plur</td> <td>near</td><td>near</td> <td>thing,animal</td></tr>
                <tr class=palauan><td><i>ailei</i></td>       <td>plur</td> <td>near</td><td>far </td> <td>thing,animal</td></tr>
                <tr class=palauan><td><i>ailechang</i></td>   <td>plur</td> <td>far </td><td>near</td> <td>thing,animal</td></tr>
                <tr class=palauan><td><i>aikei</i></td>       <td>plur</td> <td>far </td><td>far </td> <td>thing,animal</td></tr>

                <tr class=palauan2><td><i>tirkang</i></td>     <td>plur</td><td>near</td><td>near</td> <td>human</td></tr>
                <tr class=palauan2><td><i>tirelei</i></td>     <td>plur</td><td>near</td><td>far </td> <td>human</td></tr>
                <tr class=palauan2><td><i>tirilechang</i></td> <td>plur</td><td>far </td><td>near</td> <td>human</td></tr>
                <tr class=palauan2><td><i>tirkei</i></td>      <td>plur</td><td>far</td> <td>far</td> <td>human</td></tr>
                </table>
            </td>
            <td>
                <p>Some observations about the Palauan demonstrative pronouns:
                <ul>
                <li>The singular forms group humans and animals together but keep things separate.
                <li>The plural forms group animals and things together but keep humans separate.
                <li>This organization shows some identifying suffix patterns:
                    <ul>
                    <li>The <i>ti-</i> stem identifies the singular thing form.
                    <li>The <i>ngi-</i> stem identifies the singular human,animal forms.
                    <li>The <i>ai-</i> stem identifies the plural thing,animal forms.
                    <li>The <i>tir-</i> stem identfies the plural human forms.
                    <li><i>sei</i> does not fit the above patterns.
                    </ul>
                </ul>
            </td>
            </tr>
            </table>

            <p>A different organization along the distance axes reveals identifying patterns in the suffixes:

            <table>
            <tr>
            <td>
                <table>
                <tr><td></td>                                 <th>Count</th><th>Dstnc Spkr</th><th>Dstnc Lstnr</th><th>Describes</th></tr>
                <tr class=palauan><td><i>tiang</i></td>       <td>sing</td> <td>near</td><td>near</td> <td>thing</td></tr>
                <tr class=palauan><td><i>ngikang</i></td>     <td>sing</td> <td>near</td><td>near</td> <td>human,animal</td></tr>
                <tr class=palauan><td><i>aikang</i></td>      <td>plur</td> <td>near</td><td>near</td> <td>thing,animal</td></tr>
                <tr class=palauan><td><i>tirkang</i></td>     <td>plur</td><td>near</td><td>near</td> <td>human</td></tr>

                <tr class=palauan2><td><i>tiei</i></td>        <td>sing</td> <td>near</td><td>far </td> <td>thing</td></tr>
                <tr class=palauan2><td><i>ngilei</i></td>      <td>sing</td> <td>near</td><td>far </td> <td>human,animal</td></tr>
                <tr class=palauan2><td><i>ailei</i></td>       <td>plur</td> <td>near</td><td>far </td> <td>thing,animal</td></tr>
                <tr class=palauan2><td><i>tirelei</i></td>     <td>plur</td><td>near</td><td>far </td> <td>human</td></tr>

                <tr class=palauan><td><i>tilechang</i></td>   <td>sing</td> <td>far </td><td>near</td> <td>thing</td></tr>
                <tr class=palauan><td><i>ngilechang</i></td>  <td>sing</td> <td>far </td><td>near</td> <td>human,animal</td></tr>
                <tr class=palauan><td><i>ailechang</i></td>   <td>plur</td> <td>far </td><td>near</td> <td>thing,animal</td></tr>
                <tr class=palauan><td><i>tirilechang</i></td> <td>plur</td><td>far </td><td>near</td> <td>human</td></tr>

                <tr class=palauan2><td><i>sei</i></td>         <td>sing</td> <td>far </td><td>far </td> <td>thing</td></tr>
                <tr class=palauan2><td><i>ngikei</i></td>      <td>sing</td> <td>far </td><td>far </td> <td>human,animal</td></tr>
                <tr class=palauan2><td><i>aikei</i></td>       <td>plur</td> <td>far </td><td>far </td> <td>thing,animal</td></tr>
                <tr class=palauan2><td><i>tirkei</i></td>      <td>plur</td><td>far</td> <td>far</td> <td>human</td></tr>
                </table>
            </td>
            <td>
                <p>More observations about the Palauan demonstrative pronouns:
                <ul>
                <li>The <i>-(k)ang</i> suffix is for near, near 
                <li>The <i>-lei</i> suffix is for near, far 
                <li>The <i>-lechang</i> suffix is for far, near 
                <li>The <i>-kei-</i> suffix is for far, far
                <li><i>sei</i> and <i>tiei</i> do not fit this pattern
                </ul>
            </td>
            </tr>
            </table>

            <a id="indefinite"><h2>Indefinite pronouns</h2></a>

            <p>The English relative pronouns include the following: anybody,
            anyone, anything, each, either, everybody, everyone, everything,
            neither, nobody, no one, nothing, one, somebody, someone,
            something, both, few, many, several, all, any, most, none, some.</p>

            <p>Some Palauan equivalents are <i>ngdiak a tal chad, bekel, ngii di el chad, rokir, rokiu, ngdiak a ngarang, kesai, bebil, oumesingd</i>.

            <a id="reflexive"><h2>Reflexive pronouns</h2></a>
            <p>The English reflexive pronouns include the following: myself, yourself, himself, herself, itself, ourselves, yourselves, themselves.</p>
            <p>There are no Palauan equivalents.  To express a similar idea, the word <i>di</i> is typically used.  E.g. <i>Ng di mle ngak el sobekak.</i>
            translates figuratively to "I kicked myself."</p>

            <a id="interrogative"><h2>Interrogative pronouns</h2></a>
            <p>The English interrogative pronouns include the following:  what, who, which, when, why, whom, whose, how.</p>
            <p>Some Palauan equivalents include the following: <i>ngerang, techang, mekesakl, oingarang, engerang, makerang, ngara meng, ngara uchul, keskelel, kuskelii</i>.</p>

            <a id="possessive"><h2>Possessive pronouns</h2></a>
            <p>The English possessive pronouns include the following: my, your, his, her, its, our, their, mine, yours, his, hers, ours, theirs.</p>
            <p>Palaun possessive pronouns do not typically exist as independent words but rather as suffixes on nouns.  Please refer to the 
            <a href="nouns.php">nouns grammar page</a> for more information.  For foreign words, however, possession uses the linking work <i>er</i>
            and an emphatic personal pronoun (e.g. <i>chert er kau</i>).
            </p>

            <a id="affix"><h2>Affix pronouns</h2></a>
            <p>
            Both Palauan nouns and verbs can have pronoun stems affixed as a prefix identifing the subject.  Verbs can additionally have pronouns as a suffix identifying the object.
            Examples:
            <ul>
            <li><i>Ng diak lsensei.</i> He's not a teacher.
            <li><i>Ng diak kimodengelterir.</i> We (exclusive) do not know them.
            </ul>
            Here is a list of affix pronouns:
            <?php print_table("select pal,eng from all_words3 where pos like 'affix'"); ?>
            <p>
        </div>

        <?php belau_footer("/grammar/pronouns.php"); ?>
    </div>

</body>
</html>
