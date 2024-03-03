#! /usr/bin/env python

import os
import pyglet
import glob
import time
import sys
from PIL import Image

sys.path.append('/Users/bentj/Dropbox/belau/scripts')
sys.path.append('../scripts')
import belau

def play(player,src):
  try:
    #music = pyglet.resource.media(src)
    music = pyglet.media.load(src, streaming=False)
    music.play()
    time.sleep(1)
    ans = raw_input("\tWas this sound good (r to replay, s to skip, q to quit)? [y|n|r|q]: ")
    if (ans == 'r'):
      return play(player,src)
    if (ans == 'q'):
      sys.exit(0)
    elif (ans == 's'):
      return -1
    else:
      success = (ans == 'y')
  except pyglet.media.avbin.AVbinException as e:
    print("Exception playing file. Assuming bad.")
    success = 0  
  print "%s -> %d" % ( src, success )
  return success

def get_table():
  return "upload_audio"

def get_filter(dbid):
  return "where externalid=%d and externaltable='examples' and externalcolumn='palauan'" % dbid

def verify(c,dbid,success,table,where):
  print "%s %d" % (( "Validating" if success!=0 else "Invalidating"), dbid)
  q = "update %s set verified=%d,uploaded=%d %s" % (table,success,success,where)
  c.execute(q)

def setup_pyglet(mp3_dir):
  working_dir = os.path.dirname(os.path.realpath('.'))
  pyglet.resource.path = [os.path.join(working_dir,mp3_dir)]
  pyglet.resource.reindex()

def get_all_pending(c,q,key):
  pending = dict()
  c.execute(q)
  for row in c.fetchall():
    pending[row[key]] = row
  return pending
    
def get_id(file_path):
  eid = int(os.path.splitext(file_path)[0]) # the id in the examples table
  return eid

def check_audio(c,db):
  mp3_dir = 'mp3s/examples.palauan'
  setup_pyglet(mp3_dir)
  os.chdir(mp3_dir)

  q = "select u.id as uid,e.id as eid,e.palauan as pal,e.english as eng from examples e,upload_audio u where e.id=u.externalid and (isnull(u.verified) or u.verified=0) and u.uploaded=1"
  pending = get_all_pending(c,q,'eid')

  for file in list(glob.glob('*.mp3')):
    eid = get_id(file)
    if (eid in pending):
      print "Checking %s\n\t%s" % (pending[eid]['pal'],pending[eid]['eng'])
      success = play(None,file)
      if success != -1:
        verify(c,eid,success,get_table(),get_filter(eid))
      db.commit()
    else:
      print "Skipping %d" % eid 

def rotate(pic,times):
  angle = int(times) * 90
  print "Need to rotate %s %d" % (pic,angle)
  rotated = Image.open(pic).rotate(angle)
  rotated.save(pic)

def check_pic(file,pal,eng,pdef):
  print "Checking %s: %s -> %s [%s]" % (file,pal,eng,pdef)
  Image.open(file).show()
  ans = raw_input("\tIs this picture good? (1-2-3 to rotate, m for mediocre, s to skip, q to quit)? [y|n|r|q]: ")
  if (ans == 'q'):
    sys.exit(0)
  elif str.isdigit(ans):
    rotate(file,ans)
    return check_pic(file,pal,eng,pdef)
  elif ans == 'r':
    return check_pic(file,pal,eng,pdef)
  elif ans == 's':
    return -1
  elif ans == 'm':
    return 2
  else:
    return (ans == 'y')

def check_pics(c,db):
  pics_dir = 'pics'
  pics_parent = '..'
  os.chdir(pics_dir)
  table = 'pictures'
  w = "w.id=p.allwid and (isnull(p.verified) or p.verified=0) and p.uploaded=1" 
  q = "select p.id as pid,w.id as wid,w.pal as pal,w.pdef as pdef,w.eng as eng,w.tags as tags from all_words3 w,%s p where %s" % (table,w)
  pending = get_all_pending(c,q,'wid')
  for file in list(glob.glob('*.jpg')):
    wid = get_id(file)
    if (wid in pending):
      pal  = pending[wid]['pal']
      eng  = pending[wid]['eng']
      pdef = pending[wid]['pdef']
      success = check_pic(file,pal,eng,pdef)
      if success != -1:
        verify(c,wid,success,table,"where id=%s" % pending[wid]['pid'])
    else:
      print "Skipping %d" % wid
  #print pending
  os.chdir(pics_parent)

def main():

  (db,c) = belau.connect()

  check_pics(c,db)
  check_audio(c,db)

if __name__ == "__main__":
  main()
