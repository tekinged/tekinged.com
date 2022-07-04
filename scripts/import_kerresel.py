#! /usr/bin/env python
# -*- coding: utf-8 -*-

import belau
import sys
import pymysql
from warnings import warn

def check_col(c,col):
  q = "select count(*) as c from kerresel_columns where kcol like '%s'" % col 
  c.execute(q)
  row = c.fetchone()
  if row['c'] != 1:
    print "%s is missing" % col

def get_allwords(c):
  words = set()
  q = "select distinct(pal) as pal from all_words3"
  c.execute(q)
  for row in c.fetchall():
    words.add(row['pal'].strip())
  return words

def insert(c,db,row):
  tekoi = row['tekoi'].strip()
  belkul = row['belkul'].strip()
  print "Inserting %s" % tekoi 
  belau.insert(c,db,tekoi,belkul,None,False)
  u = "update kerresel_words set imported=1 where id=%d" % row['id']
  c.execute(u)

def main():
  (db,c) = belau.connect()

  words = get_allwords(c)
  print "%d distinct palauan words in DB" % len(words) 

  # now check the kerresel words
  q = "select id,tekoi,belkul from kerresel_words where imported=0"
  c.execute(q)
  for row in c.fetchall():
    if row['tekoi'].strip() not in words:
      insert(c,db,row)

  # commit and close
  db.commit()
  c.close()
  db.close()
    
if __name__ == "__main__": main()


