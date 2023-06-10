#! /s/std/bin/perl

use strict;

my $state;
my $state_months = 0;
my $state_yaks   = 1;
my $state_expressions = 2;

my %titles = {};


print << "EOF";
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<HTML>
<HEAD>
<TITLE>Palaun Hanahuda</TITLE>
<LINK REL=stylesheet TYPE="text/css" 
      HREF="http://www.cs.wisc.edu/~johnbent/hanahuda.css">
</HEAD>
<BODY>

<div >
<ul id="list-nav">
<li><a href="#cards">The cards</a></li>
<li><a href="#play">The rules</a></li>
<li><a href="#yaks">The yaks</a></li>
<li><a href="#expressions">Expressions</a></li>
<li><a href="#contact">Contact us</a></li>
</ul>
</div>

<div id="content">
<CENTER>
<H1><B>Hanahuda</B></H1>
<H2><B>Me dou kat!</B></H2>
</CENTER>
<P>
Palauns play a card game named <I>hanahuda</I> derived from a 
variant of the Japanese game, <I>hanafuda</I>.  The game play is the same,
but the scoring is slightly different.  An older version of this webpage 
included both the Palaun and Japanese translations.  This version is now
exclusively focused on the Palaun variant.  The original webpage is archived
<a href="./japanese.html">here</a>.  
<HR>
<a name=cards>
<B>The Cards</B>
<P class="hana">The cards are divided into twelve sets.  Any card in a set can 
be used to capture any other card in that set.  A card can not capture a card
from a different set.  
Six of the sets have an <I>okeiim</I> (fifty) card: 
the first cards in the <i>mats</i>, <i>chume</i>, <i>sakura</i>, <i>buil</i>, <i>nisoro</i>, and <i>kiri</i> sets. 
Ten of the sets (all except <i>buil</i> and <i>kiri</i>) have 
<i>tang</i> cards; these are the ones with the ribbons.  Decorated cards in
a set that aren't <i>tang</i> or <i>okeiim</i> are <i>teruich</i> (ten); nine
sets each have one <i>teruich</i> card.  For
example, the first cards in the <i>mases</i>, <i>chudel</i>, <i>bara</i>, and <i>babii</i> sets are
<i>teruich</i>.  The other (undecorated) cards are <i>kas</i> (trash).  The
final two cards in each set below (except for <i>nisoro</i>) are <i>kas</i>.  <i>Nisoro</i>
is the only set that has just one <i>kas</i>.
<p class="hana">
Palauns typically have a name for one card in a set
and then refer to all cards in that set by that name. For example, 
<I>sechou</I> is often
used to refer to the <I>okeiim</I> card in the first set below and then other 
cards in that set can be referred to as <i>tang er a sechou</i> or 
<i>kas er a sechou</i>.  A <i>teruich</i> card can be referred to in the 
same way (e.g. <i>teruich er a buil</i>). [Hover over a card to see the name
of that card.]
<P>
EOF

while( <> ) {
    chomp();
    if ( /# Months/ ) {
        startTable();
        $state = $state_months;
    } elsif ( /# Yaks/ ) {
        endTable();
        addRules();
        addYakDescription();
        startTable();
        $state = $state_yaks;
    } elsif ( /# Expressions/ ) {
        endTable(); # end the <i>yak</i> table
        startExpressions();
        $state = $state_expressions;
    } elsif ( /^#/ ) {
        next; # skip comments
    } else {
        if ( $state == $state_months ) {
            printMonthRow( $_ );
        } elsif ( $state == $state_yaks ) {
            printYakRow( $_ );
        } else {
            printExpression( $_ );
        }
    }
}

print "</UL>\n";
print << "EOF";
<A name=contact><BR><HR><P><B>Contact us</B>
<p>
Please send any comments, questions, 
different <i>yak</i>s, or frequently heard expressions to 
<a href="mailto:ciramk\@gmail.com">
Charlene Iramk</a>.
</div>
</BODY>
</HTML>
EOF

sub
startExpressions {
    print "<BR><HR><P><A name=expressions><B>Terminology and frequently "
        . "heard expressions</B><br>\n";
    print "<UL>\n";
}

sub
printExpression {
    my @items = split( /\|/, $_[0] );
    print "<li class=\"terms\"><i>$items[0]</i> "
        . "<ul><li>$items[1](submitted by $items[2])</ul>\n";  
}

sub
addRules {
    print "<A name=play><BR><HR><P><B>The rules</B>\n";
    print "<P>\n";
    print "<TABLE WIDTH = 65%><TR>\n";
    print "<TD>\n";
print << "EOF";
<P class="hana">

The dealer shuffles the cards.  Experienced players shuffle in a really cool
way that looks nothing like how Americans shuffle cards.  They throw a few
cards from a shrinking stack in one hand to a growing stack in another.
Inexperienced players just place the cards flat on the table and mix them all
up by moving them around.  The dealer offers the shuffled deck to the player on
her right (the cutter) who can cut them if she desires.  If she cuts them, she
takes a small number of cards from the top of the deck, looks at the bottom
card, and then places those cards in the middle of the table.  The dealer then
asks the cutter how she wants the cards distributed.  She can either ask for
the cards to go to the table first or to her.  If she asks for the cards to
come to her, the dealer then deals her 10 cards (9 in a 3 player game, 8 in a 4
player game).  The dealer then gives out cards to the rest of the players and
then places 10 (or 9, or 8) cards face-up circling the cards that the cutter
placed in the middle of the table.  The dealer then places the rest of the
cards on top of the cards that the cutter placed in the middle of the table.
The cutter can also ask for half of the cards to be given to her and then
half to the table.  Or the cutter can ask for the cards to go to the table 
first and then to the players.  The cutter's knowledge of the bottom 
card can be a large advantage at the end of the game.  If any player has
three of the same set (<i>chitsiobiki</i>), that player can trade one of
those cards for a new card from the deck.  Other players may ask that 
player to show all three cards.
</p>

<p class="hana">
In the
four player game, players on opposite sides of the table are a team.  Players
alternate.  On a turn, first a player plays a card from hand, capturing a
face-up card if it is in a set with the played card.  Otherwise the played
card is added to the face-up collection.  The same player then draws the top
stack card and attempts to capture a face-up card.  If no capture is possible
the card is just added to the face-up collection.  The action of playing the
top stack card is called <I>omkais</I>.  Dealing is <I>merous</I>.  The
losing player (or team) deals for the next round.  When playing in teams,
one player typically collects and arranges the captured cards for both players 
on that team; this is called <i>omechobech</i>.  Cards are organized by
grouping the <i>okeiim</I>, the <i>tang</i>, the <i>teruich</i>, and the
<i>kas</i>.  The <i>kas</i> cards are typically just thrown in a pile while
the others are nicely placed in a vertical stack so that each card can easily
be seen.  When other players are curious about the captured <i>kas</i>, they
can ask the player to spread them out so that they can be identified. 

<P class=\"hana\"> To score, players must collect </i><i>yak</i>s</i> (various
combinations of cards).  If no <i>yak</i>s are collected, then the sum of the
<i>okeiim</I> and the <i>teruich</i> cards can be used to determine a winner or
the game can be called a draw.  If a player (or team) gets <i>arasi</i> or
</i>nanatang</i>, then that player can choose to end the round prematurely.  If
she does so, the round ends at that time and only that <i>yak</i> is scored.
The game continues through multiple rounds until one team reaches a
predetermined score such as 5000 points.

</P>
EOF
    print "<TD><IMG WIDTH=\"180\" hspace=10 HEIGHT=\"120\" "
            . "SRC=\"layout.gif\">&nbsp;\n";
    print "</TABLE>\n";
}

sub
printMonthRow {
    my @items = split( /:/, $_[0] );
    print "<TR>\n";
    print "\t<TD ALIGN=\"center\"><B>$items[1]</B>";
    print "\t<TD>\n";
    my $month = lc(substr($items[0],0,3));
    my @titles = split( /,/, $items[2] );
    for ( my $i = 1; $i <= 4; $i++ ) {
        my $title = ($titles[$i-1]?$titles[$i-1]:"kas") . " er a " . $items[1];
        $title =~ s/\s+/ /g;
        $titles{$month.$i} = $title;
        print "\t\t<IMG WIDTH=\"41\" HEIGHT=\"70\" TITLE=\"$title\" "
            . "SRC=\"cards/${month}$i.gif\">&nbsp;\n";
    }
}

sub
addYakDescription {
    print "<BR><HR><P><A name=yaks><B>The yaks</B>\n";
}

sub
printYakRow {
    my @items = split( /:/, $_[0] );
    print "<TR>\n";
    print "\t<TD ALIGN=\"center\"><B>$items[0]</B><br><i>$items[1]</i>\n";
    print "\t<TD ALIGN=\"right\">$items[2]\n";
    my @cards = split( /,/, $items[3] );
    print "\t<TD>\n";
    foreach my $card ( @cards ) {
        my $title = $titles{$card};
        print "\t\t<IMG WIDTH=\"41\" HEIGHT=\"70\" TITLE=\"$title\" "
            . "SRC=\"cards/$card.gif\">&nbsp;\n";
    }
    
}

sub
startTable {
    print "<TABLE BORDER=1 CELLSPACING=2 CELLPADDING=5>\n";
}

sub
endTable {
    print "</TABLE>"
}
