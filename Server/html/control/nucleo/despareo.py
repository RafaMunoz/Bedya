import sys
import latch

appid = sys.argv[1]
secret = sys.argv[2]
accid  = sys.argv[3]

latcheo = latch.Latch(appid, secret)
response = latcheo.unpair(accid)
