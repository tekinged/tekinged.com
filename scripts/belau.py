#! /usr/bin/env python3

import os
import sys
#from sshtunnel import SSHTunnelForwarder
import MySQLdb
import MySQLdb.cursors

def pal2id(c,pal,verbose=False):
  def show_word(idx,row):
    print ("\t%d: %s %s [%s] [id %d, root %d]" % (idx,row['pos'],row['eng'],row['pdef'],row['id'],row['stem']))

  c.execute("select id,pos,eng,stem,pdef from all_words3 where pal like '%s'" % pal)
  rows = c.fetchall()
  print ("%s has %d matches" % (pal, len(rows)))
  if (len(rows)>1):
    for idx, row in enumerate(rows):
      show_word(idx,row)
    answer = raw_input("\tWhich word to link?")
    row = rows[int(answer)]
    print ("Using %s" % row['eng'])
    return row['id']
  elif (len(rows)==0):
    return None
  else:
    if verbose:
      show_word(0,rows[0])
    return rows[0]['id']

def print_from_row(row,indent=''):
  print ("%s%06d/%06d: %-15s %5s %s" % (indent, row['stem'], row['id'], row['pal'].upper(), row['pos'], row['eng']))
  if (row['pdef']):
    print ("%s\t%s" % (indent,row['pdef']))

def print_from_id(c,wid,indent=''):
  q = "select * from all_words3 where id=%d order by pal" % wid
  c.execute(q)
  assert(c.rowcount()==1) 
  word = c.fetchone()
  print_from_row(c.fetchone(),indent)

def connect():
  #print "Connecting via tunnel"
  pword=os.getenv('TEK_PWD')
  puser=os.getenv('TEK_USR')
  try:
    db=MySQLdb.connect(user=puser,passwd=pword,db="belau",host="127.0.0.1",port=3307,cursorclass=MySQLdb.cursors.DictCursor)
    c=db.cursor()
    return (db,c)
  except Exception as e:
    print("Couldn't connect. Is tunnel set up?")
    print(e)
    raise(e)
    sys.exit(-1)

def insert(c,db,pal,pdef=None,edef=None,prompt=True,tag=None,pos=None):
  query = "insert into all_words3 (pal,pdef,eng,tags,pos) values (%s,%s,%s,%s,%s)"
  values = (pal,pdef,edef,tag,pos)
  try:
    print ("Execute %s %s?" % (query, values))
    if (prompt):
      var = raw_input("y|n? ")
    else:
      var = 'y'
    if (var != 'n'):
      c.execute(query,values)
      rid=c.lastrowid
      query = "update all_words3 set stem=id where id=%s"
      c.execute(query,(rid,))
      db.commit()
      return rid
    else:
      return 0
  except: 
    e = sys.exc_info()[0]
    print ("Error: %s" % e)
    return -1

def print_rows(rows,word,wordtwo):
  for row in rows:
    print ("%s/%s is already defined as %s" % (word,wordtwo,row[2]))

def search(c,word):
  c.execute("""select id,eng,pdef,stem,id,tags,pos from all_words3 where pal like '%s'""" % (word))
  rows = c.fetchall()
  return rows
