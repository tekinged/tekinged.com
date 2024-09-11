#! /usr/bin/env python

import sys
import pymysql
from num2words import num2words
import re

def is_member(noun,members):
  for i in members:
    if i == noun:
      return True
  return False

def main():
  try:
    password = sys.argv[1];
  except IndexError:
    print "Usage: %s password" % sys.argv[0]
    return 
  db=pymysql.connect(user="johnbent",passwd=password,db="belau",host="mysql.tekinged.com")
  c=db.cursor()

  p = re.compile('(^\d+)\s+(.*)$')

  # for every number
  c.execute("""select eng,id from all_words3 where pos like 'num.'""")
  for row in c.fetchall():
    match = p.match(row[0])
    rid = row[1]
    ordinal='ordinal' in match.group(2)
    numstr=num2words(int(match.group(1)),ordinal=ordinal)
    neweng="%s [%s] %s" % (match.group(1),numstr,match.group(2))
    query="update all_words3 set eng=%s where id=%s"
    print "%s <- %s (%s)" % (query,neweng,rid)
    c.execute(query,(neweng,rid,))

    #for field,value in zip(schema,forms):
    #  update="""update nouns set %s='%s' where 3ps like '%s' and (isnull(%s) or length(%s)=0)""" % (field,value,p3s,field,field);
    #  try:
        #print update
    #    c.execute(update)
    #    if (c.rowcount):
    #      print update
    #  except _mysql_exceptions.IntegrityError, e:
    #    print "Insert error: %s" % e

  # commit and close
  db.commit()
  c.close()
  db.close()

if __name__ == "__main__": main()


