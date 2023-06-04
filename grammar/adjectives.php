<?php 
include '../functions.php'; 
$title = "Palauan Adjectives";
html_top($title);
belau_header($title);
?>

    <div id="content-container">

        <div id="aside">
            Quick links:
            <ul>
            <li><a href="#verbs">Resulting State Verbs</a><br>
            <li><a href="#vas">Anticipating State Verbs</a>
            <li><a href="#nouns">State Verbs with Related Nouns</a><br>
            <li><a href="#reng"><i>Reng</i> Idioms as Adjectives</a><br>
            </ul>
        </div>

        <div id="content"> 
        <?php echo "<h2>$title</h2>"; ?>
        <p>
        The following is a brief discussion about Palauan adjectives.  For a longer
        exploration, please refer to discussions of state verbs in the <a href="/books/handbook1.php">Joseph Handbooks</a>.
        According to the official Lewis Joseph grammar book of Palauan, there are no Palauan
        parts of speech called adjectives.  However, Palauan does, of course, have words used
        to describe other words.  In English, we call these words adjectives.  Examples of
        English adjectives are dangerous, beautiful, and hot.  
        </p>

        <a id="verbs"></a><h2>Palauan Resulting State Verbs</h2>
        <p>In Palauan, words corresponding to English adjectives are 
        called state verbs.  There are several types of Palauan state verbs.  The most common
        are resulting state verbs which occur as a result of a verb.
        Some examples:
            <ul>
            <li>Someone hides something which results in it being hidden.
                <ul><li>In Palauaun, <i>omart</i> is the verb 'to hide,' and <i>blart</i> is the resulting state verb corresponding to the English adjective hidden.
                </ul>
            <li>Someone heats something which results in it being hot.
                <ul><li>In Palauaun, <i>mengeald</i> is the verb 'to heat,' and <i>mekeald</i> is the resulting state verb corresponding to the English adjective hot.
                </ul>
            </ul>
        </p>

        <p>Here is a list of seven random Palauan verbs and their resulting state verbs:
        </p>
        <p class='tab'>
        <div>
        <?php
            show_words("select id,stem from all_words3 where pos like 'v.r.s.' and length(eng) > 1 order by rand() limit 7",False);
            #print_table("select * from verb_adjs order by rand() limit 7");
        ?>
        </div>
        </p>

        &nbsp;
        <a id="vas"></a> 
        <h2>Palauan Anticipating State Verbs</h2>
        <p>Anticipating state verbs in Palauan are like resulting state verbs.  However, instead of describing
        the state of something <i>after</i> a verb has modified it, these describe the state of something
        <i>before</i> a verb is anticipated to modify it.  Here's seven random Anticipating State Verbs:
        <p class='tab'>
        <?php
            show_words("select id,stem from all_words3 where pos like 'v.a.s.' and length(eng) > 1 order by rand() limit 7",False);
        ?>
        </p>

        &nbsp;
        <a id="nouns"></a>
        <h2>State Verbs with Related Nouns</h2>
        <p>
        In English, a common thing to do is to ask 'how XXXX is something,' where XXXX is an
        adjective.  For example, 'how hot is that,' or 'how dangerous is that,' are common
        English expressions.  

        <p>
        This is true in Palauan as well in a form like, 
        '<i>ng uangarang a kleldelel</i>,' which translates literally perhaps to something like,
        'it is like what, its heat,' or figuratively as, 'how hot is it.'  The word <i>kleldelel</i>
        is a possessed noun meaning 'its heat.'  See the <a href="nouns.php">nouns page</a> for a
        longer explanation of possessed nouns.

        <p>
        Many of these Palauan nouns have related <i>state verbs</i> which translate to, and are 
        used as, English adjectives.
        Here is a list of seven random Palauan nouns along with their corresponding state verbs. 
        <p class='tab'>
        <?php
            print_table("select * from noun_adjs order by rand() limit 7");
        ?>
        </p>


        <a id="reng"></a><h2><i>Reng</i> Idioms as Adjectives</h2>
        <p>
        There are many Palauan expressions which use a state verb to describe the Palauan word <i>reng</i> which means spirit or heart.
        These are idioms which mean their literal and figurative meanings are not the same.  Typically, but not always, the figurative meaning describes an emotion.
        An example is <i>kesib a reng,</i> which literally means a sweaty heart but figuratively it means to be angry.  Here is a list of seven random examples of
        these <i>reng</i> idioms:
        <p class='tab'>
        <?php
            print_table("select pal as Palauan,eng as English from all_words3 where pal rlike 'rengul' && length(pal)>5 && !isnull(eng) order by rand() limit 7");
        ?>
        </p>

        </div>

        <?php belau_footer("/grammar/adjectives.php"); ?>
    </div>

</body>
</html>
