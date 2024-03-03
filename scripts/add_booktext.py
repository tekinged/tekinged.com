#! /usr/bin/env python

from __future__ import unicode_literals

import nltk.data
import sys
import pymysql
import itertools
import argparse
import spacy
from _mysql_exceptions import IntegrityError


sys.path.append('/Users/bentj/Dropbox/belau/scripts')
sys.path.append('../scripts')
import belau

def main():
  parser = argparse.ArgumentParser()
  parser.add_argument('file', type=str, help='The file containing the book text')
  parser.add_argument('book', type=str, help="Title of Book")
  parser.add_argument('-p', action='store_true', default=False, help='Prompt before actually adding.')
  args = parser.parse_args()

  (db,c)=belau.connect()

  fp = open(args.file)
  data = fp.read().decode('utf-8')
  print data
  # try with spacy
  #nlp = spacy.load('en')
  #sentences = nlp(data)
  tokenizer = nltk.data.load('tokenizers/punkt/english.pickle')
  sentences = tokenizer.tokenize(data)

  for sentence in sentences:
    sentence = sentence.replace('\n', ' ').replace('\r', '').replace(' +', ' ')
    print args.book, sentence
    if args.p:
      ans = raw_input("Insert? [y|n]")
      if ans == 'n':
        print "Will not enter."
        return
    insert = """insert into book_text(book,pal) values (%s,%s)""" 
    print insert
    c.execute(insert,(args.book,sentence))
     

  # commit and close
  db.commit()
  c.close()
  db.close()
    
if __name__ == "__main__": main()
