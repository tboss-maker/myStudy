import requests

# 登陆地址
url1 = 'http://localhost/login'
url2 = 'http://localhost/main'
loginInfo = {'user':'root','pass':'root'}
headers = { "Accept":"text/html,application/xhtml+xml,application/xml;",
            "Accept-Encoding":"gzip",
            "Accept-Language":"zh-CN,zh;q=0.8",
            "Referer":"http://www.example.com/",
            "User-Agent":"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36"
            }
# 登陆获取登录cookie
res1 = requests.post(url1,data = loginInfo,headers=headers)
# 登录之后的页面
res2 = requests.get(url2,cookies=res1.cookies,headers=headers)
print(res2.text)


