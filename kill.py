#!/usr/bin/python
# Author: Nick Bond
# Purpose: This is a simple cleanup script that makes sure that 
#          no lingering python processes are bogging down the virtual
#          machine. 
from subprocess import call

call(['pkill','-9','python'])

