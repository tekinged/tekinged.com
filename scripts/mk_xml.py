#! /usr/bin/env python

import argparse
import sys
import pymysql
import string
import re
import os
import belau

def query(q,c):
  c.execute(q)
  return c.fetchall()

def print_footer(xml):
  xml.write( '</d:dictionary>\n' )

def print_header(xml):
  xml.write( '''<?xml version="1.0" encoding="UTF-8"?>
<!--
  This is the Palauan dictionary source file as produced using the tekinged.com database.
  It can be built using Dictionary Development Kit.
-->
<d:dictionary xmlns="http://www.w3.org/1999/xhtml" xmlns:d="http://www.apple.com/DTDs/DictionaryService-1.0.rng"> \n''')

def add_image(wid,pal,xml):
  if os.path.isfile('OtherResources/Images/%d.jpg' % wid ):
    xml.write( '\t<span class="picture"><img src="Images/%d.jpg" alt="%s"/></span>\n' % ( wid, pal ))
    
def space(multiplier=1):
  width=5*multiplier
  return '<span style="display:inline-block; width: %dpx;"></span>' % width

def add_branch_words(branches,xml):
  #print row
  for row in branches: 
    display_word(row,'b',xml)

def display_word(row,htype,xml):
  def get_eng(eng):
    if eng:
      return eng
    else:
      return ''

  xml.write( '\t<div class="entry">%s<%s style="display: inline">%s</%s> %s <i>%s</i> %s %s\n' % 
            ( space(), htype, row['pal'], htype, space(), row['pos'], space(), get_eng(row['eng']) ))
  if row['pdef']:
    xml.write( '\t<span class="pdef" d:priority="0">%s</span>\n' % row['pdef'])
  xml.write( '\t</div>\n' )

def get_pal(wid,c):
  q='select pal from all_words3 where id=%d' % wid
  pal = query(q,c)[0]['pal']
  return pal

def get_regexes(word,branches):
  regex=''
  branches = branches + (word,)
  for branch in branches:
    regex+="lower(palauan) regexp '[[:<:]]%s[[:>:]]' or " % branch['pal']
  regex+='0'
  return regex 

def add_proverbs(word,branches,c,xml):
  add_sentences(word,branches,c,'proverbs','PROVERBS','english',xml,'explanation')

def add_examples(word,branches,c,xml):
  add_sentences(word,branches,c,'examples','JOSEPHS DICTIONARY EXAMPLE SENTENCES','english',xml)

def add_uploads(word,branches,c,xml):
  add_sentences(word,branches,c,'upload_sentence','VOLUNTEER UPLOADED EXAMPLE SENTENCES','eng',xml)

def add_sentences(word,branches,c,table,label,english,xml,explanation=None):
  q='select * from %s where %s limit 7' % (table, get_regexes(word,branches))
  examples = query(q,c)
  if examples:
    xml.write( '<span class="column">\n')
    xml.write( '%s\n' % label )
    for example in examples:
      if explanation:
        explanation_text = '[%s]' % example[explanation]
      else:
        explanation_text = ''
      xml.write( '<div class="entry"><i>%s</i> %s %s</div>\n' % ( example['palauan'], example[english], explanation_text ) )
    xml.write( '</span>\n' )

def add_synonyms(row,c,xml):
  q='select word as b from synonyms where grouping in (select grouping from synonyms where word=%d) and word !=%d' % (row['id'],row['id'])
  add_extras(q,c,'SYNONYMS',xml)

def add_links(row,c,xml):
  q='select b from cf where a=%d union all select a from cf where b=%d' % (row['id'],row['id'])
  add_extras(q,c,'SEE ALSO',xml)

def add_extras(q,c,label,xml):
  extras = query(q,c)
  if extras:
    xml.write( '\t<div class="extra" d:priority="0"><h1 style="display: inline">%s: </h1>' % label )
    for extra in extras:
      xml.write( '%s ' % get_pal(extra['b'],c) )
    xml.write( '</div>\n' )

def origin_to_string(o):
  return {
    'J': 'Japanese',
    'T': 'Tagalog',
    'E': 'English',
    'S': 'Spanish',
    'M': 'Malay',
    'G': 'German',
  }.get(o[0],o)

def show_borrowing(row,c,xml):
  # borrowed marker
  if row['origin']:
    origin="from %s" % origin_to_string(row['origin'])
    if row['oword']:
      origin += " %s" % row['oword']
    xml.write( '\t<div class="borrow" d:priority="0">%s</div>\n' % origin )

def print_word(row,c,xml,compressed):
  # get the branch words
  branches = query('select id,pal,eng,pdef,pos from all_words3 where stem=%d and stem!=id order by pal' % row['id'], c)

  # main header for each word
  xml.write( '<d:entry id="%s_%d" style="display: inline" d:title="%s">\n' % ( row['pal'], row['id'], row['pal'] ) )

  # index entries for the word and its branches
  xml.write( '\t<d:index d:value="%s"/>\n' % row['pal'] )
  for branch in branches:
    xml.write( '\t<d:index d:value="%s"/>\n' % branch['pal'] )

  # show a header 
  xml.write( '\t<div d:priority="0"><h1>%s</h1></div>\n' % row['pal'] )

  # show any borrowing
  show_borrowing(row,c,xml)

  # now show the word
  display_word(row,'b',xml)

  # now add the branches
  add_branch_words(branches,xml)

  # now add extras 
  add_links(row,c,xml)
  add_synonyms(row,c,xml)
  add_examples(row,branches,c,xml)
  add_uploads(row,branches,c,xml)
  add_proverbs(row,branches,c,xml)

  # now add the picture
  if not compressed:
    add_image(row['id'],row['pal'],xml)

  # end the word
  xml.write( '</d:entry>\n\n' )


def main():
  (db,c) = belau.connect()

  parser = argparse.ArgumentParser(description='Convert database into XML suitable to convert into Mac Dict with Dictionary Development Kit.')
  parser.add_argument('-l', type=int, help='Only do a few words.') 
  parser.add_argument('-f', type=str, help='Only do a specific word.')
  parser.add_argument('-z', action='store_true', default=False, help='Compressed mode without pictures')
  args = parser.parse_args()

  if args.z:
    xml = open('Palauan_lite.xml', 'w')
  else:
    xml = open('Palauan.xml', 'w')

  print_header(xml)

  q = "select id,pal,eng,pdef,pos,oword,origin from all_words3 where id=stem and (isnull(pos) or pos not like 'affix')"; 
  if args.f:
    q += " and pal like '%s' " % args.f
  if args.l:
    q += " order by rand() limit %d " % args.l
  print query
  words = query(q,c)
  for idx,row in enumerate(words):
    print '%5d/%d %s' % (idx, len(words), row['pal'])
    print_word(row,c,xml,args.z)

  print_footer(xml)

  xml.close()
  db.commit()
  c.close()
  db.close()

if __name__ == "__main__": main()


