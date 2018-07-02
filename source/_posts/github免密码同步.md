# github免密码同步


## 使用git提交到github,每次都要输入用户名和密码的解决方法

    使用git提交文件到github,每次都要输入用户名和密码，操作起来很麻烦，以下方法可解决，记录以下。
    
    原因：在clone 项目的时候，使用了 https方式，而不是ssh方式。
    
    默认clone 方式是：https
    
    1.查看clone 地址：git remote -v
    2.移除https的方式，换成 ssh方式: git remote remove origin
    3.git remote add origin git地址
    4.查看push方式是否修改成功：git remote -v
    
## 1. 使用ssh方式免密码同步方式(ssh+git协议)

    github可以支持ssh公私钥登陆 , 当同步公钥到github后，可以方便的同步而不用输入密码。
    
    (1)客户端产生公私钥

    ssh-kengen -t rsa -C “some comments”               ,之后有三次询问，直接回车 
    
    ssh-keygen -t rsa                  # 一直回车下去，不输入密码
    
    cat ~/.ssh/id_rsa.pub
        
    (2)添加公钥到github账户

    使用网页登陆github，在settings–>ssh keys–>add key
   
    cat /home/user/.ssh/id_rsa.pub，把内容粘贴到网页上
    
    (3)测试是否成功

    ssh git@github.com
    
        The authenticity of host 'github.com (192.30.252.131)' can't be established.
    RSA key fingerprint is 16:27:ac:a5:76:28:2d:36:63:1b:56:4d:eb:df:a6:48.
    Are you sure you want to continue connecting (yes/no)? yes
    Warning: Permanently added 'github.com,192.30.252.131' (RSA) to the list of known hosts.
    PTY allocation request failed on channel 0
    Hi robinchenyu! You've successfully authenticated, but GitHub does not provide shell access.
    Connection to github.com closed

    客户端的下载的仓库应选用ssh方式

        git clone git@github.com:username/projectname.git

        git设置默认用户名
        
        $ git config --global user.name "username"
        
        $ git config --global user.email "address@mail"
        
## 2.密码缓存 ---  git在用https进行push时候免输账密的方法

https://git-scm.com/book/zh/v2/Git-%E5%B7%A5%E5%85%B7-%E5%87%AD%E8%AF%81%E5%AD%98%E5%82%A8

    https 方式每次都要输入密码，按照如下设置即可输入一次就不用再手输入密码的困扰而且又享受 https 带来的极速
    
    设置记住密码（默认15分钟）：
    
    git config --global credential.helper cache
    如果想自己设置时间，可以这样做：
    
    git config credential.helper 'cache --timeout=3600'     3600second = 1hours
    这样就设置一个小时之后失效
    
    长期存储密码：
    
    git config --global credential.helper store
 
    
    (1) 新建一个文件

    $ touch ~/.git-credentials
    $ vim ~/.git-credentials
    
    进去添加内容(github为github.com,码云为gitee.com)
    https://{username}:{passwd}@gitee.com
    
    (2)添加git配置
    
    $ git config --global credential.helper store
   
    (3) 查看～/.gitconfig文件的变化
    
    [credential]
        helper = store
        
        