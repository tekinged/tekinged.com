#! /usr/bin/env python
# -*- coding: utf-8 -*- 

from __future__ import unicode_literals
import re
import sys
import pymysql
import codecs
import MySQLdb
import itertools
import time
import os

sys.path.append('/Users/bentj/Dropbox/belau/scripts')
sys.path.append('../scripts')
import belau

total_updates=0

def insert_word(c,pword,pos,edef,stem=None,prompt=True):
  query = "insert into all_words3 (pal,pos,eng,stem) values (%s,%s,%s,%s)"
  values = (pword,pos,edef,stem)
  try:
    print( "\tExecute %s %s?" % (query, ", ".join(str(i) for i in values)))
    if (prompt):
      var = raw_input("\ty|n? ")
    else:
      var = 'y'
    if (var != 'n'):
      c.execute(query,values)
      rid=c.lastrowid
      if stem==None:
        query = "update all_words3 set stem=id where id=%s"
        c.execute(query,(rid,))
        global total_updates
        total_updates = total_updates + 1
      return rid
    else:
      return 0
  except: 
    e = sys.exc_info()[0]
    print("insert_word Error: %s" % e)
    return -1

def add_extra(c,wid,field,value):
  query = "update all_words3 set %s=%%s where id=%%s" % field
  values = (value,wid)
  update_db(c,query,values,False)

def get_extras(pieces):
  extras = []
  if pieces[2] == 'affix':
    # don't look for extras if the word is an affix
    return (extras,pieces)
  while (pieces[1][0] == '-'):
    extras.append(pieces[1:3])
    del pieces[1:3]
  return (extras,pieces) 

def add_extras(c,wid,extras):
  if len(extras)>0:
    flags = {'-o': 'origin', '-t': 'tags', '-j': 'josephs', '-b' : 'oword'}
    for extra in extras:
      print("\tSliced out %s" % extra)
      (flag,value) = (extra[0],extra[1])
      try:
        field=flags[flag]
      except:
        print("Not yet handling extra arg %s %s" % (flag,value))
        sys.exit(0)
      add_extra(c,wid,field,value)

def add_variant(c,var,pal):
  print("\tNeed to add %s as a variant of %s" % (var,pal))
  c.execute("""select id,pos,eng,stem,pdef from all_words3 where pal like '%s'""" % pal)
  rows = c.fetchall()
  if (len(rows)>1):
    print("%s has %d matches" % (pal, len(rows)))
    for idx, row in enumerate(rows):
      print("\t%d: %s %s [%s] [id %d, root %d]" % (idx,row['pos'],row['eng'],row['pdef'],row['id'],row['stem']))
    answer = raw_input("\tWhich word to use as root for variant? ")
    row = rows[int(answer)]
  elif (len(rows)==0):
    print("FATAL: %s has 0 matches." % pal)
    sys.exit(0)
  else:
    row = rows[0]

  return add_word(c,var,'var.',pal,row['stem'])

def root_word(pieces,c):
  print("Root word %s" % pieces)
  (extras,pieces) = get_extras(pieces)

  pword=pieces[1]
  pos=pieces[2]
  edef=' '.join(pieces[3:])

  if 'var.' in pos:
    (wid,bid)=add_variant(c,pword,edef)
  else:
    (wid,bid)=add_word(c,pword,pos,edef)

  add_extras(c,wid,extras)

  return wid

def add_proverb(c,pal,eng,exp,wid):
  query = "insert into proverbs (palauan,english,source,explanation,stem) values (%s,%s,%s,%s,%s)"
  values = (pal,eng,'Josephs',exp,wid)
  insert_example(c,query,values)

def add_example(c,pal,eng,wid):
  query = "insert into examples (palauan,english,source,stem) values (%s,%s,%s,%s)"
  values = (pal,eng,'Josephs',wid)
  insert_example(c,query,values)

def insert_example(c,query,values):
  try:
    update_db(c,query,values,False)
    global total_updates
    total_updates = total_updates + 1
  except MySQLdb.IntegrityError:
    print("Ignoring exception on insert.  Probably duplicate.")

def get_poses(c):
  try:
    if 'n.' in get_poses.pos:
      return get_poses.pos
  except AttributeError:
    get_poses.pos = [] 
    query="select distinct(pos) as pos from all_words3 where !isnull(pos)";
    c.execute(query)
    rows = c.fetchall()
    for row in rows:
      get_poses.pos.append(row['pos'])
    # put in a new one to make this script work
    get_poses.pos.append('v.caus.inch.')
    get_poses.pos.append('v.a.s.redup.')
    print(get_poses.pos)
    return get_poses(c)

def test_pos(c,pos):
  try:
    if pos not in test_pos.pos:
      ans = raw_input("%s is not a known pos. Continue? [y|n]: " % pos)
      if (ans == 'y'):
        test_pos.pos.append(pos)
        return True
      else:
        return False
  except AttributeError:
    test_pos.pos = get_poses(c)
    print(test_pos.pos)
    return test_pos(c,pos)

def edef_has_pos(c,edef):
  if edef is None:
    return None
  poses = get_poses(c)
  for pos in poses:
    if ' %s ' % pos in edef:
      return pos
  return None


def add_word(c,pword,pos,edef,root=None,ignore_dups=False):
  # first let's make sure the pos is valid
  if (test_pos(c,pos)==False):
    print("\t%s is unknown pos" % pos)
    sys.exit(0)

  # lets recurse if the edef has any parts of speech in it
  split = edef_has_pos(c,edef)
  if split is not None: 
    answer = raw_input("\tRecurse to add multiple pos %s [y|n]" % split)
    if answer == 'y':
      try:
        (pre,post)=edef.split(split)
      except:
        print("Couldn't split %s on %s" % (edef,split))
        sys.exit(0)
      (mainwid,branchwid) = add_word(c,pword,pos,pre,root,ignore_dups)
      if root is None:
        root = mainwid
      return add_word(c,pword,split,post,root,ignore_dups)

  # now figure out if the incoming palauan word has a var form or forms
  variants=[]
  variants=pword.split('/')
  for index, word in enumerate(variants):
    parens=re.match("(\w+)\((\w)\)(\w*)",word)
    if (parens):
      variants[index]=parens.group(1)+parens.group(3)
      variants.append(parens.group(1)+parens.group(2)+parens.group(3))

  pword=variants[0]
  del variants[0]

  # actually add the main word now
  (mainwid,branchwid) = really_add_word(c,pword,pos,edef,root,ignore_dups)

  # now add any variants
  for variant in variants:
    wid = root if root else mainwid
    really_add_word(c,variant,'var.',pword,wid,ignore_dups)

  # special case expressions with reng
  if (pos == 'expression' and 'rengul' in pword):
    print("Adding reng tag to %s : %d [group %d]" % (pword,branchwid,mainwid))
    add_extra(c,branchwid,'tags','reng')

  return (mainwid,branchwid)

def get_branch_count(c,wid):
  q= "select count(*) as c from all_words3 where stem=%s" % wid
  #print("Get branch count with %s" % q)
  c.execute(q)
  row = c.fetchone()
  return row['c']

def add_missing(c,pword,pos,edef,root,prompt):
  print("\tAdding missing word %s" % pword)
  if edef is not None and 'xx' in edef:
    print("\tDefinition is xx for a missing word. Fix.")
    sys.exit(0)

  if edef is not None and '/' in edef:
    modify = raw_input("\tReplace '/' in the definition with ' or '? [y|n]: ")
    if (modify == 'y'):
      edef = edef.replace('/',' or ')
      print("\tModified definition to be %s" % edef)

  wid = insert_word(c,pword,pos,edef,stem=root,prompt=False)
  if root==None:
    root = wid
  return (root,wid)

def linked_without_eng(row,pos,root):
  if (row['pos'] == pos and root == row['stem'] and (row['eng'] is None or len(row['eng']) < 1)): 
    print("Already in and linked but missing english definition.")
    return True
  return False

def already_in(c,pword,row,edef,pos,root):
  if ((edef is None or edef == row['eng']) and pos == row['pos']):
    nothing_to_do = False
    if not root:
      # this means that we were trying to add a root word that is already in.
      nothing_to_do = True
      if (row['id'] != row['stem']):
        print("\tSpecified %s as root but it isn't." % pword)
        branch_to_root(c,row['id'],row['stem'])
        row['stem'] = row['id']
    elif root == row['stem']: 
      # it's already in and already correctly linked
      nothing_to_do = True
    if nothing_to_do:
      # easy case.  Already in with this def and pos.
      print("\t%s already in with id %d stem %d." % (pword,row['id'],row['stem']))
      return True
  return False

def branch_to_root(c,branch,root):
  confirm = raw_input("\tThat word is not a root.  Should I make it so and link it back to its current root [y|n]: ")
  if (confirm == 'y'):
    print("\tNeed to make %d its own root and link it to %d" % (branch,root))
    query = "update all_words3 set stem=id where id=%d" % branch
    update_db(c,query,None,False)
    actually_link(c,branch,root)
  else:
    print("Sorry.  I don't know what to do now.  Bye!")
    sys.exit(0)

def use_existing_root(c,rows):
  if (len(rows)>1):
    roots = 0
    for idx, row in enumerate(rows):
      if (row['id']==row['stem']):
        roots = roots + 1
        best_match=idx
    if (roots==1):
      print("Only one possible root.  Using %d" % best_match)
      answer = best_match
    else:
      answer = raw_input("\tWhich word to use as root: ")
  else:
    answer = 0

  # if a branch word should become a root
  if (rows[int(answer)]['stem'] != rows[int(answer)]['id']):
    branch_to_root(c,rows[int(answer)]['id'],rows[int(answer)]['stem'])
    return (rows[int(answer)]['id'],rows[int(answer)]['id'])
  else:
    return (rows[int(answer)]['stem'],rows[int(answer)]['id'])


def show_options(nword,update,skip,instead,quit):
  print("\t%s: As a new word" % nword )
  print("\t%s: Update existing to link to this group" % update )
  print("\t%s: Do not enter right now" % skip )
  print("\t%s: Do not enter now but use this word as the root for the rest of the group." % instead)
  print("\t%s: Quit" % quit)

# this is the function that actually does an insert of a word into the db
# There are several possible ways this can work
# CASE 1:   The word is completely missing.  Easy.  Add it.
# CASE 2:   The word is already in exactly the way we are trying to add it.  Easy. Do nothing.
# CASE 3:   A different word with the same spelling is in the DB.  Easy.  Treat this like CASE 1.
# CASE 4:   The word is already in but needs updates.
# CASE 4.1: The word is not in the group.  Update its stem to point to the root.
# CASE 4.2: The word is already in with a Palauan definition but not an English definition.
# CASE 4.3: The word is already in but without an English definition.
# CASE 4.4: The word is already in but with a different English definition.
# CASE 4.5: Some combination of the above.
def really_add_word(c,pword,pos,edef,root=None,ignore_dups=False):

  # for multi-part "words" like 'a lsekum', they are entered with _'s.  Replace with spaces.
  pword = pword.replace('_',' ')

  # Look for whether this word (or a different word with same spelling) is already in the DB
  print("\tSearching for %s, %s, %s" % (pword,pos,edef))
  rows=belau.search(c,pword)

  # CASE 1: easy case.  The word not in at all.  Go ahead and add
  if (len(rows)==0):
    return add_missing(c,pword,pos,edef,root,False)
    
  # CASE 2:   check whether it is already entered and properly linked
  for row in rows:
    if already_in(c,pword,row,edef,pos,root):
      return (row['stem'],row['id'])

  # CASE 4.3:  already nicely linked but missing English
  for row in rows:
    if linked_without_eng(row,pos,root):
      query = "update all_words3 set eng=%s where id=%s"
      values = (edef,row['id'])
      update_db(c,query,values,False)
      return (row['stem'],row['id'])

  # If we get here, another word(s) with this same spelling is already in the DB.  
  # It could be CASE 3 that the other words are different words.
  # Or it could be that it is the right word.
  # If it is the right word, it might be missing English or it might not be linked right.
  # show the user all the existing words with the same spelling
  for idx, row in enumerate(rows):
    group_sz = get_branch_count(c,row['stem'])
    row['group_sz'] = group_sz
    try:
      print("\t%d: Add to %s %s [%s] [id %d, root %d, group size %d]" % (idx,row['pos'],row['eng'],row['pdef'],row['id'],row['stem'],group_sz))
    except TypeError:
      print("Caught type error")
      print("Probably %s [id %d] has a NULL stem." % (pword,row['id']))
      sys.exit(0)
  # Here are the prompts
  nword = 'N' 
  skip  = 'S' 
  update = 'U'
  instead = 'I'
  quit = 'Q'
  show_options(nword,update,skip,instead,quit)

  # special case instead: when the user specificies 'foo' or 'xx' for edef that means they want to add more words later to an existing root.
  # however, if the edef only contains xx then there is a problem
  if (edef == 'foo' or edef == 'xx'):
    if (root == None):
      print("\tBecause definition is foo for a root word, assuming you want to use an existing word as root.")
      answer = instead
    else:
      print("\tBecause definition is foo for a branch, assuming you want to update existing word to link to this group.")
      answer = update
  else:
    if edef is not None and 'xx' in edef:
      print("\tDefinition contains xx. Fix.")
      sys.exit(0)
    # We need to ask the user want to do 
    answer = raw_input("\tEnter your choice: ")
    try:
      answer=answer.upper()
    except AttributeError:
      # is an integer
      pass


  # now we take the answer and do the insert/updates
  if (answer == nword):
    return add_missing(c,pword,pos,edef,root,False)

  # quitting
  if (answer == quit):
    print("\tWill quit now.")
    sys.exit(0)

  # not doing anything
  if (answer == skip):
    print("\tWill skip for now")
    return (root,-1)

  # just use an existing word as the root. This sets up the program so that the next stuff added goes into this group
  if (answer == instead):
    return use_existing_root(c,rows)

  # sometimes it's easier to just say update existing instead of add to for existing words
  if (answer == update and root == None and len(rows)==1):
    answer = 0

  # Here we are either modifying an existing word but not changing its stem or we are changing the stem and maybe also the edef
  if (answer == update and root != None):
    if (len(rows)>1):
      answer = raw_input("\tWhich word to update: ")
    else:
      answer = 0
    if rows[int(answer)]['eng'] is None or len(rows[int(answer)]['eng']) < 1:
      query = "update all_words3 set stem=%s,eng=%s,pos=%s where id=%s"
      values = (root,edef,pos,rows[int(answer)]['id'])
    else:
      query = "update all_words3 set stem=%s,pos=%s where id=%s"
      values = (root,pos,rows[int(answer)]['id'])
    prompt = False
    update_db(c,query,values,prompt)
    # if we changed a root word that had multiple words in its group, we need to change all of them
    r = rows[int(answer)] # a short cut so we don't have to retype this all the time
    if r['group_sz'] > 1 and r['stem'] != root and r['id']==r['stem']:
      query = "update all_words3 set stem=%s where stem=%s"
      values = (root,rows[int(answer)]['id'])
      update_db(c,query,values,True)
    return (root,rows[int(answer)]['id'])
  elif (answer < nword):
    query = "update all_words3 set eng=%s,pos=%s where id=%s"
    values = (edef,pos,rows[int(answer)]['id'],)
    update_db(c,query,values,prompt=False)
    return (rows[int(answer)]['stem'],rows[int(answer)]['id'])
  else:
    print("Non-sensical answer.  Exiting.")
    sys.exit(0)

def update_db(c,query,values,prompt=True):
  print("\tWill update with %s %s" % (query, values))
  if prompt:
    raw_input("\tContinue")
  c.execute(query,values)
  global total_updates
  total_updates = total_updates + 1


def add_perfectives(c,perf_string,root):
  print("\tAdding perfectives for root %s" % root)
  perfectives = perf_string.replace(',','').split() # strip any commas
  # insert the perfectives
  if ('terir' in perfectives[1]):
    posarray=('v.pf.3s','v.pf.3p.human','v.pf.3s.past', 'v.pf.3p.human.past')
  else:
    posarray=('v.pf.3s','v.pf.3p.inan','v.pf.3s.past', 'v.pf.3p.inan.past')
  for idx, pos in enumerate(posarray):
    if 'xx' in perfectives[idx]:
      print("Not adding perfective for pos %s" % pos)
      continue
    (w,b) = add_word(c,perfectives[idx],pos,None,root=root,ignore_dups=True)
    #print("TEMPORARILY ADDING JOSEPHS=4 to perfectives")
    #add_extra(c,b,'josephs',4)
    

def add_branch(c,branch,root):
  print("\tNeed to add branch %s to %s" % (branch,root))
  pieces = branch.split()
  pword = pieces[0]
  pos = pieces[1]
  edef = ' '.join(pieces[2:])
  return add_word(c,pword,pos,edef,root)

def add_links(c,links,root_words):
  for pair in itertools.combinations(links,2):
    add_link(c,root_words,*pair)

def add_link(c,root_words,a,b):

  stems = []
  linkable = True

  for word in ( a , b ):
    if word.isdigit():  # user is specifying to link the previously added root word 
      print("\tUsing root word reverse indexing: %s" % root_words)
      stems.append(root_words[-1*int(word)])
      continue
    c.execute("""select id,pos,eng,stem,pdef from all_words3 where pal like '%s' and id=stem""" % word)
    rows = c.fetchall()
    if (len(rows)>1):
      print("%s has %d matches" % (word, len(rows)))
      for idx, row in enumerate(rows):
        print("\t%d: %s %s [%s] [id %d, root %d]" % (idx,row['pos'],row['eng'],row['pdef'],row['id'],row['stem']))
      answer = raw_input("\tWhich word to link?")
      stems.append(rows[int(answer)]['stem'])
    elif (len(rows)==0):
      print("FATAL: %s has 0 matches." % word)
      sys.exit(0)
    else:
      row = rows[0]
      stems.append(row['stem'])

  actually_link(c,stems[0],stems[1])

def actually_link(c,a,b):
  lower=min(a,b)
  higher=max(a,b)
  print("\tLinking %s and %s" % ( lower, higher ) )
  update = """insert into cf (a,b) values (%s,%s)""" % (lower,higher)
  try:
    #print(update)
    c.execute(update)
    if (c.rowcount):
      pass
      #print(update)
  except MySQLdb.IntegrityError as e:
    print("\tInsert error: %s" % e)

def mywarn(msg):
  answer = raw_input("WARN: %s. Continue [y|n]: " % msg)
  if (answer != 'y'):
    sys.exit(0)

def file_len(fname):
  with open(fname) as f:
    for i, l in enumerate(f):
      pass
  return i + 1

def main():
  (db,c) = belau.connect()

  root_words=list()

  inputfile='words.txt'
  line_count = file_len(inputfile)
  print("%s has %d lines" % (inputfile,line_count))

  skipmode = False
  for lineno, line in enumerate(codecs.open(inputfile)):
    line = line.decode('utf-8').strip()
    line = line.replace('’', "'")
    print("%s %.2f Percent Done. Line %4d/%4d %s" % ("Skipping" if skipmode else "Processing", float(100.0*lineno/line_count),lineno,line_count,line))
    if skipmode and 'START' not in line:
      continue
    db.commit()
    if 'STOP' in line:
      print("Hit Stop Point")
      sys.exit(0)
    elif 'SKIP' in line:
      print("In skip mode")
      skipmode = True
      continue
    elif 'START' in line:
      print("Resuming processing after SKIP")
      skipmode = False
      continue
    elif 'ECHO' in line:
      print(line)
      continue
    elif 'DIE' in line:
      print(line)
      sys.exit(0)
    parts = line.split('--')
    pieces = parts[0].split()

    # root word
    # may have extras
    if (pieces[0] == 'w' or pieces[0] == 'v'):
      wid = root_word(pieces,c)
      root_words.append(wid)
      if pieces[0] == 'v':
        try:
          add_perfectives(c,parts[1],wid)
        except IndexError:
          mywarn("Perfectives missing")
        try:
          del parts[1]
        except IndexError:
          mywarn("Couldn't remove perfectives")
      for part in parts[1:]:
        add_branch(c,part,wid)

    # branch word on its own line
    # may have extras
    elif (pieces[0] == '-'):
      (extras,pieces) = get_extras(pieces)
      part = ' '.join(pieces[1:])
      (wid,bid) = add_branch(c,part,wid)
      add_extras(c,bid,extras)
      for part in parts[1:]:
        add_branch(c,part,wid)

    # link connection
    elif (pieces[0] == 'l'):
      add_links(c,pieces[1:],root_words)

    # phrase or example or proverb
    else:
      # for a phrase, an optional pos can be added
      pieces = parts[0].split()
      type = pieces[0]
      del pieces[0]
      pal = ' '.join(pieces)
      eng = parts[1]
      print("\tPhrase or example %s: %s -> %s" % (type,pal,eng))
      if (type=='p'):
        try: 
          pos = parts[1].strip()
          eng = parts[2]
        except IndexError:
          pos = "expression"
          eng = parts[1]
        add_word(c,pal,pos,eng,wid)
      elif (type=='e'):
        add_example(c,pal,eng,wid)
      elif (type=='r'):
        explanation = parts[2]
        add_proverb(c,pal,eng,explanation,wid)
      else:
        print("FATAL error.  Unrecognized entry %s" % line)
        sys.exit(0)
    continue

  # commit and close
  db.commit()
  c.close()
  db.close()

  # move the file out of the way
  archive_name = "old/words.%d.txt" % int(time.time())
  print("%s -> %s" % (inputfile,archive_name))
  os.rename(inputfile,archive_name)

  print("Total updates: %d" % total_updates)
    
if __name__ == "__main__": main()


