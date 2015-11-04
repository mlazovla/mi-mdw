#!/usr/bin/python
#import MySQLdb
# USAGE
# python search.py --dataset images --index index.cpickle
# import the necessary packages
from searcher import Searcher
from rgbhistogram import RGBHistogram
import numpy as np
import argparse

try:
    import cPickle as pickle
except ImportError:
    import pickle as cPickle

import glob
import cv2
 
# construct the argument parser and parse the arguments
ap = argparse.ArgumentParser()
#ap.add_argument("-id", "--id", required = True,
#	help = "Id of the query image")
#ap.add_argument("-m", "--method", required = True,
#	help = "Method that will be used to compare histogramms []")
ap.add_argument("-d", "--dataset", required = True, help = "Path to the directory that contains the images to be indexed")
ap.add_argument("-i", "--index", required = True, help = "Path to where the computed index will be stored")
args = vars(ap.parse_args())
 

print("INDEX IS: " + args["index"])
# load the index and initialize our searcher
index = cPickle.loads(open(args["index"], "rb").read())
searcher = Searcher(index, "")

# I think we need just load our custom histogram here, so we will make a request
# to database
#db = MySQLdb.connect(host="localhost", user="george", passwd="pika4u", db="vmwdb")
# you must create a Cursor object. It will let
#  you execute all the queries you need
#cur = db.cursor() 
# Use all the SQL you like
#cur.execute("SELECT * FROM YOUR_TABLE_NAME WHERE ID <> " + args["id"])
# Then we need to update index
# print all the first cell of all the rows
#for row in cur.fetchall() :
#   print row[0]

# loop over images in the index -- we will use each one as
# a query image

for (query, queryFeatures) in index.items():
	# perform the search using the current query
	results = searcher.search(queryFeatures)
 
	# load the query image and display it
	path = args["dataset"] + "/%s" % (query)
	queryImage = cv2.imread(path)
	cv2.imshow("Query", queryImage)
	print("query: %s" % (query))
 
	# initialize the two montages to display our results --
	# we have a total of 25 images in the index, but let's only
	# display the top 10 results; 5 images per montage, with
	# images that are 400x166 pixels
	montageA = np.zeros((166 * 5, 400, 3), dtype = "uint8")
	montageB = np.zeros((166 * 5, 400, 3), dtype = "uint8")
 
	# loop over the top ten results
	for j in range(0, 10):
		# grab the result (we are using row-major order) and
		# load the result image
		(score, imageName) = results[j]
		path = args["dataset"] + "/%s" % (imageName)
		result = cv2.imread(path)
		print("\t%d. %s : %.3f" % (j + 1, imageName, score))
 
		# check to see if the first montage should be used
		if j < 5:
			montageA[j * 166:(j + 1) * 166, :] = result
 
		# otherwise, the second montage should be used
		else:
			montageB[(j - 5) * 166:((j - 5) + 1) * 166, :] = result
 
	# show the results
	cv2.imshow("Results 1-5", montageA)
	#cv2.imshow("Results 6-10", montageB)
	cv2.waitKey(0)

