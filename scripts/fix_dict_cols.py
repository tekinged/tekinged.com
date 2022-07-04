#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os
import belau

def main():
  (db,c) = belau.connect()

  difference=57
  c.execute("""select Col,page from dict_columns"""); 
  for row in c.fetchall():
    numbers = re.findall(r'\d+',row['Col'])
    if len(numbers) != 1:
      print "WTF: %s -> %s" % (row['Col'], numbers)
      sys.exit(0)
    print "Col %s (%d) is page %s" % (row['Col'], int(numbers[0]), row['page'])
    expected_page = int(numbers[0]) - 57
    if (row['page'] is not None ):
      if (int(row['page']) != expected_page):
        print "WTF: %d != %d" % (int(row['page']),expected_page)
        sys.exit(0)
    else:
      u = "update dict_columns set page=%d where Col like '%s'" % (expected_page,row['Col'])
      print u
      c.execute(u)

  #values= "('" + "'),('".join(sorted(distinct)) + "')"
  #c.execute("""drop table if exists `eng_list`""");
  #c.execute("""create table `eng_list` ( `eng` varchar(32) NOT NULL DEFAULT '' )""");
  #q = "insert into `eng_list` (`eng`) values %s" % values
  #print q

  #c.execute(q)
  db.commit()
  c.close()
  db.close()
  sys.exit(0)

if __name__ == "__main__": main()


