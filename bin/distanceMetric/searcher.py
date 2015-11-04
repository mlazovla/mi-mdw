# import the necessary packages
import numpy as np

class Searcher:
	def __init__(self, index):
		# store our index of images
		# index here is a dictionary of histograms of images in our database
		# may be we need to change it to something different
		self.index = index

	def __init__(self, index, method):
		# store our index of images
		# index here is a dictionary of histograms of images in our database
		# may be we need to change it to something different
		self.index = index
		# method to compute distance
		self.method = method

	def search(self, queryFeatures):
		# initialize our dictionary of results
		results = {}

		# loop over the index
		for (k, features) in self.index.items():
			# compute the chi-squared distance between the features
			# in our index and our query features -- using the
			# chi-squared distance which is normally used in the
			# computer vision field to compare histograms
			d = self.count_distance(features, queryFeatures)

			# now that we have the distance between the two feature
			# vectors, we can udpate the results dictionary -- the
			# key is the current image ID in the index and the
			# value is the distance we just computed, representing
			# how 'similar' the image in the index is to our query
			results[k] = d

		# sort our results, so that the smaller distances (i.e. the
		# more relevant images are at the front of the list)
		results = sorted([(v, k) for (k, v) in results.items()])

		# return our results
		return results

	def count_distance(self, histA, histB):
		if (not self.method):
			return self.chi2_distance(histA, histB)
		else:
		 	return d

	def chi2_distance(self, histA, histB, eps = 1e-10):
		# compute the chi-squared distance
		d = 0.5 * np.sum([((a - b) ** 2) / (a + b + eps)
			for (a, b) in zip(histA, histB)])

		# return the chi-squared distance
		return d