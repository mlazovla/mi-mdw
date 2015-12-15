#!/usr/bin/python
# USAGE
# python search.py --dataset images --index index.cpickle
# import the necessary packages
from searcher import Searcher
import MySQLdb
import numpy as np
import argparse

try:
    import cPickle as pickle
except ImportError:
    import pickle as cPickle

#
# # construct the argument parser and parse the arguments
# ap = argparse.ArgumentParser()
# # ap.add_argument("-id", "--id", required = True,
# # help = "Id of the query image")
# # ap.add_argument("-m", "--method", required = True,
# # help = "Method that will be used to compare histogramms []")
# ap.add_argument("-d", "--dataset", required=True, help="Path to the directory that contains the images to be indexed")
# ap.add_argument("-i", "--index", required=True, help="Path to where the computed index will be stored")
# args = vars(ap.parse_args())

#print("INDEX IS: " + args["index"])
# load the index and initialize our searcher
#index = cPickle.loads(open(args["index"], "rb").read())

# I think we need just load our custom histogram here, so we will make a request
# to database
db = MySQLdb.connect(host="localhost", user="root", passwd="stoupa", db="mi_vwm")
# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()
# Use all the SQL you like
cur.execute("SELECT svg.id, svg.`h_angle`, svg.`h_color_h`, svg.`h_color_l`, svg.`h_color_s` FROM `svg` LEFT JOIN svg_similarity ss ON `svg`.`id` = ss.`src_svg_id` WHERE ss.`src_svg_id` IS NULL")

# At firts select only those images, that don't yet have similarity
# initialize the index dictionary to store our quantifed
# images, with the 'key' of the dictionary being the image
# filename (or id) and the 'value' our computed features
index = {}


# Then we need to update index
# print all the first cell of all the rows
for row in cur.fetchall():
    color1Hist = None
    color2Hist = None
    color3Hist = None

    if row[1] is not None:
        anglesHist = [int(angle) for angle in row[1].split(",")]
    if row[2] is not None:
        color1Hist = [int(color1) for color1 in row[2].split(",")]
    if row[3] is not None:
        color2Hist = [int(color2) for color2 in row[3].split(",")]
    if row[4] is not None:
        color3Hist = [int(color3) for color3 in row[4].split(",")]
    index[row[0]] = [anglesHist, color1Hist, color2Hist, color3Hist]
    # index[row[1]] = row[3].split(",")

results = []
searcher = Searcher(index)

# loop over images in the index -- we will use each one as
# a query image
for (query, queryFeatures) in index.items():
    # perform the search using the current query
    results = searcher.search(queryFeatures)
    print "Query was: " + str(query) + ". Results DESC:"
    # print "Query was: " + query + ". Results DESC:"
    i = 0

    for (id, result) in results.iteritems():
        # INSERT INTO `svg_similarity` (`id`, `src_svg_id`, `dst_svg_id`, `angle`, `colors`) VALUES (NULL, '1', '2', '0.4', '0.6');
        # print str(id) + ": ", result
        x = db.cursor()
        sqlstring = """INSERT INTO `svg_similarity` (`id`, `src_svg_id`, `dst_svg_id`, `angle`, `colors`) VALUES (NULL, %d, %d, %f, NULL);"""%(query, id, result[0]) 
        try:
            x.execute(sqlstring)
        except:
            db.rollback()
    db.commit()    
    x.close()

# for result in results:
#     i += 1
#     print str(i) + ": ", result
# # load the query image and display it
# path = args["dataset"] + "/%s" % (query)
# queryImage = cv2.imread(path)
# cv2.imshow("Query", queryImage)
# print("query: %s" % (query))
#
# # initialize the two montages to display our results --
# # we have a total of 25 images in the index, but let's only
# # display the top 10 results; 5 images per montage, with
# # images that are 400x166 pixels
# montageA = np.zeros((166 * 5, 400, 3), dtype="uint8")
# montageB = np.zeros((166 * 5, 400, 3), dtype="uint8")
#
# # loop over the top ten results
# for j in range(0, 10):
#     # grab the result (we are using row-major order) and
#     # load the result image
#     (score, imageName) = results[j]
#     path = args["dataset"] + "/%s" % (imageName)
#     result = cv2.imread(path)
#     print("\t%d. %s : %.3f" % (j + 1, imageName, score))
#
#     # check to see if the first montage should be used
#     if j < 5:
#         montageA[j * 166:(j + 1) * 166, :] = result
#
#     # otherwise, the second montage should be used
#     else:
#         montageB[(j - 5) * 166:((j - 5) + 1) * 166, :] = result
#
# # show the results
# cv2.imshow("Results 1-5", montageA)
# # cv2.imshow("Results 6-10", montageB)
# cv2.waitKey(0)

