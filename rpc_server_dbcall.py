#!/usr/bin/python
# Author: Nick Bond
# Purpose: This script listens for RPC calls from the rpc_call_wgen.py script
#          upon recieving an RPC signal a pull from the database is initiated
#          based on specific timestamp bounds. A string of the requested data
#          is then sent back to the RPC client.
import pika
from subprocess import call
import MySQLdb as mdb
from subprocess import call
import time
from datetime import datetime


connection = pika.BlockingConnection(pika.ConnectionParameters(
        host='hostname'))

channel = connection.channel()

channel.queue_declare(queue='rpc_wgen_queue')


def on_request(ch, method, props, body):
		n = str(body)
                print(n)
      
		print('%s'% n)
	
	
		Iterator = 1
		Timenum=0
		Timenum2=1
		pdfnum= 0
	
		###Getting Lower And Upper Bound Date and Times####

		timestamp_lower = time.time()
	
		timestamp_lower = timestamp_lower - 24 * 60 * 60
		timestamp_lower=time.strftime("%Y-%m-%d", time.localtime(timestamp_lower))
		
		
		timestamp_upper = datetime.utcnow()
		timestamp_upper = str(timestamp_upper)
		timestamp_upper = timestamp_upper[:-7]

		upper_slice = timestamp_upper[-8:]
		timestamp_lower = time.time()
		timestamp_upper_new = time.time()
		timestamp_lower = timestamp_lower - 48 * 60 * 60
		timestamp_upper_fiter = timestamp_upper_new 
		timestamp_upper = timestamp_upper_new - 24 * 60 * 60

		timestamp_lower=time.strftime("%Y-%m-%d"+" %s" % upper_slice, time.localtime(timestamp_lower))
		timestamp_upper_fiter=time.strftime("%Y-%m-%d"+" %s" % upper_slice, time.localtime(timestamp_upper_fiter))
		timestamp_upper=time.strftime("%Y-%m-%d"+" %s" % upper_slice, time.localtime(timestamp_upper))

		timestamp_lower1 = timestamp_lower[:-8]
		timestamp_upper1 = timestamp_upper[:-8]
		timestamp_upper_fiter = timestamp_upper_fiter[:-8]

		timestamp_upper_end = timestamp_upper[-3:]
		timestamp_lower_end = timestamp_lower[-3:]
		
		Date = timestamp_lower1
		Date2 = timestamp_upper1
		
		Time = 0
		Time2 = 0
		new=[]

		####Connecting to the database and setting ranges######

		con = mdb.connect('localhost', 'username', 'password', 'database_name');
		while Iterator <= 24:
        		print(Timenum)
        		print(Timenum2)
        		Timenum_wcol = str(Timenum) + ':'
        		Timenum2_wcol = str(Timenum2) + ':'
        		with con:

                		if Iterator <= 10:
                        		range1= timestamp_lower1 + '0'+ Timenum_wcol + timestamp_lower_end
                        		range2= timestamp_upper1 + '0' + Timenum2_wcol + timestamp_upper_end

                		if Iterator > 10 and Iterator <= 23:
                        		range1= timestamp_lower1 + Timenum_wcol + timestamp_lower_end
                        		range2= timestamp_upper1 + Timenum2_wcol + timestamp_upper_end

                		if Iterator == 24:
                        		print('Yes we made it')
                        		range1= timestamp_upper1 + '00:00:00'
                        		range2= timestamp_upper_fiter + '00:00:00'
                        		print(range1)
                        		print(range2)


                		cur = con.cursor()
                		cur.execute("SELECT (Solar_Irradiation) FROM ANL4 WHERE (ts BETWEEN ('%s') AND ('%s') )" %(range1,range2)) #fetching solar irradiation from database

                		rows = cur.fetchall()

                		new1=[]


                		for row in rows:
                        		new1 += row                #converting tuple to list


                		#new = map(float, new)
                		new += [new1]
				Iterator += 1
				Timenum +=1
				Timenum2 += 1
				

		response = str(new)

        	channel.basic_publish(exchange='',
                              routing_key=props.reply_to,
                              properties=pika.BasicProperties(correlation_id = \
                              props.correlation_id),
                              body=str(response))
        	ch.basic_ack(delivery_tag = method.delivery_tag)

        	ch.basic_qos(prefetch_count=1)
		print(response)
channel.basic_consume(on_request, queue='rpc_wgen_queue')

print " [x] Awaiting RPC requests"

channel.start_consuming()


