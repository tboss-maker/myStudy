import pymysql

#mysql链接
db = pymysql.connect("localhost","root","","test")
sql = "select * from old_table"
#使用cursor()方法获取操作游标
cur = db.cursor()
# execute执行语句
cur.execute(sql)
# fetchone查询一条数据
# fetchall查询所有数据
if cur.rowcount:
    print(cur.fetchall())
else:
    print('查询失败')






