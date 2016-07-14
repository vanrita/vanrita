/*
 * 注意：
 * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
 * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
 * 3. 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
 *
 * 如有问题请通过以下渠道反馈：
 * 邮箱地址：weixin-open@qq.com
 * 邮件主题：【微信JS-SDK反馈】具体问题
 * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
 */
wx.ready(function() {
    // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
    document.querySelector('#checkJsApi').onclick = function() {
        wx.checkJsApi({
            jsApiList: [
            'getNetworkType',
            'previewImage'
            ],
            success: function(res) {
                alert(JSON.stringify(res));
            }
        });
    }
    ;
    
    // 2. 分享接口
    // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
    document.querySelector('#onMenuShareAppMessage').onclick = function() {
        wx.onMenuShareAppMessage({
            title: '药直达',
            desc: '分享是个坑，还必须要加载微信js，获取token和签名，还要做缓存，我真醉了，PM们求不催进度，不然不分享了~',
            link: 'http://zvyoko.com/share',
            imgUrl: 'https://m.baidu.com/static/index/plus/plus_logo.png',
            trigger: function(res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                alert('用户点击发送给朋友');
            },
            success: function(res) {
                alert('已分享');
            },
            cancel: function(res) {
                alert('已取消');
            },
            fail: function(res) {
                alert(JSON.stringify(res));
            }
        });
        alert('点击右上角分享');
    }
    ;
    
    // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
    document.querySelector('#onMenuShareTimeline').onclick = function() {
        wx.onMenuShareTimeline({
            title: '药直达',
            link: 'http://zvyoko.com/share',
            imgUrl: 'https://m.baidu.com/static/index/plus/plus_logo.png',
            trigger: function(res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                alert('用户点击分享到朋友圈');
            },
            success: function(res) {
                alert('已分享');
            },
            cancel: function(res) {
                alert('已取消');
            },
            fail: function(res) {
                alert(JSON.stringify(res));
            }
        });
        alert('点击右上角分享');
    }
    ;
    
    // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
    document.querySelector('#onMenuShareQQ').onclick = function() {
        wx.onMenuShareQQ({
            title: '药直达',
            desc: '分享是个坑，还必须要加载微信js，获取token和签名，还要做缓存，我真醉了，PM们求不催进度，不然不分享了~',
            link: 'http://zvyoko.com/share',
            imgUrl: 'https://m.baidu.com/static/index/plus/plus_logo.png',
            trigger: function(res) {
                alert('用户点击分享到QQ');
            },
            complete: function(res) {
                alert(JSON.stringify(res));
            },
            success: function(res) {
                alert('已分享');
            },
            cancel: function(res) {
                alert('已取消');
            },
            fail: function(res) {
                alert(JSON.stringify(res));
            }
        });
        alert('点击右上角分享');
    };
    
    var shareData = {
        title: '药直达',
        desc: '分享是个坑，还必须要加载微信js，获取token和签名，还要做缓存，我真醉了，PM们求不催进度，不然不分享了~',
        link: 'http://zvyoko.com/share',
        imgUrl: 'https://m.baidu.com/static/index/plus/plus_logo.png',
    };
    wx.onMenuShareAppMessage(shareData);
    wx.onMenuShareTimeline(shareData);
    
    
}
);

wx.error(function(res) {
    alert(res.errMsg);
}
);

