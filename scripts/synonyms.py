#! /usr/bin/env python3

import sys
import itertools
import argparse

import belau

args = None
table = 'synonyms'

def get_next_group(c):
  q = "select max(mygrouping) as g from %s" % table
  print(q);
  c.execute(q)
  group = c.fetchone()['g'] + 1
  return group

def find_existing_group(c,i):
  q = "select mygrouping from %s where word=%d" % (table,i)
  print(q)
  c.execute(q)
  rows = c.fetchall()
  assert(len(rows)==0 or len(rows)==1)
  return None if len(rows)==0 else rows[0]['mygrouping']

def add_syns(c,syns,args):
  group = None

  # get the id's of each word before inserting any into the DB.  That way if there's a problem we won't create a new group
  ids = []
  for s in syns:
    i = belau.pal2id(c,s,args.v)
    #print("%s has id %s" % (s,i))
    if i is None:
      print("No id found for %s from %s." % (s,syns))
      assert 0
    ids.append(i)
    if group is None:
      group = find_existing_group(c,i)

  # use existing group or make a new one
  if group is None:
    print("Making new group " )
    group = get_next_group(c)
  else:
    print("Using existing group %d" % group)

  if args.p:
    ans = input("Proceed to enter this group into %s? " % table) 
    if ans == 'n':
      print("Will not enter.")
      return

  for i in ids:
    update = """insert into %s(mygrouping,word) values (%s,%s)""" % (table,group,i)
    print(update)
    try:
      c.execute(update)
    except pymsql.err.IntegrityError:
      pass # if we're updating an existing group, then duplicate inserts will fail (correctly)

def main():
  parser = argparse.ArgumentParser(description='Enter some synonyms [or confused words].')
  parser.add_argument('synonyms', metavar='S', type=str, nargs='+', help='List of Synonyms')
  parser.add_argument('-c', action='store_true', default=False, help='Add to confusion list instead.') 
  parser.add_argument('-p', action='store_true', default=False, help='Prompt before actually adding.')
  parser.add_argument('-v', action='store_true', default=False, help='Verbose.')
  args = parser.parse_args()

  if len(args.synonyms)<2:
    print("Must specify at least two words.")
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
