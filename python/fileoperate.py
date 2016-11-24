import json
#python操作文件
#open方法创造file对象
# r只读
# r+可写,从顶部覆盖,文件不存在失败
# w 可写,不存在自动创建,覆盖
# w+ 可读可写
# a 只能写,从底部添加,不存在则创建
# a+ 可读可写,从顶部读取内容,底部添加内容,不存在创建

# 写入文件
# file = open('new.txt','a+')
# a = {'age':24,'money':500,'name':'lisi'}
# file.write(a)
# file.close()

# 读取文件
file = open('new.txt','a+')
# 指针归0
file.seek(0)
# 读取内容
a = file.read()
# 将字符串信息解析成json
a = json.loads(a)
print(a)
print(a['name'])



