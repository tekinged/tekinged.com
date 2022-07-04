#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os

sys.path.append('/Users/jbent/Dropbox/belau/scripts')
sys.path.append('../scripts')
import belau

field = "yearweek(added)"  # week at a time
#field = "concat(year(added),'.',lpad(dayofyear(added),3,'0'))" # day at a time

def get_count(c,val,date):
    q = "select count(*) as count from log_query where found=%s and %s = '%s'" % (val,field, date)
    print "# %s" % q
    c.execute(q)
    for row in c.fetchall():
      return row['count']

def main():
  (db,c) = belau.connect()

  q = "select distinct(%s) as date from log_query order by %s" % (field,field)
  print "# %s" % q
  c.execute(q)
  idx = 0
  for row in c.fetchall():
    idx = idx + 1
    hits = get_count(c,1,row['date'])
    miss = get_count(c,0,row['date'])
    tot  = hits + miss
    try:
      print "%d %s TOTAL %s PERC %.2f" % ( idx, row['date'], tot, float(hits) / tot * 100 )
    except ZeroDivisionError:
      pass  # no queries on this day maybe c.close() db.close() if __name__ == "__main__": main() 

  c.close()
  db.close()


if __name__ == "__main__": main()

