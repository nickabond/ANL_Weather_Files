#!/usr/bin/python
# Author: Nick Bond
# Purpose: This script allows the user to strip off data
# 	   from a website using a url library and then 
#          make an remote procedure call using RabbitMQ
#          to a virtual machine that in turn sends back an 
#          acknoledgement. A message with the website data is 
#          included with the RPC call. 

import urllib
import time
import pika
from subprocess import call




url = 'http://www.atmos.anl.gov/ANLMET/anltower.now' #Pulling Data from ANL Tower site

urlobj = urllib.urlopen(url)

for line in urlobj:
	data = urlobj.read()
	data_new = data[:908] #Slicing to extract data into variables.
datetime = data_new[57:80]
Windspeed60m_mph = data_new[119:123] #60m denotation designates height
Windspeed60m_cms = data_new[131:135]
Windspeed60m_dir = data_new[180:183]
Windspeed60m_deg = data_new[190:194]
Sigma_theta60m = data_new[248:252]
Temperature60m_faren = data_new[296:298]
Temperature60m_cels = data_new[306:310]
Barometric_in = data_new[638:643]
Barometric_kpa = data_new[651:655]
Solar_irad = data_new[709:713]
Net_rad = data_new[770:774]
Stability = data_new[831:836]
Stability_cname = data_new[823:824]
WBG_TempCels = data_new[886:891]
WBG_TempFaren = data_new[899:904]
Windspeed10m_mph = data_new[351:355]
Windspeed10m_cms = data_new[363:368]
Windspeed10m_dir = data_new[412:415]
Windspeed10m_deg = data_new[422:426]
Sigma_theta10m = data_new[480:484]
Temperature10m_cels = data_new[538:542]
Temperature10m_faren = data_new[528:530]
Relative_humidity = data_new[594:598]

print(data)

##setting contents of message##

data = [Relative_humidity,Temperature10m_faren,Temperature10m_cels,Sigma_theta10m,
	Windspeed10m_deg, Windspeed10m_dir, Windspeed10m_cms, Windspeed10m_mph,
	WBG_TempCels,WBG_TempFaren, Stability,Stability_cname,Net_rad, Solar_irad,
	Barometric_kpa, Barometric_in, Temperature60m_faren, Temperature60m_cels,
	Sigma_theta60m,Windspeed60m_deg,Windspeed60m_cms, Windspeed60m_mph, datetime,
	Windspeed60m_dir]

print(data)


data=str(data) #Converting Data for Transport
		
connection = pika.BlockingConnection(pika.ConnectionParameters(
host='hostname'))
channel = connection.channel()

channel.exchange_declare(exchange='weather_data',
        	                 type='direct')


message = data
channel.basic_publish(exchange='weather_data',
                      routing_key='ANL_Data',
                      body=message)
print ('[x]')

##RPC Call Subprocess##

call(['python','rpc_client.py'])

connection.close()







	
