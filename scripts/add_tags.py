#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os
import belau

def print_def(definition):
  try:
    if len(definition) > 0:
      print "\t%s" % definition 
  except TypeError:
    pass

def main():
  (db,c) = belau.connect()

  w = raw_input("Enter your mysql filter: ")
  t = raw_input("Enter your desired tag: ")
  q = "select id,pal,eng,pos,pdef from all_words3 where (isnull(tags) or length(tags)<0) and (%s) order by pal" % w
  print q

  c.execute(q)
  for row in c.fetchall():
    print "%s %s" % (row['pal'],row['pos'])
    print_def(row['eng'])
    print_def(row['pdef'])
    u = raw_input("Add tag %s? [y|n]" % t)
    if (u == 'y'):
      u = "update all_words3 set tags='%s' where id='%d'" % (t,row['id'])
      c.execute(u)


  db.commit()
  c.close()
  db.close()

if __name__ == "__main__": main()


