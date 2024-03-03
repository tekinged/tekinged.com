#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os

sys.path.append('/Users/jbent/Dropbox/belau/scripts')
sys.path.append('../scripts')
import belau

def collect(log,c,results):
  where="where added >= '2014-10-15'" # for some reason, we have 3 more weeks of log_query than log_visitor.  This homogenizes them.
  where="where added >= '2014-11-21'" # we have 7 more weeks of log_query than log_quiz.  This homogenizes them.
  if log == 'log_visit':
    where += "and page not like '404.php' and ip not in (select ip from log_filter)"
  elif log == 'log_quiz':
    where += "and length(query)>0"

  q = "select yearweek(added),count(distinct(ip)) as visitors from %s %s group by yearweek(added)" % (log, where)
  print "# ", q
  c.execute(q)
  for idx, row in enumerate(c.fetchall()):
    while True:
      try:
        results[idx].append(row['visitors'])
        break
      except KeyError:
        results[idx]=[]

def val_or_zero(a,i):
  try:
    return a[i]
  except IndexError:
    return 0

def main():
  (db,c) = belau.connect()


  results = {}
  collect('log_visit',c,results)
  collect('log_query',c,results)
  collect('log_quiz',c,results)

  for k,v in results.iteritems():
    print k, val_or_zero(v,0), val_or_zero(v,1), val_or_zero(v,2)

  c.close()
  db.close()

if __name__ == "__main__": main()


