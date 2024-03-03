#! /usr/bin/env python

import sys
import pymysql
import string
import re

def main():
  try:
    password = sys.argv[1];
  except IndexError:
    print "Usage: %s password" % sys.argv[0]
    return 
  db=pymysql.connect(user="johnbent",passwd=password,db="belau",host="mysql.tekinged.com")
  c=db.cursor()
  d=db.cursor()

  total = c.execute("""select first from page_markers where id <349"""); 
  hits = 0
  for row in c.fetchall():
    q = "select pal from all_words3 where pal like '%s'" % row[0]
    print q
    hits += d.execute(q)
  
  print "%d / %d (%.2f) complete" % (hits,total,(1.0*hits)/total)

  # close 
  c.close()
  d.close()
  db.close()

if __name__ == "__main__": main()


