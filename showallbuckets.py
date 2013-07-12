#!/usr/bin/python
# Author: Nick Bond
# Purpose: This script shows all keys within a particular S3 bucket. 
import MySQLdb as mdb
import boto 
import sys
import uuid
import boto.s3.connection
from boto.s3.key import Key

def object_creator(): 
	
	generator = uuid.uuid4()
	return generator	


filename = 'mysqlbackup.sql'
access_key_id = 'access_key_id'
secret_access_key = 'secret_access_key'

conn=boto.s3.connection.S3Connection(aws_access_key_id=access_key_id, aws_secret_access_key=secret_access_key, #Connecting to Cumulus
is_secure=False, port=8888, host='hostname',
debug=0, https_connection_factory=None, calling_format = boto.s3.connection.OrdinaryCallingFormat())

bucket = conn.get_bucket('anltowerdata') 
key = bucket.get_key('mysqlbackup.sql')

for key in bucket.list():
        print "{name}\t{size}\t{modified}".format(
                name = key.name,
                size = key.size,
                modified = key.last_modified,
                )

