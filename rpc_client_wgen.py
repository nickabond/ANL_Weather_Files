### WEATHER GENERATOR RPC CLIENT ###

#!/usr/bin/python

### WEATHER GENERATOR RPC CLIENT ###
# Author: Nick Bond
# Purpose: This script initiates a database call through RabbitMQ
#          and receives a string of data that we will use to compute
#          autocorrelation in another script.The data is then plotted
#          overlaying each other as time progresses. Additionally the 
#          plots are saved to a PDF and emailed off. 


import pika
import uuid
from subprocess import call
import matplotlib as mpl
mpl.use('Agg')
from matplotlib.backends.backend_agg import FigureCanvasAgg
from ClimateUtilities import *
import phys
from matplotlib.backends.backend_pdf import PdfPages
import pylab as pl
import matplotlib.artist 
import matplotlib.pyplot as plt
from matplotlib.figure import Figure
from matplotlib.patches import Polygon
#import matplotlib.color
import MySQLdb as mdb
import smtplib
import base64
import time
from datetime import datetime


import numpy as np;
import scipy.spatial.distance;
auCorr2 = []
valueslen = []
#auCorr =[]
new2=[]

class RpcClient(object):
    def __init__(self):
        self.connection = pika.BlockingConnection(pika.ConnectionParameters(
                host='vm-103.alamo.futuregrid.org'))

        self.channel = self.connection.channel()

        result = self.channel.queue_declare(exclusive=True)
        self.callback_queue = result.method.queue

        self.channel.basic_consume(self.on_response, no_ack=True,
                                   queue=self.callback_queue)

    def on_response(self, ch, method, props, body):
        if self.corr_id == props.correlation_id:
            self.response = body

    def call(self, n):
        self.response = None
        self.corr_id = str(uuid.uuid4())
        self.channel.basic_publish(exchange='',
                                   routing_key='rpc_wgen_queue',
                                   properties=pika.BasicProperties(
                                         reply_to = self.callback_queue,
                                         correlation_id = self.corr_id,
                                         ),
                                   body=str(n))
        while self.response is None:
            self.connection.process_data_events()
        return str(self.response)

rpc = RpcClient()

#print " [x] Initiating Database Call"

response = rpc.call('It works!')

def autocorr(x):
    result = np.correlate(x, x, mode = 'full')
    maxcorr = np.argmax(result)
   
    result = result / result[maxcorr]
    
    return result[result.size/2:]


index = 0
response = eval(response)
Iterator = 0
Timenum=0
Timenum2=0
pdfnum= 1


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



while index <= 23:

	Timenum_wcol = str(Timenum) 
        Timenum2_wcol = str(Timenum2) 
                       

        if Iterator <= 10:
        	range1= timestamp_lower1 + '0'+ Timenum_wcol + timestamp_lower_end
                range2= timestamp_upper1 + '0' + Timenum2_wcol + timestamp_upper_end
		Date = range1
                Date2 = range2

        if Iterator > 10 and Iterator <= 22:
                range1= timestamp_lower1 + Timenum_wcol + timestamp_lower_end
                range2= timestamp_upper1 + Timenum2_wcol + timestamp_upper_end
		Date = range1
                Date2 = range2

        if Iterator == 23:
                print('Yes we made it')
                range1= timestamp_upper1 + '00:00:00'
                range2= timestamp_upper_fiter + '00:00:00'
		Date = range1
		Date2 = range2  

	new = response[index]
	print(response[index])
	new = map(float, new)
#	new2 = new2.append(new)
	new2 += [new]
	print(new)

	s1 = np.array(new, dtype=float);
	print(s1)


	print '*************************************************'
	global s1short, meanshort, stdShort, s1dev, s1shX, s1shXk

	s1short = s1
	meanshort = s1short.mean()
	stdShort = s1short.std()

	s1dev = s1short - meanshort
	s1sh_len = s1short.size

	s1shX = np.arange(1,s1sh_len + 1)

##########################################################
# c0 to be computed ...
##########################################################

	sumY = 0
	kk = 1
	for ii in s1shX: 
		if ii > s1sh_len:
			break
        	sumY += s1dev[ii-1]*s1dev[ii-1]
    
	c0 = sumY / s1sh_len
	print 'c0 = ', c0
##########################################################
# now compute autocorrelation
##########################################################

	auCorr = []
	
#	print auCorr
	s1shXk = s1shX
	lenS1 = s1sh_len
	nn = 1  # factor by which lenS1 should be divided in order
                                # to reduce computation length ... 1, 2, 3, 4
                                # should not exceed 4

	for kk in s1shXk:
                sumY = 0
                for ii in s1shX:
                        if ii >= s1sh_len or ii + kk - 1>=s1sh_len/nn:
                                break
                        sumY += s1dev[ii-1]*s1dev[ii+kk-1]
                auCorrElement = sumY / (s1sh_len) #Y-Axis Value
                auCorrElement = auCorrElement / c0
                auCorr.append(auCorrElement)		
                s1shX = s1shXk[:lenS1-kk]
	auCorr2 += [auCorr]
	
 	      
#########################################################
#
# first 15 of above Values are consistent with
# Box-Jenkins "Time Series Analysis", p.34 Table 2.2
#
	s1sh_sdt = s1dev.std()  # Standardabweichung short 
	print '#########################################'
	s1shXfloat = np.ndarray(shape=(1,lenS1),dtype=float)
	s1shXfloat = s1shXk # to make floating point from integer
	valueslen += [s1shXfloat]
	print(len(s1shXfloat))
	print(len(new))
## Second Plot (Time in 15 minute intervals V.S. Solar Irradiation) ##


	
 
  #	fig = pl.figure()
   #     ax = pl.subplot(111)
#	ax.bar(s1shXfloat*4, auCorr, width=1)
 #       plt.title('Auto-Correlation For:' + ' %s UTC ' % (Date) + '-' + '%s UTC' % (Date2))
#	plt.xlabel('Time Lag (In 15 Minute Time Intervals)\nTotal Time Intervals: %s' % len(new))
 #       plt.ylabel('Autocorrelation')
  #      plt.savefig(pp, format='pdf')
############################        

	index += 1
	Iterator += 1
	
iter = 0
#index = 0
pp = PdfPages('1c.pdf')
fig1 = pl.figure()
ax = pl.subplot(111)
print(valueslen[0])
print(valueslen[23])
print(auCorr2[0])
print(auCorr2[20])

print(type(auCorr2)==list)
while iter <= 23:

	ax.plot((valueslen[iter]*15),auCorr2[iter], label= '%s' % iter)

	
	iter += 1
plt.legend()
plt.title('Auto-Correlation For:' + ' %s UTC ' % (Date) + '-' + '%s UTC' % (Date2))
plt.xlabel('Time (In Minutes)')
plt.ylabel('Autocorrelation')	
plt.savefig(pp, format='pdf')


#index = 1
iter = 0
fig0 = pl.figure()
ax = pl.subplot(111)

while iter <= 23:
	
        ax.plot((valueslen[iter]/4),auCorr2[iter])
        iter += 1
	


plt.title('Auto-Correlation For:' + ' %s UTC ' % (Date) + '-' + '%s UTC' % (Date2))
plt.xlabel('Time (In Hours)')
plt.ylabel('Autocorrelation')
plt.savefig(pp, format='pdf')


iter = 0
fig2 = pl.figure()
ax = pl.subplot(111)

while iter <= 23:

        ax.plot((valueslen[iter]),new2[iter])
        iter += 1

plt.title('Solar Irradiance For:' + ' %s UTC ' % (Date) + '-' + '%s UTC' % (Date2))
plt.xlabel('Time (In 15 Minute Intervals)')
plt.ylabel('Autocorrelation')
plt.savefig(pp, format='pdf')



pp.savefig()
pp.close()




#	pp = PdfPages('%sc.pdf'% index)
		
        ## First Plot (Time in Hours V.S. Autocorrelation) ##
#	w2 = plot(c2)
#	plt.xlabel('Time Lag (In Minutes)')
#	plt.ylabel('Autocorrelation')
#	plt.savefig(pp, format='pdf')

        ## Second Plot (Time in 15 minute intervals V.S. Solar Irradiation) ##

####### OPTIONAL BAR CHART #####
#while it2 <= 23:
#	fig = pl.figure()
#	ax = pl.subplot(111)
#	ax.bar(s1shXfloat, auCorr[it2], width=1)
#	it2 += 1
#	fig.PlotTitle = 'Auto-Correlation For:' + ' %s UTC ' % (Date) + '-' + '%s UTC' % (Date2)
#	plt.xlabel('Time Lag (In 15 Minute Time Intervals)\nTotal Time Intervals: %s' % len(new))
#	plt.ylabel('Autocorrelation')
#	plt.savefig(pp, format='pdf')
############################        
	


#plt.savefig('l.pdf')

        ## Third Plot (Time in UTC V.S. Autocorrelation)
#	c4 = Curve()
#	c4.addCurve((s1shXfloat/4) * 100)
#	c4Y
#.addCurve(new)
#	c4.PlotTitle = 'Solar Irradiation For:' + ' %s UTC ' % (Date) + '-' + '%s UTC' % (Date2)
#	w3 = plot(c4)
#	plt.xlabel('Time Lag (In Hours)')
##	plt.ylabel('Solar Irradiation (W/m**2)')
#	plt.savefig(pp, format='pdf')
#	pp.savefig()
#	pp.close()
#	index +=1
#	Timenum +=1
#	Timenum2 += 1

#######PUT BACK For Singular plots#####
#	fig = pl.figure()
 #       ax = pl.subplot(111)
  #      ax.plot((s1shXfloat/4), new)
   #     fig.PlotTitle = 'Auto-Correlation For:' + ' %s UTC ' % (Date) + '-' + '%s UTC' % (Date2)
    #    plt.xlabel('Time Lag (In Hours)')
    #    plt.ylabel('Solar Irradiation (W/m**2)')
     ##   plt.savefig(pp, format='pdf')
#	pp.savefig()
 #       pp.close()
  #      index +=1
   #     Timenum +=1
    #    Timenum2 += 1
	
#	it2 += 1
#	index += 1

###########################
call(['python','mergepdfs.py']) #Merges PDF Pages.
time.sleep(10)
call(['python','email_test1.py']) #Creating a Subprocess that emails the PDF off.




