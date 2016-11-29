import requests

url1 = 'http://localhost/login'
url2 = 'http://localhost/main'
loginInfo = {'username': 'root', 'pass': 'root'}

header = {"Accept": "text/html,application/xhtml+xml,application/xml;",
          "Accept-Encoding": "gzip",
          "Accept-Language": "zh-CN,zh;q=0.8",
          "Referer": "http://www.example.com/",
          "User-Agent": "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36"
          }
s = requests.session()
res1 = s.post(url1,data=loginInfo)
res2 = s.get(url2)
print(res1.text)

