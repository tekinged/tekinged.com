#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os
import belau

def collect(db,c,query,field,table):
  distinct = set()

  print("Execute %s" % query)
  c.execute(query)
  for row in c.fetchall():
    clean = re.sub('e.g.',' ', row[field])
    clean = re.sub(' +',' ',clean)
    clean = re.sub('[%s]' % re.escape(string.punctuation), ' ', clean)
    words = clean.split(' ') 
    distinct.update(words)

  print("Fetched the %s words" % field)

  # somehow a blank word is added 
  distinct.remove('')

  values= "('" + "'),('".join(sorted(distinct)) + "')"
  c.execute("""drop table if exists `%s`""" % table);
  c.execute("""create table `%s` ( `%s` varchar(32) NOT NULL DEFAULT '' )""" % (table,field));
  q = "insert into `%s` (`%s`) values %s" % (table,field,values)
  print(q)
  c.execute(q)

def main():
  (db,c) = belau.connect()

  q = "select eng from all_words3 where length(eng) > 0 and pos not like 'var.' and pos not like 'cont.'""";
  collect(db,c,q,'eng','eng_list')

  q = "select pdef from all_words3 where length(pdef) > 0"
  collect(db,c,q,'pdef','pal_list')

  db.commit()
  c.close()
  db.close()

if __name__ == "__main__": main()


