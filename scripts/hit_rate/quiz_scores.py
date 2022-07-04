#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os

sys.path.append('/Users/jbent/Dropbox/belau/scripts')
sys.path.append('../scripts')
import belau

def gnuvalue(results,key,qtype):
  try:
    return results[key][qtype]
  except:
    return '?'

def main():
  (db,c) = belau.connect()

  results = {} 
  counts = {}

  try:
    if sys.argv[1] == 'c':
      q = """select quiztype,yearweek(added) as week,count(*) as count,1 as perc           from log_quiz            group by quiztype,yearweek(added) order by quiztype,yearweek(added);"""
  except:
    q = """select quiztype,yearweek(added) as week,sum(correct)/sum(total) as perc, count(*) as count
          from log_quizzes_filtered 
          group by quiztype,yearweek(added) order by quiztype,yearweek(added)""" 
  print "# " , q
  #q = "select correct,total,unix_timestamp(added) as epoch,added from log_quizzes_filtered order by added;"
  c.execute(q)
  for i, row in enumerate(c.fetchall()):
    if row['week'] not in results:
      results[row['week']] = {}
      counts[row['week']] = {}
      counts[row['week']]['TOTAL'] = 0
    results[row['week']][row['quiztype']] = row['perc']
    counts[row['week']][row['quiztype']] = row['count']
    counts[row['week']]['TOTAL'] += row['count']
    print "# DEBUG: %d %.2f %s" % (i, row['perc'],row['quiztype'])

  which=results
  try:
    if sys.argv[1] == 'c':
      which=counts
  except:
    pass

  for i, key in enumerate(sorted(results)):
    #print "%d %d %s %s %s" % (i, int(key), gnuvalue(results,key,'Classic'), gnuvalue(results,key,'Pictures'), gnuvalue(results,key,'Audio'))
    print "%d %d %s %s %s %s %s %s %s %s %s %s %s" % (i, int(key), 
                                            gnuvalue(which,key,'Classic'), 
                                            gnuvalue(which,key,'Pictures'), 
                                            gnuvalue(which,key,'Audio'), 
                                            gnuvalue(which,key,'Parts of Speech'),
                                            gnuvalue(which,key,'Trivia'),
                                            gnuvalue(which,key,'Pronouns'),
                                            gnuvalue(which,key,'Proverbs'),
                                            gnuvalue(which,key,'Synonyms'),
                                            gnuvalue(which,key,'Living Things'),
                                            gnuvalue(which,key,'Reng Expression'),
                                            gnuvalue(which,key,'TOTAL'))
    #if row['total'] > 0:
    #  perc = float(row['correct']) / row['total']
    #  print "%.2f %d %s" % (perc,row['epoch'],row['added'])

  #print results

  c.close()
  db.close()

if __name__ == "__main__": main()


