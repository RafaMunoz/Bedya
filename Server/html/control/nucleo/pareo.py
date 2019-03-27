import sys
import latch

appid = sys.argv[1]
secret = sys.argv[2]
codigo  = sys.argv[3]

latcheo = latch.Latch(appid, secret)
response = latcheo.pair(codigo)
cuenta_latch = str(response.get_data()['accountId'])

print cuenta_latch