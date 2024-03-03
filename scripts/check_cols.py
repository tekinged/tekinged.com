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

def main():
  (db,c) = belau.connect()

  for i in range(518):
    for side in ('left', 'right'):
      col = "page-%03d_%s.png.txt" % (i,side)
      check_col(c,col)

  # commit and close
  db.commit()
  c.close()
  db.close()
    
if __name__ == "__main__": main()


