<?php 
include '../functions.php'; 
$title = "Palauan Nouns";
html_top($title);
belau_header($title);
?>

    <div id="content-container">

        <div id="aside">
            Quick links:
            <ul>
            <li><a href="#eset">Example e-set noun</a><br>
            <li><a href="#iset">Example i-set noun</a><br>
            <li><a href="#aset">Example a-set noun</a><br>
            <li><a href="#uset">Example u-set noun</a><br>
            <li><a href="#exceptions">Exceptions</a><br>
            </ul>
        </div>
        <div id="content"> 
            <?php echo "<h2>$title</h2>"; ?>
            <p>Note about this page: This is a short attempt to list some of the simple rules
            for Palauan nouns, especially to list an example of each of the four sets.  For
            a much longer and comprehensive explanation, please refer to Lewis Josephs'
            definitive <a href="http://www.jstor.org/stable/3623305">Palauan grammar book</a>.

            <p>Palauan nouns are a bit different from English nouns since they exist in
            multiple forms.  They can have <i>unpossessed</i> and <i>possessed</i> forms
            and some have <i>plural</i> forms. Note that the plural forms are only for
            nouns describing humans.</p>

            <p class="example">
                Examples:<ul>
                <li><i>ngalek</i>, child; <i>ngelekek</i>, my child; <i>rengelekek</i>, my children.
                <li><i>charm</i>, animal; <i>chermek</i>, my animal (pet). Note there is no plural form. 
                </ul>
            </p>

            <p>The possessed forms use <i>pronoun suffixes</i> to indicate
            ownership.  Unfortunately, there are no known rules to convert a noun
            between its unpossessed and possessed forms.  Each must be
            memorized independently.  There are a few patterns which are typically, but not always followed.
            Single vowels are typically "weakened" into the weak 'E,' double vowels usually become single
            and infrequently reduce to weak 'E' or are deleted, and vowel clusters usually lose one of the 
            vowels (usually the stronger one).

            <p>However, there are rules (with a few
            exceptions) to convert from one possessed form to another.  Therefore, to learn a Palauan noun
            requires learning its unpossessed form, at least one of its singular unpossessed forms, as well
            as the rules to convert amongst the possessed forms. First,
            let's list what the seven possessed forms are.  They correspond to
            the seven Palauan pronouns: </p>

                <table>
                <tr><td>                      </td><th>Singular    </th>  <th>Plural</th>                                            </tr>
                <tr><th rowspan='2'>1st Person</th><td rowspan='2'>my</td><td>our (inclusive)</td></tr></tr><td>our (exclusive)</td></tr>
                <tr><th>2nd Person            </th><td>your        </td>  <td>your (you all's)</td>                                  </tr>
                <tr><th>3rd Person            </th><td>his/hers/its</td>  <td>theirs</td>                                            </tr>
                </table>

            <p>These are similar to English with three exceptions.
            <ul>
            <li>English has three 1st person singular pronouns for <i>his, hers, its</i> whereas Palauan has just one.
            <li>English has only one 1st person plural pronoun for <i>ours</i>.  Palauan has two: the inclusive form includes the listener; the exclusive form excludes the listener.
            <li>English has the same pronoun for both 2nd person singular and plural, <i>you</i>,  whereas Palauan has distinct pronouns for each. 
            </ul>
            </p>

            <p>Although there are no rules for converting between a noun's
            unpossessed and possessed forms, there are rules (with some
            exceptions) for converting between the various possessed forms.
            Possessed nouns use pronoun suffixes to indicate the possessor of the noun.
            Each possessed noun belongs to one of four sets: the <i>e-set,
            u-set, i-set,</i> or <i>a-set</i>.  A noun's set is identified by
            the final vowel in its singular forms and 1st person plural
            inclusive form.  For example, <i>charm</i> is an e-set noun since
            the final vowel in <i>chermek</i> is an e.  The below table shows
            all possessed forms of the noun <i>charm</i> using italics to show 
            the e-set pronoun suffixes which are the same across all e-set nouns.

            <a id="eset"><h2>E-set for <i>charm</i> (animal)</h2></a>

                <table>
                <tr><td>                      </td><th>Singular    </th>  <th>Plural</th>                                            </tr>
                <tr><th rowspan='2'>1st Person</th><td rowspan='2'>cherm<i>ek</i></td><td>cherm<i>ed</i></td></tr></tr><td>cherm<i>am</i></td></tr>
                <tr><th>2nd Person            </th><td>cherm<i>em</i>        </td>  <td>cherm<i>iu</i></td>                                  </tr>
                <tr><th>3rd Person            </th><td>cherm<i>el</i></td>  <td>cherm<i>ir</i></td>                                            </tr>
                </table>
            </p>

            <a id="iset"><h2>I-set for <i>oach</i> (leg)</h2></a>
            <p>
            This table similarly shows the i-set possessor suffixes for the Palauan word for leg, <i>oach</i>:

                <table>
                <tr><td>                      </td><th>Singular    </th>  <th>Plural</th>                                            </tr>
                <tr><th rowspan='2'>1st Person</th><td rowspan='2'>och<i>ik</i></td><td>och<i>id</i></td></tr></tr><td>och<i>emam</i></td></tr>
                <tr><th>2nd Person            </th><td>och<i>im</i>        </td>  <td>och<i>emiu</i></td>                                  </tr>
                <tr><th>3rd Person            </th><td>och<i>il</i></td>  <td>och<i>erir</i></td>                                            </tr>
                </table>
            </p>
        
            <p>Notice that the 'e' in the 'emiu' and 'erir' suffixes is not included in all i-set nouns depending on the preceding letter(s). This is true
            for u-set and a-set nouns as well.  The 'e' will be present when the preceding letters are 'ch' as in <i>ochemiu</i> and absent
            when the preceding letters are 'ng' as in 'rengmiu.' 
            </p>

            <a id='aset'><h2>A-set for <i>chur</i> (tongue)</h2></h2></a>
            <p>
            Here are the possessed forms for the a-set noun <i>chur</i> (tongue):

                <table>
                <tr><td>                      </td><th>Singular    </th>  <th>Plural</th>                                            </tr>
                <tr><th rowspan='2'>1st Person</th><td rowspan='2'>chur<i>ak</i></td><td>chur<i>ad</i></td></tr></tr><td>chur<i>emam</i></td></tr>
                <tr><th>2nd Person            </th><td>chur<i>am</i>        </td>  <td>chur<i>emiu</i></td>                                  </tr>
                <tr><th>3rd Person            </th><td>chur<i>al</i></td>  <td>chur<i>erir</i></td>                                            </tr>
                </table>
                   </p>

            <a id='uset'><h2>U-set for <i>reng</i> (heart/spirit)</h2></a>
            <p>
            Here are the possessed forms for the u-set noun <i>reng</i> (heart/spirit):

                <table>
                <tr><td>                      </td><th>Singular    </th>  <th>Plural</th>                                            </tr>
                <tr><th rowspan='2'>1st Person</th><td rowspan='2'>reng<i>uk</i></td><td>reng<i>ud</i></td></tr></tr><td>reng<i>mam</i></td></tr>
                <tr><th>2nd Person            </th><td>reng<i>um</i>        </td>  <td>reng<i>miu</i></td>                                  </tr>
                <tr><th>3rd Person            </th><td>reng<i>ul</i></td>  <td>reng<i>rir</i></td>                                            </tr>
                </table>
            </p>

            <p>Note that <i>oach</i> and <i>chur</i> have 'e' in their 2nd and 3rd person plural forms but <i>reng</i> does not.
            

            <a id="exceptions"><h2>Exceptions</h2></a> <p> Here are a few nouns that are
            exceptions to the suffix pronouns rules: <i>chim, mlai, blai,
            chetil, obengkek.</i>  </p> <p> Note that some nouns such as <i>chetil</i>
            and <i>tkul</i> do not have an unpossessed form and some, typically
            of foreign original, like <i>chert</i> and <i>dengua</i> do not have
            possessed forms.

            <h2>TODO: add something here later about plural forms, prefix pronouns, and action nouns....</h2>.
            
        </div>

        <?php belau_footer("/grammar/nouns.php"); ?>
    </div>

</body>
</html>
