#!/usr/bin/python
# Author: Nick Bond with code snippets compliments of UC
# Purpose: This scripts is itended to read in values from a 
#	   from a database and used these values for computation.
#          This particular script computes autocorrelation for
#          solar irradiance and then plots them on single plots.
#          Additionally the plots are saved to a pdf using a 
#          subprocess call and emailed to the respective parties. 

#Some additional libraries from the University of Chicago are used. 

import matplotlib as mpl
mpl.use('Agg')
from matplotlib.backends.backend_agg import FigureCanvasAgg
from ClimateUtilities import *
import phys
from matplotlib.backends.backend_pdf import PdfPages
import pylab as pl
import matplotlib.pyplot as plt
from matplotlib.figure import Figure
from matplotlib.patches import Polygon
import MySQLdb as mdb
import smtplib
import base64
from subprocess import call
import time
from datetime import datetime
import numpy as np;
import scipy.spatial.distance;

# functions to be defined ... :
#
#
def autocorr(x):
    result = np.correlate(x, x, mode = 'full')
    maxcorr = np.argmax(result)
    # print 'maximum = ', result[maxcorr]
    result = result / result[maxcorr]
    #   
    return result[result.size/2:]

##

Iterator = 1
Timenum=0
Timenum2=1
pdfnum= 0

###Getting Lower And Upper Bound Date and Times####

timestamp_lower = time.time()
timestamp_lower = timestamp_lower - 24 * 60 * 60
timestamp_lower=time.strftime("%Y/%m/%d", time.localtime(timestamp_lower))

timestamp_upper = datetime.utcnow()
timestamp_upper = str(timestamp_upper)
timestamp_upper = timestamp_upper[:-7]

upper_slice = timestamp_upper[-8:]
timestamp_lower = time.time()
timestamp_lower = timestamp_lower - 24 * 60 * 60 
timestamp_upper_fiter = timestamp_lower + 48 * 60 * 60


timestamp_lower=time.strftime("%Y-%m-%d"+" %s" % upper_slice, time.localtime(timestamp_lower))
timestamp_upper_fiter=time.strftime("%Y-%m-%d"+" %s" % upper_slice, time.localtime(timestamp_upper_fiter))

print(timestamp_lower)
print(timestamp_upper)

timestamp_lower1 = timestamp_lower[:-8]
timestamp_upper1 = timestamp_upper[:-8]
timestamp_upper_fiter = timestamp_upper_fiter[:-8]

timestamp_upper_end = timestamp_upper[-3:]
timestamp_lower_end = timestamp_lower[-3:]

Date = timestamp_lower1
Date2 = timestamp_upper1
Time = 0
Time2 = 0

####Connecting to the database and setting ranges######

con = mdb.connect('hostname', 'username', 'password', 'tablename');
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
    		new=[]
    		for row in rows:
        		new += row                #converting tuple to list

    		new = map(float, new)
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
 


#########################################################
#
# 
# Box-Jenkins "Time Series Analysis"
#
#########################################################
	s1sh_sdt = s1dev.std()  # Standardabweichung short 
	print '#########################################'



	c2 = Curve()
	
	s1shXfloat = np.ndarray(shape=(1,lenS1),dtype=float)
	s1shXfloat = s1shXk # to make floating point from integer
        print(len(s1shXfloat))             
	print(len(new))
	c2.addCurve(s1shXfloat * 15)
	c2.addCurve(auCorr, '', 'Autocorr')
	c2.PlotTitle = 'Auto-Correlation For:' + ' %s %s UTC ' % (Date,Time) + '-' + '%s %s UTC' % (Date2,Time2)

	
	pp = PdfPages('%sc.pdf'% pdfnum)
	
	## First Plot (Time in Hours V.S. Autocorrelation) ##
	w2 = plot(c2)
	plt.xlabel('Time Lag (In Minutes)')
	plt.ylabel('Autocorrelation')
	plt.savefig(pp, format='pdf')
#	
	
	## Second Plot (Time in 15 minute intervals V.S. Solar Irradiation) ##
	fig = pl.figure()
	ax = pl.subplot(111)
	ax.bar(s1shXfloat, auCorr, width=1)
	plt.PlotTitle = 'Auto-Correlation For:' + ' %s %s UTC ' % (Date,Time) + '-' + '%s %s UTC' % (Date2,Time2)
	plt.xlabel('Time Lag (In 15 Minute Time Intervals)\nTotal Time Intervals: %s' % len(new))
        plt.ylabel('Autocorrelation')
	plt.savefig(pp, format='pdf')
	
	
	## Third Plot (Time in UTC V.S. Autocorrelation)
	c4 = Curve()
	c4.addCurve((s1shXfloat/4) * 100)
	c4.addCurve(new)
	c4.PlotTitle = 'Solar Irradiation For:' + ' %s %s UTC ' % (Date,Time) + '-' + '%s %s UTC' % (Date2,Time2)
	w3 = plot(c4)
	plt.xlabel('Time Lag (In Hours)')
        plt.ylabel('Solar Irradiation (W/m**2)')
	plt.savefig(pp, format='pdf')       
	# plt.savefig('r.pdf')

##########################################################
#
# now try function "autocorr(arr)" and plot it
#
##########################################################


	Time += 100
        Time2 += 100
	Iterator +=1
	pdfnum += 1
	Timenum += 1
	Timenum2 += 1
	pp.savefig()
	pp.close()
call(['python','mergepdfs.py']) #Merges PDF Pages.
time.sleep(10)
call(['python','email_test1.py']) #Creating a Subprocess that emails the PDF off.
