<?php 
include '../functions.php'; 
$title = "Palauan Numbers";
html_top($title);
belau_header($title);
?>

    <div id="content-container">

        <div id="aside">
            Quick links:
            <ul>
            <li><a href="#stems">Common Number Stems</a><br>
            <li><a href="#humans">Humans</a><br>
            <li><a href="#time">Time</a><br>
            <li><a href="#animals">Animals</a><br>
            <li><a href="#long">Long Objects</a><br>
            <li><a href="#bananas">Banana Bunches</a><br>
            <li><a href="#rafts">Rafts of Logs</a><br>
            <li><a href="#objects">Other objects</a><br>
            <li><a href="#ordinal">Ordering</a><br>
            <li><a href="#counting">Counting</a><br>
            </ul>
        </div>
        <div id="content"> 
        <?php echo "<h2>$title</h2>"; ?>
        <p>
        The following is a brief set of notes for Palauan number words.  For a longer
        exploration, please refer to Chapter 24 in the <a href='/books/75josephs.php'>Joseph Grammar Book</a>.
        Another reference is a <a href='/misc/pdf.php?file=counting'>set of slides</a> prepared by Asa Timarong and Jay Watanabe for a 2012 presentation.

        <p>
        Palauan has different words for different numbers depending on what is being counted.
        For example, there are different words for the number three depending on where it refers
        to humans, or animals, or units of time, or long objects, or bunches of bananas, or rafts of logs tied
        together.  Also, there is a separate word for the number three for when someone is 
        counting as for example a child might do while jumping rope.  Finally, there are
        separate words for numbers when they are used to indicate relative order corresponding
        to the English words first, second, third.  Note that many contemporary Palauans no
        longer use all of the unique numbers; for example, the number words unique for bunches
        of bananas, long objects, rafts of logs tied together are typically not used, instead, most 
        contemporary Palauan speakers use the set of numbers reserved more generally for
        non-living things.
        </p>
        <p>
        Although this is complicated, there are some simplifications.  The first is that the
        unique numbers, depending on what is being referred to, do not go above ten.  Above
        ten, there is just a single set of numbers.  A second simplification is that most of 
        the number words share a common stem.  For example, the common stem for the 'two' words
        is '-ru.'  The words for humans and units of time are the same except that humans has
        a 't' in front.  Here is a table of the common stems: 
        </p>

        <a id="stems">
        <p class='tab'>
                <table>
                <tr><td>2, -ru</td><td>6, -lolem</td></tr>
                <tr><td>3, -de</td><td>7, -uid</td></tr>
                <tr><td>4, -ua</td><td>8, -ai</td></tr>
                <tr><td>5, -im</td><td>9, -tiu</td></tr>
                </table>
        </p>

        <p>A final simplification is that the multiples of ten between 20 and 100 basically add a 'ok' prefix to the above stems in such a way that the
        'ok' prefix has the meaning of multiplying the stem by ten.  For example, 'okai' is 'ok' plus '-ai' resulting in 80 (10 times 8). 
        </p>

        <p>
        <a id="humans">
        <a id="animals">
        <a id="objects">
        <a id="rafts">
        <a id="bananas">
        <a id="time">
        <a id="long">
        Without further ado, here are the numbers used for humans, units of time, animals and non-living things, long objects, bunches of bananas, and rafts of logs tied together.
        Notice that 1-3 have unique forms for each, but beyond that they begin to converge.  From 20 onward, they are all the same.  

        <?php
            echo "<p class='tab'>";
            echo "<table>";
            echo "<tr><td></td><td>Humans</td><td>Units of<br>Time</td><td>Animal<br>Objects</td><td>Long<br>Objects<td>Bunches of<br>bananas</td><td>Rafts</td></tr>";
            echo "<tr><td>";
            print_table("select quantity from numbers where animal=1 order by quantity", False);
            echo "</td><td>";
            print_table("select palauan from numbers where human=1 order by quantity", False);
            echo "</td><td>";
            print_table("select palauan from numbers where time=1 order by quantity", False);
            echo "</td><td>";
            print_table("select palauan from numbers where animal=1 order by quantity", False);
            echo "</td><td>";
            print_table("select palauan from numbers where longobj=1 order by quantity", False);
            echo "</td><td>";
            print_table("select palauan from numbers where banana=1 order by quantity", False);
            echo "</td><td>";
            print_table("select palauan from numbers where raft=1 order by quantity", False);
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        ?>

        <p>
        <a id="ordinal">
        <a id="counting">
        Here are the Palaun words for first, second, third, etc., listed as Ordering, and the words for counting.  Notice that these
        sets only go to 10.

        <?php
            db_connect();
            echo "<p class='tab'>";
            echo "<table>";
            echo "<tr><td></td><td align=right>Ordering</td><td>Counting</td></tr>";
            echo "<tr><td>";
            print_table("select quantity from numbers where ordinal=1 and quantity < 11 order by quantity",False);
            echo "</td><td>";
            print_table("select palauan from numbers where ordinal=1 and quantity < 11 order by quantity",False);
            echo "</td><td>";
            print_table("select palauan from numbers where counting=1 and quantity < 11 order by quantity",False);
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        ?>

        </div>

        <?php belau_footer("/grammar/numbers.php"); ?>
    </div>

</body>
</html>
