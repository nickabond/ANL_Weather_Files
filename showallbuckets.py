#!/usr/bin/python
import MySQLdb as mdb
import boto 
import sys
import uuid
import boto.s3.connection
from boto.s3.key import Key

def object_creator(): 
	
	generator = uuid.uuid4()
	return generator	


#objname = "cumulus://svc.uc.futuregrid.org:8888/ANLTowerData/%s" % object_creator()

filename = 'mysqlbackup.sql'

access_key_id = 'Ua1QUBp9XFeApwMv0sSXk'
secret_access_key = 'lB8idOCDF47Isxo3oF2guUprg6BXIveKugpiLQr6Kw'

conn=boto.s3.connection.S3Connection(aws_access_key_id=access_key_id, aws_secret_access_key=secret_access_key, #Connecting to Cumulus
is_secure=False, port=8888, host='svc.uc.futuregrid.org',
debug=0, https_connection_factory=None, calling_format = boto.s3.connection.OrdinaryCallingFormat())

bucket = conn.get_bucket('anltowerdata') 


###key = bucket.new_key('mysqlbackup.sql')
#key.set_contents_from_string('yes,indeed')
###key.set_contents_from_filename(filename)
###key = bucket.get_key('mysqlbackup.sql')
###key.set_canned_acl('public-read')
key = bucket.get_key('mysqlbackup.sql')
#key_url = key.generate_url(0, query_auth=False, force_http=True)
#print key_url
for key in bucket.list():
        print "{name}\t{size}\t{modified}".format(
                name = key.name,
                size = key.size,
                modified = key.last_modified,
                )

