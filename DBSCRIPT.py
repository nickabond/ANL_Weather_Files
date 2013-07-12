#!/usr/bin/python
#Author: Nick Bond
#Purpose: This script receives values using Rabbit MQ from a virtual
         # machine and then inserts them into a mysql database. 
         
import pika
import sys
import time
import MySQLdb as mdb
connection = pika.BlockingConnection(pika.ConnectionParameters(
        host='hostname'))
channel = connection.channel()

channel.exchange_declare(exchange='weather_data',
                         type='direct')

result = channel.queue_declare(exclusive=True)
queue_name = result.method.queue

channel.queue_bind(exchange='weather_data',
                   queue=queue_name,
                   routing_key='ANL_Data')

def callback(ch, method, properties, body):
        print (body)
        body_list=eval(body) #Body converted back from a string to a list
        
###Taking the necessary values from the RabbitMQ Message after evaluation###

        Relative_humidity = float(body_list[0]) #Variables Reassigned to Their Values
        Temperature10m_faren = float(body_list[1])
        Temperature10m_cels = float(body_list[2])
        Sigma_theta10m = float(body_list[3])
        Windspeed10m_deg = float(body_list[4])
        Windspeed10m_dir = body_list[5]
        Windspeed10m_cms = float(body_list[6])
        Windspeed10m_mph = float(body_list[7])
        WBG_TempCels = float(body_list[8])
        WBG_TempFaren = float(body_list[9])
        Stability = float(body_list[10])
        Stability_cname = body_list[11]
        Net_rad = float(body_list[12])
        Solar_irad = float(body_list[13])
        Barometric_kpa = float(body_list[14])
        Barometric_in = float(body_list[15])
        Temperature60m_faren = float(body_list[16])
        Temperature60m_cels = float(body_list[17])
        Sigma_theta60m = float(body_list[18])
        Windspeed60m_deg = float(body_list[19])
        Windspeed60m_cms = float(body_list[20])
        Windspeed60m_mph = float(body_list[21])
        datetime = body_list[22]
        date = datetime[12:] 
#	date = time.strftime('%Y-%m-%d')
        datetime = body_list[22]    
	curtime = datetime[0:5]
	print (curtime)
        Windspeed60m_dir = body_list[23]
	

        print(Relative_humidity,Temperature10m_faren,Temperature10m_cels,Sigma_theta10m,
               Windspeed10m_deg, Windspeed10m_dir, Windspeed10m_cms, Windspeed10m_mph,
               WBG_TempCels,WBG_TempFaren, Stability,Stability_cname,Net_rad, Solar_irad,
               Barometric_kpa, Barometric_in, Temperature60m_faren, Temperature60m_cels,
               Sigma_theta60m,Windspeed60m_deg,Windspeed60m_cms, Windspeed60m_mph, datetime, Windspeed60m_dir)

    ###Accessing the database and inserting values received from ANL Tower Data script###
        
	try:
                con = mdb.connect('localhost', 'username', 'password', 'tablename');

                with con:
                        cur = con.cursor(mdb.cursors.DictCursor)
  			
			##Creates table if it does not already exist.##                     
                       	try:
					sql2= """CREATE TABLE ANL4(ts TIMESTAMP,Date VARCHAR(20), Time INT, Relative_Humidity VARCHAR(12), Temperature_10m_F VARCHAR(12), Temperature_10m_C VARCHAR(12),
                                        Sigma_Theta_10m VARCHAR(12), Wind_10m_Degrees VARCHAR(12), Wind_Direction_10m VARCHAR(12),Windspeed_10m_cms VARCHAR(12), Windspeed_10m_mph VARCHAR(12),
                                        Wet_Bulb_Temp_Cels VARCHAR(12), Wet_Bulb_Temp_Faren VARCHAR(12), Stability VARCHAR(12), Stability_Index VARCHAR(12),
                                        Net_Radiation VARCHAR(12), Solar_Irradiation VARCHAR(12),Barometric_Pressure_kpa VARCHAR(12), Barometric_Pressure_in VARCHAR(12),
                                        Temperature_60m_F VARCHAR(12), Temperature_60m_C VARCHAR(12), Sigma_Theta_60m VARCHAR(12),Windspeed_60m_deg VARCHAR(12),
                                        Windspeed_60m_cms VARCHAR(12),Windspeed_60m_mph VARCHAR(12),Windspeed_60m_dir VARCHAR(12), PRIMARY KEY(Date,Time))"""
			

                        		cur.execute(sql2)
             		except:
				
                                	sql= """INSERT IGNORE INTO ANL4(Date, Time, Relative_Humidity, Temperature_10m_F, Temperature_10m_C,
                                        Sigma_Theta_10m, Wind_10m_Degrees, Wind_Direction_10m, Windspeed_10m_cms, Windspeed_10m_mph,
                                        Wet_Bulb_Temp_Cels, Wet_Bulb_Temp_Faren, Stability, Stability_Index,
                                        Net_Radiation, Solar_Irradiation,Barometric_Pressure_kpa, Barometric_Pressure_in,
                                        Temperature_60m_F, Temperature_60m_C, Sigma_Theta_60m,Windspeed_60m_deg,
                                        Windspeed_60m_cms,Windspeed_60m_mph,Windspeed_60m_dir)
                                        VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                                         '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')""" % (date, curtime,Relative_humidity,Temperature10m_faren,Temperature10m_cels,Sigma_theta10m,Windspeed10m_deg, Windspeed10m_dir, Windspeed10m_cms, Windspeed10m_mph,WBG_TempCels,WBG_TempFaren, Stability,Stability_cname,Net_rad, Solar_irad,Barometric_kpa, Barometric_in, Temperature60m_faren, Temperature60m_cels,Sigma_theta60m,Windspeed60m_deg,Windspeed60m_cms, Windspeed60m_mph, Windspeed60m_dir)


                               		cur.execute(sql)
			
			finally: None
				
        				
      	except mdb.Error, e:
		print "Error %d: %s" % (e.args[0],e.args[1])
                sys.exit(1)
	if con:
                con.close()


                       
channel.basic_consume(callback,
                      queue=queue_name,
                      no_ack=True)

channel.start_consuming()






