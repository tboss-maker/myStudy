class parentClass:
    parent = 100
    __a = 1000;
    def __init__(self):
        print('parent is my')
    def par(self,name):
        print('这是父类:',name)
    def getA(self):
        return self.__a

class childClass(parentClass):
    __b = 500
    def test(self):
        print(self.parent)
    # 重写
    def par(self):
        print('重写')
    # python不支持重载
    def test1(self,a):
        print('重载',a)
    # def test1(self,a,b):
    #     print('重载',a,b)
    # def test1(self,a,b,c):
    #     print('重载',a,b,c)

    #     析构函数
    # def __del__(self):
    #     print("对象销毁")
# a = childClass()
# b = parentClass()
# b.par('my')
child = childClass()
# child.par()
child.test1("a")
print(child.getA())






