#! /usr/bin/env python
# -*- coding: utf-8 -*-

import belau
import sys
import pymysql
from warnings import warn

class Word(object):
  def __init__(self, pword):
    self.pword = pword
    self.pdef = ''

  def add_def(self,pdef):
    if len(self.pdef) > 0:
      self.pdef += " "
    self.pdef += pdef 
  
  def get_def(self):
    return self.pdef

  def get_word(self):
    return self.pword

  def toString(self):
    return "%s: %s" % (self.pword,self.pdef)

def check_alpha(word,col):
  correct = True
  if (word.lower().strip() < check_alpha.prev.lower().strip()):
    mywarn("Bad alpha in %s: %s < %s should not be true." % (col,word, check_alpha.prev))
    correct = False
  check_alpha.prev = word
  return correct
check_alpha.prev = '0'

def mywarn(msg):
  mywarn.warnings += 1
  sys.stderr.write("WARN %d: %s\n" % (mywarn.warnings, msg))
mywarn.warnings = 0

# according to the review by Gibson, should be 13791 words
def get_words(c):
  expected = 13791
  blob = get_blob(c)

  words = []

  previous_word='0'
  current = None
  for line in blob.splitlines():
    if (main_word_entry(line)):
      if current is not None:
        words.append(current)
      current = Word(line.replace(':',''))
      if line.lower().strip() < previous_word.lower().strip():
        mywarn("Bad alpha: %s and %s are out of order." % (previous_word, line))
      previous_word = line
    else:
      current.add_def(line)

  words.append(current)

  print "Word Count: %d. According to Gibson, should be %d" % (len(words),expected)
  return words

def main_word_entry(line,silent=True):
  if not silent:
    #mywarn("Check %s for main_word_entry" % line)
    pass
  if ':' in line:
    subwords = line.split()
    if len(subwords) > 1:
      if not silent:
        mywarn( "Not single word entry: %s" % line )
      return False
    else:
      return True
  else:
    return False

def get_blob(c):
  bad_cols = {}
  q = "select kcol,ctext_copy as txt from kerresel_columns"
  c.execute(q)
  rows = c.fetchall()
  texts = []
  for row in rows:
    col=row['kcol']
    txt=row['txt']
    if not 'Hereng' in txt:
      txt.replace('H','ll')
    #print "Received column %s" % col 
    texts.append(txt)
    if 'â' in txt:
      bad_cols[col] = 1
      mywarn( "Bad character in %s" % col ) 
    for line in txt.splitlines():
      if ':' in line and not main_word_entry(line,True) and line.strip() != 'omeu a osul:':
        bad_cols[col] = 1
        mywarn( "Bad main word in %s: %s" % (col,line) )
      if ':' in line:
        if not check_alpha(line,col):
          bad_cols[col] = 1

  for key in bad_cols:
    update = "update kerresel_columns set assigned=NULL where kcol like '%s';" % key
    print update 
    c.execute(update)
  

  blob = "\n".join(texts)
  print "Got all the text: %d bytes" % len(blob)
  print "%d bad columns" % len(bad_cols)
  #print blob
  return blob

def find_dups(words):
  prev = '0'
  for word in words:
    pdef  = word.get_def()
    pword = word.get_word()
    try:
      first_word = pdef.split()[0]
    except:
      mywarn("Problem splitting %s : %s" % (pword,pdef))
      #sys.exit(0)
    cur = word.toString()
    if cur == prev:
      print "Duplicate: %s" % word.toString()
      sys.exit(0)
    prev = cur

def find_longest(words):
  longest=0
  for word in words:
    longest = len(word.get_def())
  return longest

def insert_words(c,db,words):
  for word in words:
    u = """insert into kerresel_words (tekoi,belkul) values (%s,%s)"""
    c.execute(u,(word.get_word(),word.get_def(),))
  db.commit()

def check_words(words):
  for word in words:
    pal = word.get_word()
    pde = word.get_def()
    pieces = pde.split()
    if pal.strip() != pieces[0].strip():
      mywarn("Mismatch?\n\t%s\n\t%s" % (pal,pieces[0]))

def main():
  (db,c) = belau.connect()

  words = get_words(c)

  check_words(words)

  #find_dups(words)
  #longest = find_longest(words)
  #insert_words(c,db,words)

  # commit and close
  db.commit()
  c.close()
  db.close()
    
if __name__ == "__main__": main()


