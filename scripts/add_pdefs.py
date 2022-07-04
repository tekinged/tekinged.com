#! /usr/bin/env python

import belau
import sys
import pymysql

def main():
  (db,c) = belau.connect()

  f = open('pdefs.txt')
  for line in f.readlines():
    if not line:
      continue
    entered = False
    pdef = line.rstrip()
    words = pdef.split(' ')
    print words
    try:
      pword = words[1]
      tag = words[3]
    except IndexError:
      continue
    if tag == 'suebek':
      tag = 'bird'
    #pword,pdef = line.rstrip().split(' -- ')
    #pword = pword.lower()
    #print "%s -> %s" % (pword, pdef)
    rows=belau.search(c,pword)
    if (len(rows)==0):
      print "\tWill insert %s -> %s" % (pword, pdef)
      belau.insert(c,db,pword,pdef,None,True,tag,'n.')
      entered = True
    else:
      for row in rows:
        print row
        if (pdef == row['pdef']):
          #print "%s -> %s is already entered." % (pword,pdef)
          entered = True
    if (entered == False):
      print "Please specify where to enter %s -> %s" % (pword,pdef)
      idx = 0
      for idx, row in enumerate(rows):
          print "\t%d: Add to %s %s [%s]" % (idx,pword,row['eng'],row['pdef'])
      nword = 'N' 
      skip  = 'S' 
      quit  = 'Q'
      print "\t%s: As a new word" % nword 
      print "\t%s: Do not enter right now" % skip 
      answer = raw_input("Enter your choice: ")
      if (answer.lower() == nword.lower()):
        print "\tWill insert %s -> %s" % (pword, pdef)
        belau.insert(c,db,pword,pdef,None,True,tag,'n.')
      elif (answer.lower() == quit.lower()):
        break
      elif (answer.lower() == skip.lower()):
        print "\tWill skip for now"
      elif (answer < nword):
        query = "update all_words3 set pdef=concat(coalesce(pdef,''),' ',%s),tags=%s where id=%s"
        values = (pdef,tag,rows[int(answer)]['id'],)
        print "\tWill query with %s %s" % (query, values)
        #raw_input("Continue")
        c.execute(query,values)
    db.commit()
    continue

  # commit and close
  db.commit()
  c.close()
  db.close()
    
if __name__ == "__main__": main()


