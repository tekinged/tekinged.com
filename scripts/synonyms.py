#! /usr/bin/env python

import sys
import pymysql
import itertools
import argparse
from _mysql_exceptions import IntegrityError

sys.path.append('/Users/bentj/Dropbox/belau/scripts')
sys.path.append('../scripts')
import belau

args = None
table = 'synonyms'

def get_next_group(c):
  q = "select max(grouping) as g from %s" % table
  c.execute(q)
  group = c.fetchone()['g'] + 1
  return group

def find_existing_group(c,i):
  q = "select grouping from %s where word=%d" % (table,i)
  c.execute(q)
  rows = c.fetchall()
  assert(len(rows)==0 or len(rows)==1)
  return None if len(rows)==0 else rows[0]['grouping']

def add_syns(c,syns,args):
  group = None

  # get the id's of each word before inserting any into the DB.  That way if there's a problem we won't create a new group
  ids = []
  for s in syns:
    i = belau.pal2id(c,s,args.p)
    if i is None:
      print "No id found for %s from %s." % (s,syns)
      assert 0
    ids.append(i)
    if group is None:
      group = find_existing_group(c,i)

  # use existing group or make a new one
  if group is None:
    group = get_next_group(c)
    print "Making new group %d" % group
  else:
    print "Using existing group %d" % group

  if args.p:
    ans = raw_input("Proceed to enter this group into %s? " % table) 
    if ans == 'n':
      print "Will not enter."
      return

  for i in ids:
    update = """insert into %s(grouping,word) values (%s,%s)""" % (table,group,i)
    print update
    try:
      c.execute(update)
    except IntegrityError:
      pass # if we're updating an existing group, then duplicate inserts will fail (correctly)

def main():
  parser = argparse.ArgumentParser(description='Enter some synonyms [or confused words].')
  parser.add_argument('synonyms', metavar='S', type=str, nargs='+', help='List of Synonyms')
  parser.add_argument('-c', action='store_true', default=False, help='Add to confusion list instead.') 
  parser.add_argument('-p', action='store_true', default=False, help='Prompt before actually adding.')
  parser.add_argument('-v', action='store_true', default=False, help='Verbose.')
  args = parser.parse_args()

  if len(args.synonyms)<2:
    print "Must specify at least two words."
    sys.exit(0)

  if args.c:
    global table
    table = 'confusion' 

  (db,c)=belau.connect()

  words = args.synonyms 
  add_syns(c,set(words),args)

  # commit and close
  db.commit()
  c.close()
  db.close()
    
if __name__ == "__main__": main()
