#! /usr/bin/env python3

import sys
import itertools

import belau

def add_link(c,a,b):

  stems = []
  linkable = True

  for word in ( a , b ):
    if word.isdigit():  # might already be the ids
      stems.append(word)
      continue
    c.execute("""select id,pos,eng,stem from all_words3 where pal like '%s' and id=stem""" % word)
    rows = c.fetchall()
    if (len(rows)!=1):
      print( "%s has %d matches" % (word, len(rows)))
      linkable=False 
      for row in rows:
        print( "\t", word, row )
    else:
      row = rows[0]
      if (row['id'] != row['stem']):
        linkable=False
        print ("Sorry. Not linkable: %s is not a root word (%s != %s)" % (word, row['id'], row['stem']))
      else:
        stems.append(row['id'])

  if (linkable == False):
    print ("Sorry.  Not linkable.")
    sys.exit(0)

  (lower,higher)=sorted((stems[0],stems[1]))
  print ("Need to link %s and %s" % ( lower, higher ) )
  update = """insert into cf (a,b) values (%s,%s)""" % (lower,higher)
  try:
    #print update
    c.execute(update)
    if (c.rowcount):
      print (update)
  except: 
    print ("Insert error: Duplicate?" )

def main():
  try:
    a = sys.argv[1]
    b = sys.argv[2] 
  except IndexError:
    print ("Usage: %s word1 word2" % sys.argv[0])
    return 

  (db,c) = belau.connect()
  #c=db.cursor()


  words = sys.argv[1:]
  print (words)
  for pair in itertools.combinations(words,2):
    add_link(c,*pair)

  # commit and close
  db.commit()
  c.close()
  db.close()
    
if __name__ == "__main__": main()


