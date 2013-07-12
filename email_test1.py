#!/usr/bin/python

import smtplib
import base64

filename = "1c.pdf"
s= smtplib.SMTP('smtp.gmail.com', 587)
s.set_debuglevel(1)
s.ehlo()
s.starttls()
s.ehlo()
s.login('anlweathertower@gmail.com', 'Anltdata73')


# Read a file and encode it into base64 format
fo = open(filename, "rb")
filecontent = fo.read()
encodedcontent = base64.b64encode(filecontent)  # base64

sender = 'anlweathertower@gmail.com'
reciever = 'nickabond@gmail.com'

marker = "ANLTOWERDATA"

body ="""You will find a forecast of soil moisture, relative humidity, and solar irradiation for the next 24 hours attached."""
# Define the main headers.
part1 = """From: From ANL Weather Generator <anlweathertower@gmail.com.net>
To: To Nick <nickabond@gmail.com>
Subject: Sending Attachement
MIME-Version: 1.0
Content-Type: multipart/mixed; boundary=%s
--%s
""" % (marker, marker)

# Define the message action
part2 = """Content-Type: text/plain
Content-Transfer-Encoding:8bit

%s
--%s
""" % (body,marker)

# Define the attachment section
part3 = """Content-Type: multipart/mixed; name=\"%s\"
Content-Transfer-Encoding:base64
Content-Disposition: attachment; filename=%s

%s
--%s--
""" %(filename, filename, encodedcontent, marker)
message = part1 + part2 + part3

try:
   
   s.sendmail(sender, reciever, message)
   print "Successfully sent email"
except Exception:
   print "Error: unable to send email"
