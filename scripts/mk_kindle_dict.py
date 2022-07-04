#! /usr/bin/env python3

# https://jakemccrary.com/blog/2020/11/11/creating-a-custom-kindle-dictionary/
# https://kdp.amazon.com/en_US/help/topic/G2HXJS944GL88DNV
# https://github.com/andyljones/kindlegen-64

import argparse
import sys
import pymysql
import string
import re
import os
import belau
import shutil

def shared_html_front():
  return '''
  <html>
  <head>
    <meta content="text/html" http-equiv="content-type">
  </head>
  <body>
    <h1>%s</h1>
    <h3>Created by %s</h3>
  ''' % ('Palauan Dictionary', 'tekinged.com')
  
def make_html(filename,body):
  fp = open(filename,'w+')
  contents='''
  %s
  %s
  </body>
  </html>
  ''' % (shared_html_front(), body)
  fp.write(contents)
  fp.close()

def make_cover(COVER):
  make_html(COVER,'')

def make_usage(USAGE):
  usage_text='''
    <br>
    <p>
    Using this dictionary should work like any other kindle dictionary. Please refer to tekinged.com for most up-to-date information.
  '''
  make_html(USAGE,usage_text)

def make_copyright(COPYRIGHT):
  copy_text='''
    <br>
    <h4>COPYRIGHT: This dictionary is freely provided and can be freely redistributed for noncommercial purposes.</h4>
    <h4>Any and all redistributions for commercial purposes are prohibited.</h4>
    <p>The latest version of this dictionary can be found at tekinged.com. Please email info@tekinged.com with any questions or comments.</p>
  '''
  make_html(COPYRIGHT,copy_text)

def start_wordlist(fp):
  header='''
  <html xmlns:math="http://exslt.org/math" xmlns:svg="http://www.w3.org/2000/svg"
      xmlns:tl="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
      xmlns:saxon="http://saxon.sf.net/" xmlns:xs="http://www.w3.org/2001/XMLSchema"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xmlns:cx="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:mbp="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
      xmlns:mmc="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf"
      xmlns:idx="https://kindlegen.s3.amazonaws.com/AmazonKindlePublishingGuidelines.pdf">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
      h5 {
          font-size: 1em;
          margin: 0;
      }
      dt {
          font-weight: bold;
      }
      dd {
          margin: 0;
          padding: 0 0 0.5em 0;
          display: block
      }
    </style>
  </head>
  <body>
    <mbp:frameset>
  '''
  fp.write(header)

def end_wordlist(fp):
  footer='''
      </mbp:frameset>
    </body>
  </html>
  '''
  fp.write(footer)
  fp.close()

def make_opf(OPF,COVER,COPYRIGHT,USAGE,CONTENT):
  pal_iso='pw' # ugh, pw is not supported https://www.mobileread.com/forums/showthread.php?t=208217&
  pal_iso='en-us'
  opf_text='''
  <?xml version="1.0"?>
  <package version="2.0" xmlns="http://www.idpf.org/2007/opf" unique-identifier="BookId">
  <metadata>
    <dc:title>A Palauan dictionary created by tekinged.com V1</dc:title>
    <dc:creator opf:role="aut">tekinged.com</dc:creator>
    <dc:language>%s</dc:language>
    <meta name="cover" content="my-cover-image" />
    <x-metadata>
      <DictionaryInLanguage>%s</DictionaryInLanguage>
      <DictionaryOutLanguage>en-us</DictionaryOutLanguage>
      <DefaultLookupIndex>default</DefaultLookupIndex>
    </x-metadata>
  </metadata>
  <manifest>
    <!-- <item href="cover-image.jpg" id="my-cover-image" media-type="image/jpg" /> -->
    <item id="cover"
          href="%s"
          media-type="application/xhtml+xml" />
    <item id="usage"
          href="%s"
          media-type="application/xhtml+xml" />
    <item id="copyright"
          href="%s"
          media-type="application/xhtml+xml" />
    <item id="content"
          href="%s"
          media-type="application/xhtml+xml" />
  </manifest>
  <spine>
    <itemref idref="cover" />
    <itemref idref="usage" />
    <itemref idref="copyright"/>
    <itemref idref="content"/>
  </spine>
  <guide>
    <reference type="index" title="IndexName" href="%s"/>
  </guide>
  </package>
  ''' % (pal_iso,pal_iso,COVER,USAGE,COPYRIGHT,CONTENT,CONTENT)
  o = open(OPF,'w+')
  o.write(opf_text)
  o.close()

def setup(DIR,COVER,COPYRIGHT,USAGE,OPF,CONTENT):
  try:
    os.mkdir(DIR)
  except FileExistsError:
    pass
  os.chdir(DIR)

  make_cover(COVER)
  make_copyright(COPYRIGHT)
  make_usage(USAGE)
  make_opf(OPF,COVER,COPYRIGHT,USAGE,CONTENT)

def add_word(fp, row,c):
  print("Processing %s" % row['pal'])

  # <idx:iform name="plural" value="records" />

  # start the word
  word = '''
  <idx:entry name="default" scriptable="yes" spell="yes">
    <h5><dt><idx:orth>%s</idx:orth></dt></h5>
    <dd>%s: %s</dd>
  ''' % (row['pal'], row['pos'], row['eng'] if row['eng'] else '')
  fp.write(word)


  # add any pdef; note that italics don't seem to work...
  if row['pdef']:
    fp.write('''
      <br>
      <dd><i>%s</i></dd>
      ''' % (row['pdef']))


  # now collect all words belonging to this stem
  query = "select pal,eng,pos,pdef from all_words3 where stem=%d and id != %d" % (row['stem'],row['stem'])
  c.execute(query)
  variants=''
  parts=''
  for row in c.fetchall():
    variants += ('<idx:iform name="%s" value="%s" />\n' % (row['pal'], row['pos']))
    parts += ('<br><dd> - %s %s: %s' % (row['pal'],row['pos'],row['eng'] if row['eng'] else ''))
    if row['pdef']:
      parts += ('<br> -- <i>%s</i>' % row['pdef'])

  # add each part of speech into the base definition
  fp.write(parts)

  # add the pos of the main word, not sure what that does if anything....
  fp.write('<idx:infl inflgrp="%s">' % row['pos'])

  # now add all the variants which should also pull up this entry
  fp.write(variants)

  # end the word
  fp.write('''
  </idx:entry>
  <hr/>
  ''')

def add_words(fp,args):
  (db,c) = belau.connect()


  query = "select pal,eng,pos,pdef,stem from all_words3 where stem=id and pos != 'affix'"
  if (args.limit):
    query += " order by rand()"
  else:
    query += " order by pal"
  c.execute(query)
  for i, row in enumerate(c.fetchall()):
    add_word(fp,row,c)
    if (args.limit and i >= args.limit):
      print("Prematurely stopping due to specified limit of %d" % args.limit)
      break

def generate_mobi(OPF):
  gen='kindlegen'
  if shutil.which(gen) is None:
    print("Executable %s is not installed. Cannot convert to mobi" % gen)
  else:
    output = os.system("%s %s" % (gen,OPF))
    print("Attempted to generate mobi file: %s" % output)

def main():

  parser = argparse.ArgumentParser(description='Pull words from database and convert into a kindle custom dictionary.')
  parser.add_argument('-l', '--limit', default=None, type=int, help='Only do a limited number of words. Useful for testing.') 
  parser.add_argument('-v', action='store_true', default=False, help='Verbose.')
  args = parser.parse_args()


  DIR='kindle_dict'
  WORDS='content.html'
  COVER='cover.html'
  COPYRIGHT='copyright.html'
  USAGE='usage.html'
  OPF='belau.opf'
  setup(DIR,COVER,COPYRIGHT,USAGE,OPF,WORDS)

  words = open(WORDS,"w+")
  start_wordlist(words) 
  add_words(words,args)
  end_wordlist(words)

  generate_mobi(OPF)

  print("All done")

  (db,c) = belau.connect()
  #db.commit() # not necessary, no changes should have been made
  c.close()
  db.close()

if __name__ == "__main__": main()


