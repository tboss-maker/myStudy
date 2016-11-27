import requests
import json
# 网站接口请求抓取脚本

target_url = 'http://localhost/nn_cms/nn_cms_manager_v2/admin.php?m=vboss/m246_a_2&a=get_buy_order_count_list&nns_period=last_season&nns_mode=overview'

login_url= 'http://localhost/nn_cms/nn_cms_manager/index.php';
user_param = {'username':'YWRtaW4=','userpass':"f6fdffe48c908deb0f4c3bd36c032e72",'showuserpass':'YWRtaW4wNTMwMjc=','nns_language_id':'ch'}

s = requests.session()
s.post(login_url,user_param)

r = s.get(target_url)
result = json.dumps(r.json(),indent=4)
# print(r.json())
print(result)

