#! /usr/bin/env python

import sys
import pymysql
import string
import re
import os
import belau
import fileinput


def main():
  (db,c) = belau.connect()


  for line in fileinput.input():
    (book,author) = line.split(' ')
    full = '%s-%s' % (book,author.rstrip()) 
    title = book.replace('_',' ')
    author = author.replace('_',' ').rstrip()
    pdf = full
    epub = '/books/epubs/%s.epub' % full
    print "%s by %s [%s]" % (title, author,full)
    q = "insert into books (title,author,pdf,html,ebook,category) values (%s,%s,%s,%s,%s,%s)"
    values = (title,author,pdf,'http://www.prel.org/PALM/Palauan/index.asp',epub,'childrens',)
    c.execute(q,values)

  #q = "insert into pictures (allwid,tag,pal) (select id,'misc',pal from all_words3 where id=%s and id not in (select allwid from pictures));" % sys.argv[1]
  #print q 

  #c.execute(q); 
  db.commit()
  c.close()
  db.close()
  sys.exit(0)

if __name__ == "__main__": main()


