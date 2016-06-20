import sys
import numpy as np
import math
from itertools import izip
docs = ["Romeo and Juliet","Juliet O happy dagger","Romeo died by dagger","Live free or die thats the New-Hampshire motto","Did you know New-Hampshire is in New England"]
wordlist = ["romeo","juliet","happy","dagger","live","die","free","new-hampshire"]

args=[]
args_index=[]
num_of_query = 0
for i, arg in enumerate(sys.argv): 
	if i>0:		
		for j, word in enumerate(wordlist):
			if arg == word and arg not in args:							
				args.append(word)			
				args_index.append(j)
				num_of_query+=1

# print "Args",args
# print args_index


A = []
# for d in docs:
#     for t in d.lower().split(" "):
#         if t not in wordlist:
#             wordlist.append(t)

for d in docs:
    wv = [0 for x in range(0,len(wordlist))]
    for i,w in enumerate(wordlist):
        if w in d.lower():
            wv[i] = 1
    A.append(wv)

A = np.array(A).transpose().tolist()
# print A
SA, EA, UA = np.linalg.svd(A, full_matrices=True)
SA = SA.transpose()
# print SA #Eigen Vector B
# print EA #Eigen Value B
# print UA #Eigen Vector C

# print EA[:2]
# print SA[:2]
# print UA[:2]

SAS = SA[:2]
EAS = EA[:2]
UAS = UA[:2]

EAn = EAS.tolist()
# print EAn
EAL = [[0.0 for x in range(0, len(EAn))] for x in range(0, len(EAn))]
for x in range(0, len(EAn)):
    for y in range(0, len(EAn)):
        if x==y:
            EAL[x][y] = EAn[x]
EAS = np.array(EAL)

w = np.dot(SAS.transpose(), EAS)
d = np.dot(EAS.transpose(), UAS)
# print w.tolist()
# print d.transpose().tolist()
w = w.tolist()
d = d.transpose().tolist()
#Try die, dagger:
# word1 = w[5]
# word2 = w[3]
# print "word1",word1
# print "word2",word2
###################################################################
#		Begin Query
###################################################################
temp_sum = np.zeros(2)

for q in args_index:
	temp_sum = np.add(np.array(temp_sum), np.array(w[q])) 

# q = np.divide(np.add(np.array(word1), np.array(word2)), num_of_query)
q = np.divide(temp_sum, num_of_query)
# print "q", q
a = q

query_result = []
for i in range(0,4):
	b = d[i]

	prod = sum(map(lambda x: x[0] * x[1], izip(a, b)))
	len1 = math.sqrt(sum(map(lambda x: x[0] * x[1], izip(a, a))))
	len2 = math.sqrt(sum(map(lambda x: x[0] * x[1], izip(b, b))))

	query_result.append([i, prod / (len1 * len2)])

query_result_sorted = sorted(query_result, key=lambda x: x[1], reverse= True)

for j in range(0,4):
	print docs[query_result_sorted[j][0]],">"




