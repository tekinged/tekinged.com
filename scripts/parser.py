#! /bin/env python

import codecs
import sys

from bs4 import BeautifulSoup

try:
  tag = sys.argv[1]
except:
  print "Usage: %s tag" % sys.argv[0]
  exit()

#soup = BeautifulSoup(codecs.open("all.html",encoding='utf-8'))
soup = BeautifulSoup(open("%s.html" % tag))

dts = soup.body.find_all('dt')
dds = soup.body.find_all('dd')

UTF8Writer = codecs.getwriter('utf8')
sys.stdout = UTF8Writer(sys.stdout)
sys.stderr = UTF8Writer(sys.stderr)

for i in range (len(dds)):
  palauan = dts[i].contents[0].rstrip()
  english = dds[i].contents[1].rstrip().replace(',',';').replace('--','')
  try:
    type = dds[i].contents[0].contents[0]
  except:
    type = ''
  if (len(palauan)==0 or len(english)==0):
    sys.stderr.write("EXCEPTION: %s -> %s\n" % ( palauan, english ))
  else:
    print "%s, %s, %s, native, %s" % (palauan,english,type,tag)
  #print "%s -> %s\n####\n" % (dts[i].rstrip(),dds[i].contents[1].rstrip())
