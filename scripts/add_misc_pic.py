#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os
import belau

def main():
  (db,c) = belau.connect()

  if len(sys.argv) != 2:
    print "Usage: %s id" % sys.argv[0]
    sys.exit(1)

  q = "insert into pictures (allwid,tag,pal) (select id,'misc',pal from all_words3 where id=%s and id not in (select allwid from pictures));" % sys.argv[1]
  print q 

  c.execute(q); 
  db.commit()
  c.close()
  db.close()
  sys.exit(0)

if __name__ == "__main__": main()


