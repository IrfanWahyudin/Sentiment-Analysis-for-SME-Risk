# import sys
# import numpy as np
import math
from itertools import izip

# a = [1,2,5]
# b = [2,2,4]
# a = np.array(a)
# b = np.array(b)

# print a.shape
# print b.shape

# prod = sum(map(lambda x: x[0] * x[1], izip(a, b)))
# len1 = math.sqrt(sum(map(lambda x: x[0] * x[1], izip(a, a))))
# len2 = math.sqrt(sum(map(lambda x: x[0] * x[1], izip(b, b))))
# print prod / (len1*len2)

a = [2, 1, 0, 2, 0, 1, 1, 1]
b = [2, 1, 1, 1, 1, 0, 1, 1]


prod = sum(map(lambda x: x[0] * x[1], izip(a, b)))
len1 = math.sqrt(sum(map(lambda x: x[0] * x[1], izip(a, a))))
len2 = math.sqrt(sum(map(lambda x: x[0] * x[1], izip(b, b))))

print prod / (len1 * len2)

# for aitem, bitem in izip(a,b):
# 	print aitem, bitem

if "die" in ["Romeo died by dagger".lower()]:
	print "hello"